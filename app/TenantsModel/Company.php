<?php

namespace App\TenantsModel;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $connection = 'client';
    protected $guarded = [];

    public function getAllUsers(){
        return $this->hasMany('App\TenantsModel\User', 'company_id', 'company_id');
    }
}
