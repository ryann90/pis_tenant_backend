<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tenants;
use App\Traits\TenantTraits;
use App\TenantsModel\User;
use App\TenantsModel\Employee_details;
use App\TenantsModel\Company;
use Validator;

class TenantController extends Controller 
{
    Use TenantTraits;

    public function registerTenant(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lname' => 'required|min:2',
            'mname' => 'required|min:2',
            'fname' => 'required|min:2',
            'subscription_type' => ["required","regex:(type1|type2|type3)"],
            'link' => "required|unique:tenants,link",
            'email' => 'required',
            'companyname' => 'required',
            'password' => 'required|confirmed',
            'password_confirmation' => 'required'
        ],[
            'companyname.required' => "Company name is required",
            'password_confirmation.required' => "Password does not match!!!!",
        ]);

        $subs_type = [
            'type1' => 'free_db',
            'type2' => 'standard_db',
            'type3' => 'premium_db'
        ];

        if ( !$validator->fails() ) {

            if( $request->post('subscription_type') == 'type1')
            {
                $counter = 0;
                // get function from the Traits
                $response_array = $this->clientInsertion($request, $subs_type[$request->subscription_type]);
                // change the db connection to free_db
                clientConnect('127.0.0.1','free_db','root');
                // runs the function migrateClientTables to create new tables in the db
                // set the table name and company id
                $tbl = $request->post('link').$response_array['company']['company_id'];
                migrateClientTables($tbl);
                //insert data to new table

                 //insert data to Company table
                 $company = new Company();
                 ($company->setTable($tbl.'_company')->insert([$response_array['company']])) ? $counter++ : '';

                //insert data to Users table
                $users = new User();
                ($users->setTable($tbl.'_users')->insert([$response_array['users']])) ? $counter++ : '';

                //insert data to Employee table
                $employee_details = new Employee_details();
                ($employee_details->setTable($tbl.'_employee_details')->insert([$response_array['employee_details']]) ? $counter++ : '');

                //return if successful
                if ($counter == 3) {
                    $data = [
                        'msg' => 'successfully created free account',
                        'company_name' => $request->post('companyname'),
                        'subs_type' => $subs_type[$request->subscription_type],
                        'status' => 'true',
                        'company_link' => env('APP_URL')
                    ];

                    return response()->json($data);
                } else {
                    $data = [
                        'msg' => 'failed to create free account',
                        'company_name' => $request->post('companyname'),
                        'subs_type' => $subs_type[$request->subscription_type],
                        'status' => 'false'
                    ];
                    return response()->json($data);
                }
                
            } 

            else if( $request->post('subscription_type') == 'type2') 
            {
                $counter = 0;

                // get function from the Traits
                $response_array = $this->clientInsertion($request, $subs_type[$request->subscription_type]);
                // change the db connection to standard_db
                clientConnect('127.0.0.1','standard_db','root');
                // runs the function migrateClientTables to create new tables in the db
                // set the table name and company id
                $tbl = $request->post('link').$response_array['company']['company_id'];
                migrateClientTables($tbl);
                //insert data to new table

                //insert data to Company table
                $company = new Company();
                ($company->setTable($tbl.'_company')->insert([$response_array['company']])) ? $counter++ : '';

                //insert data to Users table
                $users = new User();
                ($users->setTable($tbl.'_users')->insert([$response_array['users']])) ? $counter++ : '';

                //insert data to Employee table
                $employee_details = new Employee_details();
                ($employee_details->setTable($tbl.'_employee_details')->insert([$response_array['employee_details']])) ? $counter++ : '';

                //return if successful
                if ($counter == 3) {
                    $data = [
                        'msg' => 'successfully created free account',
                        'company_name' => $request->post('companyname'),
                        'subs_type' => $subs_type[$request->subscription_type],
                        'status' => 'true',
                        'company_link' => env('APP_URL')
                    ];
                    
                    return response()->json($data);
                } else {
                    $data = [
                        'msg' => 'failed to create free account',
                        'company_name' => $request->post('companyname'),
                        'subs_type' => $subs_type[$request->subscription_type],
                        'status' => 'false'
                    ];
                    return response()->json($data);
                }
                
            }

            else if( $request->post('subscription_type') == 'type3') 
            {
                $counter = 0;
                // get function from the Traits
                $response_array = $this->clientInsertion($request, $subs_type[$request->subscription_type]);
                // change the db connection to premium_db
                clientConnect('127.0.0.1','premium_db','root');
                // runs the function migrateClientTables to create new tables in the db
                // set the table name and company id
                $tbl = $request->post('link').$response_array['company']['company_id'];
                migrateClientTables($tbl);
                //insert data to new table

                //insert data to Company table
                $company = new Company();
                ($company->setTable($tbl.'_company')->insert([$response_array['company']])) ? $counter++ : '';
                
                //insert data to Users table
                $users = new User();
                ($users->setTable($tbl.'_users')->insert([$response_array['users']])) ? $counter++ : '';

                //insert data to Employee table
                $employee_details = new Employee_details();
                ($employee_details->setTable($tbl.'_employee_details')->insert([$response_array['employee_details']])) ? $counter++ : '';

                //return if successful
                if ($counter == 3) {
                    $data = [
                        'msg' => 'successfully created free account',
                        'company_name' => $request->post('companyname'),
                        'subs_type' => $subs_type[$request->subscription_type],
                        'status' => 'true',
                        'company_link' => env('APP_URL')
                    ];

                    return response()->json($data);
                } else {
                    $data = [
                        'msg' => 'failed to create free account',
                        'company_name' => $request->post('companyname'),
                        'subs_type' => $subs_type[$request->subscription_type],
                        'status' => 'false'
                    ];
                    return response()->json($data);
                }
                
            }
            
            else
            {
                dd('subscription not found');
            }
        }

        else
        {
            //else if validation fails
            return response()->json(['errors'=>$validator->errors()]);
        }
    }
}
