<?php

# 	@TODO
#	Add validation for gallery_image_url, image_id

class Mobicart_images {

	function Mobicart_images($api) {
		$this->api = $api;

		$this->api->add_alias("get_gallery_images", array($this, "get"));
		$this->api->add_alias("add_gallery_images", array($this, "add"));
		$this->api->add_alias("delete_gallery_images", array($this, "delete"));

	}

	/**
	* Retrieves images from a store.
	* http://www.mobi-cart.com/docs/api/galleryImagesUnderStore_API.html
	*/
	function get($username = false, $store_id = false) {
		$this->api->requires("user_name", "store_id")
					->method("get")
					->path("GalleryImageList", "images")
					->add_url_segment("store-gallery-images.json", "method");

		if($username)
			$this->api->add_param("user_name", $username);
		if($store_id)
			$this->api->add_param("store_id", $store_id);

		return $this->api;
	}

	/**
	* Adds an image to a gallery. Despite pluralisation, only image at a time.
	* http://www.mobi-cart.com/docs/api/addGalleryImagesUnderStore_API.html
	*/
	function add($username = false, $store_id = false, $url = false) {
		$this->api->requires("user_name", "store_id", "gallery_image_url")
					->method("post")
					->path("message", "message")
					->add_url_segment("add-gallery-image.json", "method");

		if($username)
			$this->api->add_param("user_name", $username);
		if($store_id)
			$this->api->add_param("store_id", $store_id);
		if($url)
			$this->api->add_param("gallery_image_url", $url);

		return $this->api;
	}

	/**
	* Deletes an image from a gallery.
	* http://www.mobi-cart.com/docs/api/deleteGalleryImage_API.html
	*/
	function delete($username = false, $image_id = false) {
		$this->api->requires("user_name", "image_id")
					->method("DELETE")
					->path("message", "message")
					->add_url_segment("delete-gallery-image.json", "method");

		if($username)
			$this->api->add_param("user_name", $username);
		if($image_id)
			$this->api->add_param("image_id", $image_id);

		return $this->api;
	}
}