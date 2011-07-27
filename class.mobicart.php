<?php

require_once("class.request.php");
require_once("class.mobicart.store.php");
require_once("class.mobicart.product.php");
require_once("class.mobicart.user.php");
require_once("class.mobicart.images.php");
require_once("class.mobicart.order.php");
require_once("class.mobicart.department.php");

class Mobicart extends Request {

	var $base_url = "www.mobi-cart.com/api";
	var $aliases = array();

	function Mobicart($key) {
		parent::Request($this->base_url);

		$this->set_key($key);

		$this->store = new Mobicart_Store($this);
		$this->product = new Mobicart_Product($this);
		$this->user = new Mobicart_User($this);
		$this->images = new Mobicart_Images($this);
		$this->order = new Mobicart_Order($this);
		$this->department = new Mobicart_Department($this);



		$this->dept =& $this->department;
	}

	function set_key($key) {
		$this->remove("api_key");

		$this->key = $key;
		$this->add_param("api_key", $key, true);

		return $this;
	}

	/**
	* Add a function alias for non-ambiguous call().
	*/
	function add_alias($alias, $callback) {
		$this->aliases[$alias] = $callback;
	}

	function __call($name, $arguments) {
		if(isset($this->aliases[$name]) && is_callable($this->aliases[$name]))
			return call_user_func_array($this->aliases[$name], $arguments);

		return call_user_func_array(array($this,$name), $arguments);
	}

	/**
	* The fields the next request should require/validate
	*/
	function requires() {
		$this->requires = func_get_args();
		return $this;
	}

	/**
	* The fields the next request should include but not require
	*/
	function optional() {
		$this->optional = func_get_args();
		return $this;
	}

	/**
	* Checks all fields in @requires for existence and correctness
	*/
	function validate() {
		$this->validation_error = false;

		if(!$this->has("url", "method")) {
			$this->validation_error = "No method set";
			return false;
		}

		$method = $this->get("url", "method");

		foreach($this->requires as $key) {
			$value = $this->get("param", $key);
			if($value === FALSE)
				$this->validation_error = "Method $method requires a $key.";
			else
				$this->check($key, $value);
		}

		return !($this->validation_error);
	}

	function check($key, $value) {
		switch ($key) {
			case "user_name": #global
			case "s_merchant_paypal_email": # order add
			case "s_buyer_email": # order add
			case "payPalAddress":
				if(!strpos($value, "@"))
					$this->validation_error = "User_name must be an email address.";
				break;

			case "from_date": # order get_by_date
			case "to_date": # order get_by_date
			case "d_order_date": # order add
				if(!preg_match("/(2[0-9][0-9][0-9]-[0-1][0-9]-[0-3][0-9])/", $value))
					$this->validation_error = $key . " must be a date in the format YYYY-MM-DD";
				break;

			case "shipping_carrier": # add shipping status
				if(!in_array($value, array("fedex","ups","other")))
					$this->validation_error = $key . " must be one of [fedex, ups, other]";
				break;

			case "shipping_status": # add shipping stauts, update shipping status
				if(!in_array($value, array("pickedup", "intransit", "arrived", "delivered")))
					$this->validation_error = $key . " must be one of [pickedup, intransit, arrived, delivered]";
				break;

			case "s_status": # order add
				if(!in_array($value, array("pending","cancel","processing","completed")))
					$this->validation_error = $key . " must be one of [pending, cancel, processing, completed]";
				break;

			case "status": # product add/edit
			case "department_status":
			case "category_status":
				if(!in_array($value, array("active","hidden","sold","coming")))
					$this->validation_error = "Status must be one of these : [active, hidden, sold, coming]";
				break;

			case "product_file": # product bulk upload
				$value = substr($value, 1);
				if(!file_exists($value))
					$this->validation_error = $key . " must be a readable file (" . $value . " not readable)";
				break;

			case "companyLogoUrL":
			case "companyWebsite":
			case "product_image_url": # product add/update/add_image/update_image
			case "video_url": # product add/update
			case "gallery_image_url":
				# http://phpcentral.com/208-url-validation-in-php.html
				if(!preg_match('|^(http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?)?$|i', $value))
					$this->validation_error = $key . " must be a well-formed URL";
				break;

			case "store_id": # global
			case "territory_id": # store countries
			case "country_id": # store states
			case "state_id": # store get/add shipping method
			case "department_id": # store get by dept
			case "category_id": # store get by sub-dept
			case "parent_category_id":
			case "f_price": # product add/update
			case "discount": # product add/update
			case "aggregate_quantity": # product add/update/add_option/update_option
			case "image_id":
				if(!is_numeric($value))
					$this->validation_error = $key . " must be numeric (" . var_export($value,true) . ")";
				break;
		}
	}
}