<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tire extends Model
{
    protected $fillable = [
        'rehvitüüp',
        'hooaeg',
        'firma',
        'hind',
        'suurus',
        'seisund',
        'description',
        'image'
    ];
}
