<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailAction extends Model
{
    use HasFactory;

    // Specify the table if it doesn't follow Laravel's naming convention
    protected $table = 'email_actions';

    // Specify the fillable properties
    protected $fillable = [
        'token',
        'action',
        'version',
        'emailType',
    ];

    // If you need to prevent mass assignment for specific fields, use $guarded
    // protected $guarded = ['id', 'created_at', 'updated_at'];
    //
}
