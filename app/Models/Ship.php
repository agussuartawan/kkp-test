<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ship extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'owner_name',
        'owner_address',
        'ship_size',
        'captain_name',
        'member_size',
        'photo',
        'licence_number',
        'licence_doc',
        'is_approved',
        'approval_note'
    ];
}
