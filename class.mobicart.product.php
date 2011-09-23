<?php

class Mobicart_Product {
	function __construct($api) {
		$this->api = $api;

		$this->api->add_alias("get_store_products", array($this, "get_all"));
		$this->api->add_alias("get_department_products", array($this, "get_by_department"));
		$this->api->add_alias("get_subdepartment_products", array($this, "get_by_subdepartment"));
		$this->api->add_alias("get_product_details", array($this, "get"));
		$this->api->add_alias("delete_product", array($this, "delete"));
		$this->api->add_alias("update_product", array($this, "update"));
		$this->api->add_alias("add_product_option", array($this, "add_option"));
		$this->api->add_alias("update_product_option", array($this, "update_option"));
		$this->api->add_alias("add_product_image", array($this, "add_image"));
		$this->api->add_alias("update_product_image", array($this, "update_image"));
		$this->api->add_alias("product_bulk_upload", array($this, "bulk_upload"));
	}

	/**
	* Retrieves a list of all products in $store_id
	* http://www.mobi-cart.com/docs/api/storeProducts_API.html
	*/
	function get_all($username = false, $store_id = false) {
		$this->api->requires("user_name", "store_id")
					->method("get")
					->path("products")
					->add_url_segment("store-products.json", "method");

		if($username)
			$this->add_param("user_name", $username);
		if($store_id)
			$this->add_param("store_id", $store_id);

		return $this->api;
	}

	/**
	* Retrieves a list of all products in a department.
	* http://www.mobi-cart.com/docs/api/departmentProducts_API.html
	*/
	function get_by_department($username = false, $department_id = false) {
		$this->api->requires("user_name", "department_id")
					->method("get")
					->path("products", "products")
					->add_url_segment("department-products.json", "method");

		if($username)
			$this->api->add_param("user_name", $username);
		if($department_id)
			$this->api->add_param("department_id", $department_id);

		return $this->api;
	}

	/**
	* Retrieves a list of all products in a sub-department (category).
	* http://www.mobi-cart.com/docs/api/categoryProducts_API.html
	*/
	function get_by_subdepartment($username = false, $category_id = false) {
		$this->api->requires("user_name", "category_id")
					->method("get")
					->path("products", "products")
					->add_url_segment("category-products.json", "method");

		if($username)
			$this->api->add_param("user_name", $username);
		if($category_id)
			$this->api->add_param("category_id", $category_id);

		return $this->api;
	}

	/**
	* Retrieves information about a single $product_id
	* http://www.mobi-cart.com/docs/api/productDetails_API.html
	*/
	function get($username = false, $product_id = false) {
		$this->api->requires("user_name", "product_id")
					->method("get")
					->path("Product")
					->add_url_segment("product-details.json", "method");

		if($username)
			$this->api->add_param("user_name", $username);
		if($product_id)
			$this->api->add_param("product_id", $product_id);

		return $this->api;
	}

	/**
	* Deletes a product
	* http://www.mobi-cart.com/docs/api/deleteProduct_API.html
	*/
	function delete($username = false, $product_id = false) {
		$this->api->requires("user_name", "product_id")
					->method("DELETE")
					->path("message","message")
					->add_url_segment("delete-product.json", "method");

		if($username)
			$this->api->add_param("user_name", $username);
		if($product_id)
			$this->api->add_param("product_id", $product_id);

		return $this->api;
	}

	/**
	* Adds a product.
	* http://www.mobi-cart.com/docs/api/addProduct_API.html
	*/
	function add($user_name = false, $department_id = false, $category_id = false, $product_name = false,
				 $product_description = false, $status = false, $f_price = false, $discount = false, $product_image_url = false,
				 $sale_label = false, $video_url = false, $aggregate_quantity = false, $featured = false) {

		$fields = array("user_name", "department_id", "product_id", "product_name", "product_description", "status", "f_price",
					"discount", "product_image_url", "sale_label", "video_url", "aggregate_quantity", "featured");

		$this->api->requires("user_name", "department_id", "product_name", "f_price")
					->optional("category_id", "product_description", "status", "discount", "product_image_url", "sale_label", "video_url", "aggregate_quantity", "featured")
					->method("POST")
					->path("message")
					->add_url_segment("add-product.json", "method");

		// add the params as they come.
		foreach($fields as $v)
			if(isset($$v) && $$v !== FALSE)
				$this->api->add_param($v, $$v);

		return $this->api;
	}

