<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tenants;
use App\Traits\TenantTraits;
use Validator;

class TenantController extends Controller 
{
    Use TenantTraits;

    public function registerTenant(Request $request)
    {
        $validator = Validator::make($request->all(), [
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

        if ( !$validator->fails() ) {

            if( $request->post('subscription_type') == 'type1')
            {
                $company_id = $this->clientInsertion($request, 'free_db');
                clientConnect('127.0.0.1','free_db','root');
                migrateClientTables($request->post('link').$company_id);
                dd('Free Subscription');
            } 

            else if( $request->post('subscription_type') == 'type2') 
            {
                $company_id = $this->clientInsertion($request, 'standard_db');
                clientConnect('127.0.0.1','standard_db','root');
                migrateClientTables($request->post('link').$company_id);
                dd('standard subscription');
            }

            else if( $request->post('subscription_type') == 'type3') 
            {
                $company_id = $this->clientInsertion($request, 'premium_db');
                clientConnect('127.0.0.1','premium_db','root');
                migrateClientTables($request->post('link').$company_id);
                dd('premium subscription');
            }

            else
            {
                dd('subscription not found');
            }
        }

        else
        {
            dd('failed');
            //return back to registration
        }
    }
}
