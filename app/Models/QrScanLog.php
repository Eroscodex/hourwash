<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QrScanLog extends Model
{
    const UPDATED_AT = null;

    protected $fillable = [
        'qr_code_id',
        'order_id',
        'scanned_by',
        'scan_type',
        'device',
        'ip_address',
    ];

    public function qrCode()
    {
        return $this->belongsTo(QrCode::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function scannedBy()
    {
        return $this->belongsTo(User::class, 'scanned_by');
    }
}
