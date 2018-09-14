<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tenants extends Model
{
    protected $table = 'tenants';
    protected $guarded = [];
    protected $hidden = ['updated_at'];

}
