<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    protected $table = 'posts'; // Specify the table name if it's not the plural of the model name
    protected $fillable = ['title', 'content']; // Specify the fillable fields for mass assignment
    public $timestamps = true; // Enable timestamps if you want to use created_at and updated_at
}
