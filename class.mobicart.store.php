<?php

class Mobicart_Store {

	function __construct($api) {
		$this->api = $api;

		$this->api->add_alias("get_store", array($this, "get"));
		$this->api->add_alias("get_store_settings", array($this, "get_settings"));
		$this->api->add_alias("get_store_shipping", array($this, "get_shipping"));
		$this->api->add_alias("get_store_tax", array($this, "get_tax"));
		$this->api->add_alias("get_countries", array($this, "get_countries"));
		$this->api->add_alias("get_states", array($this, "get_states"));
		$this->api->add_alias("get_shipping_rate", array($this, "get_shipping_rate"));
		$this->api->add_alias("set_shipping_rate", array($this, "set_shipping_rate"));
	}

	/**
	* Returns a list of stores for the $username
	* http://www.mobi-cart.com/docs/api/store_API.html
	*/
	function get($username = false) {
		$this->api->requires("user_name")
					->method("get")
					->path("store")
					->add_url_segment("stores.json", "method");

		if($username)
			$this->api->add_param("user_name", $username);

		return $this->api;
	}

	/**
	* Returns the settings of the store $store_id
	* http://www.mobi-cart.com/docs/api/storeSettings_API.html
	*/
	function get_settings($username = false, $store_id = false) {
		$this->api->requires("user_name", "store_id")
					->method("get")
					->path("store")
					->add_url_segment("store-settings.json", "method");

		if($username)
			$this->api->add_param("user_name", $username);
		if($store_id)
			$this->api->add_param("store_id", $store_id);

		return $this->api;
	}

	/**
	* Returns the shipping details of the store $store_id
	* http://www.mobi-cart.com/docs/api/storeShipping_API.html
	*/
	function get_shipping($username = false, $store_id = false) {
		$this->api->requires("user_name", "store_id")
					->method("get")
					->path("store")
					->add_url_segment("store-shipping.json", "method");

		if($username)
			$this->api->add_param("user_name", $username);
		if($store_id)
			$this->api->add_param("store_id", $store_id);

		return $this->api;
	}

	/**
	* Returns the shipping details of the store $store_id
	* http://www.mobi-cart.com/docs/api/storeTax_API.html
	*/
	function get_tax($username = false, $store_id = false) {
		$this->api->requires("user_name", "store_id")
					->method("get")
					->path("store")
					->add_url_segment("store-tax.json", "method");

		if($username)
			$this->api->add_param("user_name", $username);
		if($store_id)
			$this->api->add_param("store_id", $store_id);

		return $this->api;
	}

	/**
	* Returns a list of countries for the $username
	* http://www.mobi-cart.com/docs/api/getCountries_API.html
	*/
	function get_countries($username = false) {
		$this->api->requires("user_name")
					->method("get")
					->path("countries")
					->add_url_segment("countries.json", "method");

		if($username)
			$this->api->add_param("user_name", $username);

		return $this->api;
	}

	/**
	* Returns a list of states for the country $country
	* http://www.mobi-cart.com/docs/api/getStates_API.html
	*/
	function get_states($username = false, $country = false) {
		$this->api->requires("user_name", "territory_id")
					->method("get")
					->path("states")
					->add_url_segment("states.json", "method");

		if($username)
			$this->api->add_param("user_name", $username);
		if($country)
			$this->api->add_param("territory_id", $country);

		return $this->api;
	}

	/**
	* Returns the shipping rate to $state of $country for user $username
	* http://www.mobi-cart.com/docs/api/shippingRate_API.html
	*
	* Example: get_shipping_rate(USER, STORE, 86, 2758);
	*			returns the shipping info for Liverpool, UK
	*/
	function get_shipping_rate($username = false, $store_id = false, $country = false, $state = false) {
		$this->api->requires("user_name", "store_id", "country_id", "state_id")
					->method("get")
					->path("Shipping")
					->add_url_segment("shipping-rate.json", "method");

		if($username)
			$this->api->add_param("user_name", $username);
		if($store_id)
			$this->api->add_param("store_id", $store_id);
		if($country)
			$this->api->add_param("country_id", $country);
		if($state)
			$this->api->add_param("state_id", $state);

		return $this->api;
	}

	/**
	* Sets the shipping rate for a $state in a $country, requires $single and $multiple, for $store_id
	* http://www.mobi-cart.com/docs/api/setShippingRate_API.html
	*
	* Example: set_shipping_rate(USER, STORE, 86, 2758, 5, 10);
	*			Adds/updates shipping for Liverpool, UK to single=5, multiple=10
	*/
	function set_shipping_rate($username = false, $store_id = false, $country = false, $state = false, $single = false, $multiple = false) {
		$this->api->requires("user_name", "store_id", "country_id", "state_id", "shipping_single", "shipping_multiple")
					->method("post")
					->path("message", "message")
					->add_url_segment("set-shipping-rate.json", "method");

		if($username)
			$this->api->add_param("user_name", $username);
		if($store_id)
			$this->api->add_params("store_id", $store_id);
		if($country)
			$this->api->add_param("country_id", $country);
		if($state)
			$this->api->add_param("state_id", $state);
		if($single)
			$this->api->add_param("shipping_single", $single);
		if($multiple)
			$this->api->add_param("shipping_multiple", $multiple);

		return $this->api;
	}

}