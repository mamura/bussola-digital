<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderShipping extends Model
{
    use HasFactory;

    protected $table    = 'order_shipping';
    protected $fillable = [
        'order_id',
        'zip',
        'street',
        'number',
        'complement',
        'neighborhood',
        'city',
        'state',
        'recipient_name',
        'phone',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
