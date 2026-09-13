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

        // List of machine IDs currently occupied by OTHER active orders
        $occupiedByOthers = Order::whereNotIn('order_status', ['completed', 'cancelled', 'finish'])
            ->where('id', '!=', $this->id)
            ->whereNotNull('machine_id')
            ->pluck('machine_id')
            ->toArray();

        // 1. Fold Only orders or Finished/Completed/Cancelled orders -> No Machine Needed
        if ($isFoldOnly || in_array($status, ['completed', 'cancelled', 'finish'])) {
            if ($oldMachineId) {
                $otherUsingOld = Order::where('machine_id', $oldMachineId)
                    ->where('id', '!=', $this->id)
                    ->whereNotIn('order_status', ['completed', 'cancelled', 'finish'])
                    ->exists();

                if (! $otherUsingOld) {
                    Machine::where('id', $oldMachineId)->update([
                        'current_order_id' => null,
                        'status' => 'idle',
                        'remaining_minutes' => null,
                        'last_status_update' => now(),
                    ]);
                }
            }
            $this->machine_id = null;

            return;
        }

        // 2. Manual machine selection override by Staff/Admin
        if ($hasManualMachineInput) {
            if ($manualMachineId && $manualMachineId != $oldMachineId) {
                if ($oldMachineId && ! in_array($oldMachineId, $occupiedByOthers)) {
                    Machine::where('id', $oldMachineId)->update([
                        'current_order_id' => null,
                        'status' => 'idle',
                        'remaining_minutes' => null,
                        'last_status_update' => now(),
                    ]);
                }
                $this->machine_id = $manualMachineId;
            } elseif (! $manualMachineId) {
                if ($oldMachineId && ! in_array($oldMachineId, $occupiedByOthers)) {
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

        // 3. Stage-based Auto Assignment (excluding machines occupied by other active orders)
        if (in_array($status, ['washing', 'rinsing']) || ($status === 'received' && ! $isDryOnly) || ($status === 'pending' && ! $isDryOnly)) {
            // Requires a Washer
            $currentMachine = $this->machine_id ? Machine::find($this->machine_id) : null;
            $isCurrentWasherValid = $currentMachine
                && in_array($currentMachine->machine_type, ['washer', 'washer_dryer'])
                && ! in_array($currentMachine->id, $occupiedByOthers);

            if (! $isCurrentWasherValid) {
                if ($currentMachine && ! in_array($currentMachine->id, $occupiedByOthers)) {
                    $currentMachine->update([
                        'current_order_id' => null,
                        'status' => 'idle',
                        'remaining_minutes' => null,
                        'last_status_update' => now(),
                    ]);
                }
                $availWasher = Machine::where('status', 'idle')
                    ->where(function ($q) {
                        $q->whereNull('current_order_id')
                            ->orWhereDoesntHave('currentOrder', function ($sub) {
                                $sub->whereNotIn('order_status', ['completed', 'cancelled', 'finish']);
                            });
                    })
                    ->whereNotIn('id', $occupiedByOthers)
                    ->whereIn('machine_type', ['washer', 'washer_dryer'])
                    ->first();

                if ($availWasher) {
                    $this->machine_id = $availWasher->id;
                    $currentMachine = $availWasher;
                } else {
                    $this->machine_id = null;
                    $currentMachine = null;
                }
            }

            if ($currentMachine) {
                $machineStatus = in_array($status, ['washing', 'rinsing']) ? $status : 'idle';
                $remMins = ($status === 'washing') ? 35 : (($status === 'rinsing') ? 15 : null);
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
            $isCurrentDryerValid = $currentMachine
                && in_array($currentMachine->machine_type, ['dryer', 'washer_dryer'])
                && ! in_array($currentMachine->id, $occupiedByOthers);

            if (! $isCurrentDryerValid) {
                if ($currentMachine && ! in_array($currentMachine->id, $occupiedByOthers)) {
                    $currentMachine->update([
                        'current_order_id' => null,
                        'status' => 'idle',
                        'remaining_minutes' => null,
                        'last_status_update' => now(),
                    ]);
                }
                $availDryer = Machine::where('status', 'idle')
                    ->where(function ($q) {
                        $q->whereNull('current_order_id')
                            ->orWhereDoesntHave('currentOrder', function ($sub) {
                                $sub->whereNotIn('order_status', ['completed', 'cancelled', 'finish']);
                            });
                    })
                    ->whereNotIn('id', $occupiedByOthers)
                    ->whereIn('machine_type', ['dryer', 'washer_dryer'])
                    ->first();

                if ($availDryer) {
                    $this->machine_id = $availDryer->id;
                    $currentMachine = $availDryer;
                } else {
                    $this->machine_id = null;
                    $currentMachine = null;
                }
            }

            if ($currentMachine) {
                $machineStatus = ($status === 'drying') ? 'drying' : 'idle';
                $remMins = ($status === 'drying') ? 40 : null;
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
