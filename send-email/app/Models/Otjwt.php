<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtJWT extends Model {
    use HasFactory;

    // The table associated with the model (optional, Laravel defaults to plural of the model name)
    protected $table = 'otjwt';

    // The attributes that are mass assignable
    protected $fillable = [
        'email',
        'token',
        'active',
    ];

    // Optionally, you can define any hidden attributes
    protected $hidden = [
        'token', // If you want to hide the token when converting to array or JSON
    ];
    
    // If you want to use custom timestamps
    // const CREATED_AT = 'created_at';
    // const UPDATED_AT = 'updated_at';
}
