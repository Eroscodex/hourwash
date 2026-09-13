<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_number',
        'customer_id',
        'service_id',
        'machine_id',
        'weight_kg',
        'subtotal',
        'delivery_fee',
        'discount',
        'total_amount',
        'payment_status',
        'payment_method',
        'paid_at',
        'order_status',
        'pickup_type',
        'pickup_date',
        'pickup_time',
        'estimated_completion',
        'completed_at',
        'notes',
    ];

    protected $casts = [
        'estimated_completion' => 'datetime',
        'completed_at' => 'datetime',
        'paid_at' => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function machine()
    {
        return $this->belongsTo(Machine::class);
    }

    public function qrCode()
    {
        return $this->hasOne(QrCode::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function feedback()
    {
        return $this->hasOne(CustomerFeedback::class);
    }

    public function statusHistory()
    {
        return $this->hasMany(OrderStatusHistory::class, 'order_id');
    }

    public function syncMachineAssignment(?string $targetStatus = null, ?int $manualMachineId = null, bool $hasManualMachineInput = false): void
    {
        $status = $targetStatus ?? $this->order_status;
        $serviceType = strtolower($this->service?->service_type ?? '');
        $serviceName = strtolower($this->service?->name ?? '');

        $isFoldOnly = ($serviceType === 'fold') || (str_contains($serviceName, 'fold') && ! str_contains($serviceName, 'wash') && ! str_contains($serviceName, 'dry'));
        $isWashOnly = ($serviceType === 'wash') || (str_contains($serviceName, 'wash') && ! str_contains($serviceName, 'dry') && ! str_contains($serviceName, 'fold') && ! str_contains($serviceName, 'full'));
        $isDryOnly = ($serviceType === 'dry') || (str_contains($serviceName, 'dry') && ! str_contains($serviceName, 'wash') && ! str_contains($serviceName, 'full'));

        $oldMachineId = $this->machine_id;

        // 1. Fold Only orders or Finished/Completed/Cancelled orders -> No Machine Needed
        if ($isFoldOnly || in_array($status, ['completed', 'cancelled', 'finish'])) {
            if ($oldMachineId) {
                Machine::where('id', $oldMachineId)->update([
                    'current_order_id' => null,
                    'status' => 'idle',
                    'remaining_minutes' => null,
                    'last_status_update' => now(),
                ]);
            }
            $this->machine_id = null;

            return;
        }

        // 2. Manual machine selection override by Staff/Admin
        if ($hasManualMachineInput) {
            if ($manualMachineId && $manualMachineId != $oldMachineId) {
                if ($oldMachineId) {
                    Machine::where('id', $oldMachineId)->update([
                        'current_order_id' => null,
                        'status' => 'idle',
                        'remaining_minutes' => null,
                        'last_status_update' => now(),
                    ]);
                }
                $this->machine_id = $manualMachineId;
            } elseif (! $manualMachineId) {
                if ($oldMachineId) {
                    Machine::where('id', $oldMachineId)->update([
                        'current_order_id' => null,
                        'status' => 'idle',
                        'remaining_minutes' => null,
                        'last_status_update' => now(),
                    ]);
                }
                $this->machine_id = null;

                return;
            }
        }

        // 3. Stage-based Auto Assignment (if no machine assigned yet, or stage transition requires machine type change)
        if (in_array($status, ['washing', 'rinsing']) || ($status === 'received' && ! $isDryOnly) || ($status === 'pending' && ! $isDryOnly)) {
            // Requires a Washer
            $currentMachine = $this->machine_id ? Machine::find($this->machine_id) : null;
            $isCurrentWasher = $currentMachine && in_array($currentMachine->machine_type, ['washer', 'washer_dryer']);

            if (! $isCurrentWasher) {
                if ($currentMachine) {
                    $currentMachine->update([
                        'current_order_id' => null,
                        'status' => 'idle',
                        'remaining_minutes' => null,
                        'last_status_update' => now(),
                    ]);
                }
                $availWasher = Machine::where('status', 'idle')
                    ->whereIn('machine_type', ['washer', 'washer_dryer'])
                    ->first();

                if ($availWasher) {
                    $this->machine_id = $availWasher->id;
                    $currentMachine = $availWasher;
                }
            }

            if ($currentMachine) {
                $machineStatus = in_array($status, ['washing', 'rinsing']) ? $status : 'washing';
                $remMins = ($status === 'washing') ? 35 : (($status === 'rinsing') ? 15 : 35);
                $currentMachine->update([
                    'current_order_id' => $this->id,
                    'status' => $machineStatus,
                    'remaining_minutes' => $remMins,
                    'last_status_update' => now(),
                ]);
            }
        } elseif ($status === 'drying' || ($status === 'received' && $isDryOnly) || ($status === 'pending' && $isDryOnly)) {
            // Requires a Dryer
            $currentMachine = $this->machine_id ? Machine::find($this->machine_id) : null;
            $isCurrentDryer = $currentMachine && in_array($currentMachine->machine_type, ['dryer', 'washer_dryer']);

            if (! $isCurrentDryer) {
                // Release washer machine so it becomes idle for other orders
                if ($currentMachine) {
                    $currentMachine->update([
                        'current_order_id' => null,
                        'status' => 'idle',
                        'remaining_minutes' => null,
                        'last_status_update' => now(),
                    ]);
                }
                $availDryer = Machine::where('status', 'idle')
                    ->whereIn('machine_type', ['dryer', 'washer_dryer'])
                    ->first();

                if ($availDryer) {
                    $this->machine_id = $availDryer->id;
                    $currentMachine = $availDryer;
                }
            }

            if ($currentMachine) {
                $machineStatus = 'drying';
                $remMins = 40;
                $currentMachine->update([
                    'current_order_id' => $this->id,
                    'status' => $machineStatus,
                    'remaining_minutes' => $remMins,
                    'last_status_update' => now(),
                ]);
            }
        }
    }
}
