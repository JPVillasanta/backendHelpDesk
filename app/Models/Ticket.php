<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'ticket_number',
        'requester_name',
        'requester_email',
        'category',
        'priority',
        'subject',
        'description',
        'status',
    ];
}
