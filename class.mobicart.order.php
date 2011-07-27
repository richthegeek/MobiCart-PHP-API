<?php

class Mobicart_order {

	function Mobicart_order($api) {
		$this->api = $api;

		$this->api->add_alias("add_order", array($this, "add"));

		$this->api->add_alias("get_all_orders", array($this, "get_all"));
		$this->api->add_alias("get_order_by_date", array($this, "get_by_date"));

		$this->api->add_alias("add_order_item", array($this, "add_item"));
		$this->api->add_alias("update_order_item", array($this, "update_item"));

		$this->api->add_alias("get_order_details", array($this, "get"));
		$this->api->add_alias("get_order_history", array($this, "get_history"));

		$this->api->add_alias("get_shipping_status", array($this,"get_shipping_status"));
		$this->api->add_alias("add_shipping_status", array($this, "add_shipping_status"));
		$this->api->add_alias("update_shipping_status", array($this, "update_shipping_status"));
	}

	/**
	* Create an order
	* http://www.mobi-cart.com/docs/api/addProductOrder_API.html
	*/
	function add($user_name = false, $s_merchant_paypal_email = false, $s_buyer_name = false, $s_buyer_email = false, $i_buyer_phone = false,
				$s_shipping_street = false, $s_shipping_city = false, $s_shipping_state = false, $s_shipping_postal_code = false, $s_shipping_country = false,
				$s_billing_street = false, $s_billing_city = false, $s_billing_state = false, $s_billing_postal_code = false, $s_billing_country = false,
				$d_order_date = false, $s_status = false) {

		$reqd = array("user_name", "s_merchant_paypal_email", "s_buyer_name", "s_buyer_email", "i_buyer_phone", "s_shipping_street", "s_shipping_city",
					"s_shipping_state","s_shipping_postal_code", "s_shipping_country", "s_billing_street", "s_billing_city", "s_billing_state",
					"s_billing_postal_code", "s_billing_country", "d_order_date", "s_status");

		call_user_func_array(array($this->api, "requires"), $reqd);

		$this->api->method("POST")->path("message", "message")->add_url_segment("add-product-order.json", "method");

		foreach($reqd as $key)
			if($$key)
				$this->api->add_param($key, $$key);

		return $this->api;
	}

	/**
	* Retrieves a list of all orders in a $store_id
	* http://www.mobi-cart.com/docs/api/allOrders_API.html
	*/
	function get_all($user_name = false) {
		$this->api->requires("user_name")
				->method("get")
				->path("OrderList", "orders")
				->add_url_segment("all-orders.json", "method");

		if($user_name)
			$this->api->add_param("user_name", $user_name);

		return $this->api;
	}

	/**
	* Retrieves details of a specific $order_id
	* http://www.mobi-cart.com/docs/api/orderDetails_API.html (shows store_id, is wrong)
	*/
	function get($user_name = false, $order_id = false) {
		$this->api->requires("user_name", "order_id")
				->method("get")
				->path("order-details")
				->add_url_segment("order-details.json", "method");

		if($user_name)
			$this->api->add_param("user_name", $user_name);
		if($order_id)
			$this->api->add_param("order_id", $order_id);

		return $this->api;
	}

	/**
	* Retrieves all orders within a given date range
	* http://www.mobi-cart.com/docs/api/ordersByDate_API.html
	*/
	function get_by_date($user_name = false, $from_date = false, $to_date = false) {
		$this->api->requires("user_name", "from_date", "to_date")
				->method("get")
				->path("OrderList", "orders")
				->add_url_segment("orders-by-date.json", "method");

		if($user_name)
			$this->api->add_param("user_name", $user_name);
		if($from_date)
			$this->api->add_param("from_date", $from_date);
		if($to_date)
			$this->api->add_param("to_date", $to_date);

		return $this->api;
	}

	/**
	* Retrieves a list of the hisory of ALL orders, not a single order
	* http://www.mobi-cart.com/docs/api/orderHistory_API.html
	*/
	function get_history($user_name = false) {
		$this->api->requires("user_name")
				->method("get")
				->path("OrderList", "orders")
				->add_url_segment("order-history.json", "method");

		if($user_name)
			$this->api->add_param("user_name", $user_name);

		return $this->api;
	}

