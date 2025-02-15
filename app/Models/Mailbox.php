<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Mailbox extends Model
{
    use HasFactory;

    protected $fillable = [
        'email', 'password', 'host', 'port', 'ssl',
    ];
}