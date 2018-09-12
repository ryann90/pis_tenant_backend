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
		return $company_id;
	}
}