	/**
	* Updates a product.
	* http://www.mobi-cart.com/docs/api/updateProduct_API.html
	*/
	function update($user_name = false, $product_id = false, $product_name = false, $product_description = false,
				 $status = false, $f_price = false, $discount = false, $product_image_url = false, $sale_label = false,
				 $video_url = false, $aggregate_quantity = false, $featured = false) {

		$fields = array("user_name", "product_id", "product_name", "product_description", "status", "f_price",
					"discount", "product_image_url", "sale_label", "video_url", "aggregate_quantity", "featured");

		$this->api->requires("user_name", "product_id", "product_name", "f_price")
					->optional("product_description", "status", "discount", "product_image_url", "sale_label", "video_url", "aggregate_quantity", "featured")
					->method("POST")
					->path("message")
					->add_url_segment("update-product.json", "method");

		// add the params as they come.
		foreach($fields as $v)
			if(isset($$v) && $$v !== FALSE)
				$this->api->add_param($v, $$v);

		return $this->api;
	}

	/**
	* Adds an option to a product.
	* http://www.mobi-cart.com/docs/api/addProductOption_API.html
	* http://support.mobi-cart.com/mobicart/topics/api_switching_to_per_option_quantity_management
	*/
	function add_option($user_name = false, $product_id = false, $sku_id = false, $option_title = false, $option_name = false, $qnt = false) {
		$this->api->requires("user_name", "product_id", "option_title", "option_name")
					->optional("sku_id", "option_quantity")
					->method("POST")
					->path("message")
					->add_url_segment("add-productOption.json", "method");

		if($user_name)
			$this->api->add_param("user_name", $user_name);
		if($product_id)
			$this->api->add_param("product_id", $product_id);
		if($sku_id)
			$this->api->add_param("sku_id", $sku_id);
		if($option_title)
			$this->api->add_param("option_title", $option_title);
		if($option_name)
			$this->api->add_param("option_name", $option_name);
		if($qnt)
			$this->api->add_param("option_quantity", $qnt);

		return $this->api;
	}

	/**
	* Updates a product option.
	* http://www.mobi-cart.com/docs/api/updateProductOption_API.html
	*/
	function update_option($user_name = false, $poid = false, $sku_id = false, $option_title = false, $option_name = false, $qnt = false) {
		$this->api->requires("user_name", "product_option_id", "option_title", "option_name")
					->optional("sku_id", "option_quantity")
					->method("POST")
					->path("message")
					->add_url_segment("update-productOption.json", "method");

		if($user_name)
			$this->api->add_param("user_name", $user_name);
		if($poid)
			$this->api->add_param("product_option_id", $poid);
		if($sku_id)
			$this->api->add_param("sku_id", $sku_id);
		if($option_title)
			$this->api->add_param("option_title", $option_title);
		if($option_name)
			$this->api->add_param("option_name", $option_name);
		if($qnt)
			$this->api->add_param("option_quantity", $qnt);

		return $this->api;
	}

	/**
	* Adds an image to a product from a URL
	* http://www.mobi-cart.com/docs/api/addProductImage_API.html
	*
	* Example: add_image(false, 14733, "http://static.php.net/www.php.net/images/php.gif");
	*/
	function add_image($user_name = false, $poid = false, $url = false) {
		$this->api->requires("user_name", "product_id", "product_image_url")
					->method("POST")
					->path("message")
					->add_url_segment("add-product-image.json", "method");

		if($user_name)
			$this->api->add_param("user_name", $user_name);
		if($poid)
			$this->api->add_param("product_id", $poid);
		if($url)
			$this->api->add_param("product_image_url", $url);

		return $this->api;
	}

	/**
	* Updates an existing product image from a URL
	* http://www.mobi-cart.com/docs/api/updateProductImages_API.html
	*
	* Example: update_image(false, 10881, "http://static.php.net/www.php.net/images/php.gif");
	*/
	function update_image($user_name = false, $piid = false, $url = false) {
		$this->api->requires("user_name", "product_image_id", "product_image_url")
					->method("POST")
					->path("message")
					->add_url_segment("update-product-image.json", "method");

		if($user_name)
			$this->api->add_param("user_name", $user_name);
		if($piid)
			$this->api->add_param("product_image_id", $piid);
		if($url)
			$this->api->add_param("product_image_url", $url);

		return $this->api;
	}

	/**
	* Allows upload of a CSV containing lots of product data
	* http://www.mobi-cart.com/docs/api/bulkUpload_API.html
	* Template: http://www.mobi-cart.com/api/store-product-template
	* Example: bulk_upload(false, false, realpath("products.csv"));
	*/
	function bulk_upload($user_name = false, $store_id = false, $file = false) {
		$this->api->requires("user_name", "store_id", "product_file")
					->method("POST")
					->path("message")
					->add_url_segment("products-csv-upload.json", "method");

		if($user_name)
			$this->api->add_param("user_name", $user_name);
		if($store_id)
			$this->api->add_param("store_id", $store_id);
		if($file)
			$this->api->add_param("product_file", "@" . $file);
	}
}