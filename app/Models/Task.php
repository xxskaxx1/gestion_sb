<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;

class Task extends Model
{
    use HasApiTokens, HasFactory, HasRoles;
    protected $table = 'task';
    protected $primaryKey = 'id';
    //public $timestamps = false;
    //protected $guarded = [];
}
