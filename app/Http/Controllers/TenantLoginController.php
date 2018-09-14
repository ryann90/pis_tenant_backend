<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Tenants;
use App\Traits\TenantTraits;
use App\TenantsModel\User;
use Validator;

class TenantLoginController extends Controller
{
    Use TenantTraits;
    
    public function login (Request $request) {
        
        $validation = Validator::make($request->all(),[
            'username' => 'required',
            'link' => 'required',
            'password' => 'required',
        ]);

        if ( !$validation->fails()) {
            // finds the tenant info in the database tenants for validation
            $tenant = Tenants::where('link', $request->post('link'))->first();

            // if the tenant access wrong link or null link redirect the tenant back
            if ( $tenant['link'] == null || $tenant['link'] != $request->post('link')) {
                return response()->json([
                    'status' => 'failed',
                    'msg' => 'error link'
                ]);

            } else {

                //sets the db connection base on the users login
                clientConnect('127.0.0.1', $tenant['database'], 'root');

                //sets the table of specific user to its correct table
                //always add new User(); to be able to work the code properly
                $users = new User();
                $user_model = $users->setTable($tenant['tbl']."_users");
                // first check email 
                if ($user = $user_model->where(['email' => $request->username, 'password' => $request->password])->first()) {
                    return response()->json([
                        'status' => 'success', // or failed
                        'msg' =>  [
                            'user' => $user,
                            'subscription' => $tenant,
                        ]
                    ]);

                } else {

                    // once email and password not match
                    // lets check emp_num
                    if ($user = $user_model->where(['emp_num' => $request->username, 'password' => $request->password])->first()) {
                        return response()->json([
                            'status' => 'success', // or failed
                            'msg' =>  [
                                'user' => $user,
                                'subscription' => $tenant, // show only relevant
                            ]
                        ]);
    
                    }
                    else {
                        // if emp_num and email with password
                        // did really not matched 
                        // throw error message
                        return response()->json([
                            'status' => 'failed', // or failed
                            'msg' => 'Incorrect username or password'
                        ]);
                    }
                   
                }
            }
        } else {
            return response()->json([ 'msg' => $validation->errors(), 'status' => 'failed']);
        }
    }
}
