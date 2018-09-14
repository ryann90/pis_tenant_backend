<?php

namespace App\Traits;

use App\Tenants;

trait TenantTraits{

	function clientInsertion($request, $database){

		$owner_id = randomNumber();
		$company_id = randomNumber(10);

		Tenants::insert([
			'company_id'=>$company_id,
			'owner_id'=>$owner_id,
			'company_name'=>$request->post('companyname'),
			'owner_email'=>$request->post('email'),
			'subscription_type'=>$request->post('subscription_type'),
			'database'=>$database,
			'tbl'=>$request->post('link').$company_id,
			'link'=>$request->post('link')
		]);

		$data = [
			'company' => ['company_id' => $company_id, 'name' => $request->post('companyname'), 'email' => $request->post('email')],
			'users' => ['user_id' => $owner_id, 'company_id' => $company_id, 'email' => $request->post('email'), 'password' => $request->post('password_confirmation')],
			'employee_details' => ['user_id' => $owner_id, 'fname' => $request->post('fname'), 'mname' => $request->post('mname'), 'lname' => $request->post('lname')], 
		];
		
		return $data;
	}
}