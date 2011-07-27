<?php

class Mobicart_user {
	function Mobicart_user($api) {
		$this->api = $api;

		$this->api->add_alias("get_merchant_profile", array($this, "get"));
		$this->api->add_alias("update_merchant_profile", array($this, "update"));
	}

	/**
	* Returns the user data
	* http://www.mobi-cart.com/docs/api/merchantProfile_API.html
	*/
	function get($username = false) {
		$this->api->requires("user_name")
					->method("get")
					->path("MerchantProfile")
					->add_url_segment("merchant-profile.json", "method");

		if($username)
			$this->api->add_param("user_name", $username);

		return $this->api;
	}

	/**
	* Updates a Mobicart merchant profile
	* http://www.mobi-cart.com/docs/api/updateMerchantProfile_API.html
	* Example:	update(false, "Richard", "Lyon", "http://example.com/logo.png", "http://example.com", false, 12345, 6789)
	*/
	function update($username = false, $fn = false, $ln = false, $logo = false, $website = false, $paypal = false, $regno = false, $taxno = false) {
		$this->api->requires("user_name", "firstName")
					->optional("lastName", "companyLogoUrl", "companyWebsite", "payPalAddress", "companyRegNumber", "taxRegNumber")
					->method("post")
					->path("message", "message")
					->add_url_segment("update-merchant-profile.json", "method");

		if($username)
			$this->api->add_param("user_name", $username);
		if($fn)
			$this->api->add_param("firstName", $fn);
		if($ln)
			$this->api->add_param("lastName", $ln);
		if($logo)
			$this->api->add_param("companyLogoUrl", $logo);
		if($website)
			$this->api->add_param("companyWebsite", $website);
		if($paypal)
			$this->api->add_param("payPalAddress", $paypal);
		if($regno)
			$this->api->add_param("companyRegNumber", $regno);
		if($taxno)
			$this->api->add_param("taxRegNumber", $taxno);

		return $this->api;
	}
}