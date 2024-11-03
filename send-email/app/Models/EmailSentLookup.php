<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailSentLookup extends Model
{
    use HasFactory;

    // Specify the table name if it's not the plural form of the model
    protected $table = 'email_sent_lookup';

    // Allow mass assignment for these fields
    protected $fillable = [
        'token',
        'email_id',
    ];

    // Optional: If you want to hide sensitive fields from JSON output
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
