<?php

namespace App\TenantsModel;

use Illuminate\Database\Eloquent\Model;

class Employee_details extends Model
{
    protected $connection = 'client';
    protected $guarded = [];
}
