<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Contact extends Model
{
    use HasFactory;
    protected $fillabe = [
        'Phone',
        'Address',
        'customer_id',
    ];
    public $timestamps = true; // Enable timestamps if you want to use created_at and updated_at
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
