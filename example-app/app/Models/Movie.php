<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Movie extends Model
{
    // Define the properties and methods for the Movie model
    use HasFactory;

    // protected $table = '_movies'; // Specify the table name if it's not the plural of the model name
    // protected $fillable = ['title', 'genre', 'description']; // Specify the fillable fields for mass assignment
    // public $timestamps = true; // Enable timestamps if you want to use created_at and updated_at
    // Define any relationships, scopes, or custom methods here
}
