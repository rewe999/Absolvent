<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exchange extends Model
{
    use HasFactory;

    public $timestamps=false;

    protected $fillable = ['currency', 'code', 'mid'];
}
