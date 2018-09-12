<?php

namespace App\TenantsModel;

use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    protected $connection = 'client';
    protected $guarded = [];
}
