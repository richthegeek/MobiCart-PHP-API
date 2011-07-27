<?php

class Request {

	var $base_url;
	var $constant = array();
	var $instance = array();

	var $last_request = false;

	function Request($base_url) {
		$this->reset(true);
		$this->base_url = $base_url;
	}

	/**
	* Method as in GET/POST/PUT/DELETE
	*/
	function method($method) {
		$this->method = strtoupper($method);
		return $this;
	}

	/**
	* Switches to HTTPS mode
	*/
	function secure($secure = true) {
		$this->secure = (boolean) $secure;
		return $this;
	}

	/**
	* The path within the data that contains the desired data
	*/
	function path() {
		$this->path = func_get_args();
		return $this;
	}

	/**
	* Adds a (key,value) pair to $type of @constant
	* Does not allow null key for $type=param
	*/
	function add_constant($type, $key, $value) {
		if($key)
			$this->constant[$type][$key] = $value;
		else if($type == "url")
			$this->constant[$type][] = $value;

		return $this;
	}

	/**
	* Adds a (key,value) pair to $type of @instance
	* Does not allow null key for $type=param
	*/
	function add_instance($type, $key, $value) {
		if($key)
			$this->instance[$type][$key] = $value;
		else if($type == "url")
			$this->instance[$type][$key] = $value;

		return $this;
	}

	/**
	* Adds a segment to the URL of @constant or @instance
	*/
	function add_url_segment($segment, $id = false, $constant = false) {
		if($constant)
			$this->add_constant("url", $id, $segment);
		else
			$this->add_instance("url", $id, $segment);

		return $this;
	}

	/**
	* Adds a (key,value) pair to the param of @constant or @instance
	*/
	function add_param($key, $value, $constant = false) {
		if($constant)
			$this->add_constant("param", $key, $value);
		else
			$this->add_instance("param", $key, $value);

		return $this;
	}

	/**
	* Removes any instance of key from @constant and/or @instance.
	*
	* To remove from all, set to 0.
	* To remove from only @instance set to -1
	* To remove from only @constant set to  1
	*/
	function remove($key, $constant = 0) {
		if($constant <= 0)
			if(isset($this->instance['url'][$key]))
				unset($this->instance['url'][$key]);
			else if(isset($this->instance['param'][$key]))
				unset($this->instance['param'][$key]);

		if($constant >= 0)
			if(isset($this->constant['url'][$key]))
				unset($this->constant['url'][$key]);
			else if(isset($this->constant['param'][$key]))
				unset($this->constant['param'][$key]);

		return $this;
	}

	/**
	* Resets data to the initial state.
	* Set $constant = true to clear constants also
	*/
	function reset($constant = false) {
		if($constant)
			$this->constant = array("url" => array(), "param" => array());

		$this->instance = array("url" => array(), "param" => array());

		$this->method = "GET";

		$this->requires = array();
		$this->optional = array();

		$this->path = array();

		$this->secure = false;

		return $this;
	}

	/**
	* Checks wether this has a $key of $type
	* If type = false, checks both param and URL
	*/
	function has($type = false, $key) {
		if(!$type)
			return ($this->has("param", $key) || $this->has("url", $key));

		return (isset($this->constant[$type][$key]) || isset($this->instance[$type][$key]));
	}

	/**
	* Returns the value of $key in $type
	* If type = false, returns $key in param or URL
	*/
	function get($type = false, $key) {
		if(!$type)
			if($v = $this->get("param", $key))
				return $v;
			else
				return $this->get("url", $key);

		if(isset($this->constant[$type][$key]))
			return $this->constant[$type][$key];

		if(isset($this->instance[$type][$key]))
			return $this->instance[$type][$key];

		return false;
	}

	/**
	* Validates required data and returns a well-formed URL
	*/
	function get_url() {
		if(!$this->validate()) {
			throw new Exception($this->validation_error);
		}

		$protocol = $this->secure ? "https://" : "http://";

		$c_url = count($this->constant['url']) ? '/' . implode("/", $this->constant['url']) : '';
		$i_url = count($this->instance['url']) ? '/' . implode("/", $this->instance['url']) : '';

		return $protocol . $this->base_url . $c_url . $i_url;
	}

	function get_params($string = false) {
		$c_param = $this->constant['param'];
		$i_param = $this->instance['param'];
		$a_params = array_merge($c_param, $i_param);
		$params = array();

		// always requires an API key
		if(!in_array("api_key", $this->requires))
			array_unshift($this->requires, "api_key");

		foreach(array_merge($this->requires, $this->optional) as $key) {
			if(!isset($a_params[$key]))
				continue;

			if($string)
				$params[$key] = $key . "=" . urlencode($a_params[$key]);
			else
				$params[$key] = $a_params[$key];
		}

		if($string)
			$params = "?" . implode("&", $params);

		return $params;
	}

	/**
	* Execute the request.
	*/
	function execute($reset = true) {
		$ch = curl_init($this->get_url());

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);

		$params = $this->get_params();

		switch ($this->method) {
			case "POST":
				curl_setopt($ch, CURLOPT_POST, TRUE);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
				break;
			case "PUT":
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
				curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
				curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-length: ' . strlen($this->get_params(true))));
				break;
			case "DELETE":
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
				curl_setopt($ch, CURLOPT_URL, $this->get_url() . $this->get_params(true));
				break;
			case "GET":
			default:
				curl_setopt($ch, CURLOPT_URL, $this->get_url() . $this->get_params(true));
				break;
		}

		$this->last_request = new stdClass;
		$this->last_request->url = $this->get_url();
		$this->last_request->params = $this->get_params();
		$this->last_request->error = false;

		$this->last_request->raw = curl_exec($ch);

		$this->last_request->status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$this->last_request->content_type = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
		$this->last_request->content_length = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);

		// clear all params
		$path = $this->path;
		if($reset)
			$this->reset();

		if($json = json_decode($this->last_request->raw)) {
			// almost all requests here

			if(isset($json->error)) {
				$this->last_request->error = $json->error->message;
				$this->last_request->error_code = $json->error->errorcode;
				return false;
			}

			$this->last_request->json =& $json;

			foreach($path as $v) {
				if((is_array($json) && !isset($json[$v])) || (is_object($json) && !isset($json->$v))) {
					$this->last_request->error = "Data returned did not match expected path data";
					return false;
				}

				$json = is_array($json) ? $json[$v] : $json->$v;
			}


			return $json;
		}
		else {
			// PUT, DELETE = this
			$this->last_request->error = "Data returned was not valid JSON: " . json_last_error();
			return false;
		}
	}


}