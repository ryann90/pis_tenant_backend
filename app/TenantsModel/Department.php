<?php

namespace App\TenantsModel;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $connection = 'client';
    protected $guarded = [];
}
