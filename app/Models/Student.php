<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Student extends Model
{
    protected $fillable = ['tcno','name','surname','school_id','school_no'];
    use HasApiTokens, HasFactory, Notifiable;
}
