<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailStats extends Model
{
    use HasFactory;

    // Specify the table name if it doesn't follow Laravel's naming convention
    protected $table = 'email_stats';

    // Define the fillable attributes
    protected $fillable = [
        'email_id',
        'action',
        'action_date',
    ];

    // If you want to use Carbon for date manipulation, no need to do anything special
    // Laravel automatically handles the timestamps and date fields.
}
