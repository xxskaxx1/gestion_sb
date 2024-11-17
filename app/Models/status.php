<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;

class status extends Model
{
    use HasApiTokens, HasFactory, HasRoles;
    protected $table = 'status';
    protected $primaryKey = 'id';
}