	/**
	* Adds a product $product_id to an order $order_id, with product option $poid, $amount, $qnt
	* http://www.mobi-cart.com/docs/api/addOrderItem_API.html
	*/
	function add_item($user_name = false, $product_id = false, $order_id = false, $poid = false, $amount = false, $qnt = false) {
		$this->api->requires("user_name", "product_id", "order_id", "amount", "quantity")
				->optional("product_option_id")
				->method("POST")
				->path("message", "message")
				->add_url_segment("add-OrderItem.json", "method");

		if($user_name)
			$this->api->add_param("user_name", $user_name);
		if($product_id)
			$this->api->add_param("product_id", $product_id);
		if($order_id)
			$this->api->add_param("order_id", $order_id);
		if($poid !== false)
			$this->api->add_param("product_option_id", $poid);
		if($amount)
			$this->api->add_param("amount", $amount);
		if($qnt)
			$this->api->add_param("quantity", $qnt);

		return $this->api;
	}

	/**
	* Updates the detail of an order line item $ooid with $amount and $qnt.
	* http://www.mobi-cart.com/docs/api/updateOrderItem_API.html
	*/
	function update_item($user_name = false, $ooid = false, $amount = false, $qnt = false) {
		$this->api->requires("user_name", "order_item_id", "amount", "quantity")
				->method("POST")
				->path("message", "message")
				->add_url_segment("update-OrderItem.json", "method");

		if($user_name)
			$this->api->add_param("user_name", $user_name);
		if($ooid)
			$this->api->add_param("order_item_id", $ooid);
		if($amount)
			$this->api->add_param("amount", $amount);
		if($qnt !== false)
			$this->api->add_param("quantity", $qnt);

		return $this->api;
	}

	/**
	* Retrieves the shipping status of a tracking number
	* http://www.mobi-cart.com/docs/api/getShippingStatus_API.html
	*/
	function get_shipping_status($user_name = false, $tracking = false) {
		$this->api->requires("user_name", "tracking_number")
				->method("get")
				->path("OrderDetails")
				->add_url_segment("shipping-status.json", "method");

		if($user_name)
			$this->api->add_param("user_name", $user_name);
		if($tracking)
			$this->api->add_param("tracking_number", $tracking);

		return $this->api;
	}

	/**
	* Adds tracking information to an order
	* http://www.mobi-cart.com/docs/api/addShippingStatus_API.html
	*/
	function add_shipping_status($user_name = false, $oid = false, $number = false, $carrier = false, $remarks = false, $status = false) {
		$this->api->requires("user_name", "order_id", "tracking_number", "shipping_carrier", "shipping_status")
				->optional("shipping_remarks")
				->method("POST")
				->path("message","message")
				->add_url_segment("add-shipping-status.json", "method");

		if($user_name)
			$this->api->add_param("user_name", $user_name);
		if($oid)
			$this->api->add_param("order_id", $oid);
		if($number)
			$this->api->add_param("tracking_number", $number);
		if($carrier)
			$this->api->add_param("shipping_carrier", $carrier);
		if($remarks)
			$this->api->add_param("shipping_remarks", $remarks);
		if($status)
			$this->api->add_param("shipping_status", $status);

		return $this->api;
	}

	/**
	* Updates shipping information related to a tracking number
	* http://www.mobi-cart.com/docs/api/updateShippingStatus_API.html
	*/
	function update_shipping_status($user_name = false, $number = false, $status = false, $remarks = false) {
		$this->api->requires("user_name", "tracking_number", "shipping_status")
				->optional("shipping_remarks") /* API says this is mandatory, but it's not in Add-shipping-status? */
				->method("POST")
				->path("message","message")
				->add_url_segment("update-shipping-status.json", "method");

		if($user_name)
			$this->api->add_param("user_name", $user_name);
		if($number)
			$this->api->add_param("tracking_number", $number);
		if($status)
			$this->api->add_param("shipping_status", $status);
		if($remarks)
			$this->api->add_param("shipping_remarks", $remarks);

		return $this->api;
	}
}
