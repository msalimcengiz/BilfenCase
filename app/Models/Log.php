<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Log extends Model
{
    protected $fillable = ['auth','operation','table','table_item_id'];
    use HasApiTokens, HasFactory, Notifiable;
}
