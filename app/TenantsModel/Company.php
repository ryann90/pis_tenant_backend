<?php

namespace App\TenantsModel;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $connection = 'client';
    protected $guarded = [];
}
