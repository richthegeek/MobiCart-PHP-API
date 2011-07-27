<?php

class Mobicart_department {

	function Mobicart_department($api) {
		$this->api = $api;

		$this->api->add_alias("get_department", array($this, "get"));
		$this->api->add_alias("add_department", array($this, "add"));
		$this->api->add_alias("update_department", array($this, "update"));
		$this->api->add_alias("delete_department", array($this, "delete"));
		$this->api->add_alias("get_subdepartments", array($this, "get_subs"));
		$this->api->add_alias("add_subdepartment", array($this, "add_sub"));
		$this->api->add_alias("update_subdepartment", array($this, "update_sub"));
		$this->api->add_alias("delete_subdepartment", array($this, "delete_sub"));
	}

	/**
	* Lists all departments in a store.
	* http://www.mobi-cart.com/docs/api/departmentsForStore_API.html
	*/
	function get($user_name = false, $store_id = false) {
		$this->api->requires("user_name", "store_id")
				->method("get")
				->path("DepartmentList", "departments")
				->add_url_segment("store-departments.json", "method");

		if($user_name)
			$this->api->add_param("user_name", $user_name);
		if($store_id)
			$this->api->add_param("store_id", $store_id);

		return $this->api;
	}

	/**
	* Add a top-level department to a store
	* http://www.mobi-cart.com/docs/api/addDepartmentUnderStore_API.html
	*/
	function add($user_name = false, $store_id = false, $name = false, $status = false) {
		$this->api->requires("user_name", "store_id", "department_name")
				->optional("department_status")
				->method("post")
				->path("message")
				->add_url_segment("add-department.json", "method");

		if($user_name)
			$this->api->add_param("user_name", $user_name);
		if($store_id)
			$this->api->add_param("store_id", $store_id);
		if($name)
			$this->api->add_param("department_name", $name);
		if($status)
			$this->api->add_param("department_status", $status);

		return $this->api;
	}

	/**
	* Updates a top-level department $dept_id
	* http://www.mobi-cart.com/docs/api/updateDepartmentUnderStore_API.html
	*/
	function update($user_name = false, $dept_id = false, $name = false, $status = false) {
		$this->api->requires("user_name", "department_id", "department_name")
				->optional("department_status")
				->method("post")
				->path("message", "message")
				->add_url_segment("update-department.json", "method");

		if($user_name)
			$this->api->add_param("user_name", $user_name);
		if($dept_id)
			$this->api->add_param("department_id", $dept_id);
		if($name)
			$this->api->add_param("department_name", $name);
		if($status)
			$this->api->add_param("department_status", $status);

		return $this->api;
	}

	/**
	* Deletes a department $dept_id
	* http://www.mobi-cart.com/docs/api/deleteDepartmentUnderStore_API.html
	*/
	function delete($user_name = false, $dept_id = false) {
		$this->api->requires("user_name", "department_id")
				->method("delete")
				->path("message", "message")
				->add_url_segment("delete-department.json", "method");

		if($user_name)
			$this->api->add_param("user_name", $user_name);
		if($dept_id)
			$this->api->add_param("department_id", $dept_id);

		return $this->api;
	}


	/**
	* Lists all sub-categories of a department
	* http://www.mobi-cart.com/docs/api/subDepartments_API.html
	*/
	function get_sub($user_name = false, $dept_id = false) {
		$this->api->requires("user_name", "department_id")
				->method("get")
				->path("CategoryList")
				->add_url_segment("sub-departments.json", "method");

		if($user_name)
			$this->api->add_param("user_name", $user_name);
		if($dept_id)
			$this->api->add_param("department_id", $dept_id);

		return $this->api;
	}

	/**
	* Add a sub-category to a department and optionally a parent category
	* http://www.mobi-cart.com/docs/api/addSubDepartmentUnderSubDepartment_API.html
	*/
	function add_sub($user_name = false, $dept_id = false, $pcid = false, $name = false, $status = false) {
		$this->api->requires("user_name", "department_id", "parent_category_id", "category_name", "category_status")
				->method("post")
				->path("message")
				->add_url_segment("add-sub-department-nested.json", "method");

		if($user_name)
			$this->api->add_param("user_name", $user_name);
		if($dept_id)
			$this->api->add_param("department_id", $dept_id);
		if($pcid !== FALSE)
			$this->api->add_param("parent_category_id", $pcid);
		if($name)
			$this->api->add_param("category_name", $name);
		if($status)
			$this->api->add_param("category_status", $status);

		return $this->api;
	}

	/**
	* Updates a sub-category
	* http://www.mobi-cart.com/docs/api/updateSubDepartmentUnderSubDepartment_API.html
	*/
	function update_sub($user_name = false, $cid = false, $pcid = false, $name = false, $status = false) {
		$this->api->requires("user_name", "category_id", "parent_category_id",  "category_name")
				->optional("category_status")
				->method("post")
				->path("message", "message")
				->add_url_segment("update-sub-department-nested.json", "method");

		if($user_name)
			$this->api->add_param("user_name", $user_name);
		if($cid)
			$this->api->add_param("category_id", $cid);
		if($pcid !== FALSE)
			$this->api->add_param("parent_category_id", $pcid);
		if($name)
			$this->api->add_param("category_name", $name);
		if($status)
			$this->api->add_param("category_status", $status);

		return $this->api;
	}

	/**
	* Deletes a sub-category $cid
	* http://www.mobi-cart.com/docs/api/deleteSubDepartment_API.html
	*/
	function delete_sub($user_name = false, $cid = false) {
		$this->api->requires("user_name", "category_id")
				->method("delete")
				->path("message", "message")
				->add_url_segment("delete-sub-department.json", "method");

		if($user_name)
			$this->api->add_param("user_name", $user_name);
		if($cid)
			$this->api->add_param("category_id", $cid);

		return $this->api;
	}

}