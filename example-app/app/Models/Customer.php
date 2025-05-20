<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use HasFactory;
    protected $fillable = [
        'FirstName',
        'LastName',
    ];
    public $timestamps = true; // Enable timestamps if you want to use created_at and updated_at
    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }
}
