<?php

require_once("class.mobicart.php");

$key = "INSERT YOUR KEY HERE";

$mobi = new Mobicart($key);

$mobi->add_param("user_name", "richthegeek@gmail.com", true);
$mobi->add_param("store_id", 4525, true);

// STORE (store)
//  Get Store              @get_store                   @store->get
//  Get Store Settings     @get_store_settings          @store->get_settings
//  Get Store Shipping     @get_store_shipping          @store->get_shipping
//  Get Store Tax          @get_store_tax               @store->get_tax
//  Get Countries          @get_countries               @store->get_countries
//  Get States             @get_states                  @store->get_states
//  Get Shipping Rate      @get_shipping_rate           @store->get_shipping_rate
//  Set Shipping Rate      @set_shipping_rate           @store->set_shipping_rate

// PRODUCT (product)
//  Get Store Products     @get_store_products          @product->get_all
//  Get Dept. Products     @get_department_products     @product->get_by_department
//  Get Sub-Dept. Products @get_subdepartment_products  @product->get_by_subdepartment
//  Get Product Details    @get_product_details         @product->get
//  Delete Product         @delete_product              @product->delete
//  Add product            @add_product                 @product->add
//  Update product         @update_product              @product->update
//  Add Product Options    @add_product_options         @product->add_option
//  Update Product Options @update_product_options      @product->update_option
//  Add Product Image      @add_product_image           @product->add_image
//  Update Product Image   @update_product_image        @product->update_image
//  Product Bulk Upload    @product_bulk_upload         @product->bulk_upload

// USER (user)
//  Get Merchant Profile    @get_merchant_profile       @user->get    2011-07-27: returns exception
//  Update Merchant Profile @update_merchant_profile    @user->update 2011-07-27: returns success but doesn't seem to affect profile

// IMAGES (images)
//  Get Gallery Images      @get_gallery_images         @images->get  2011-07-27: "File name is not a valid image" if no images uploaded
//  Add Gallery Images      @add_gallery_images         @images->add
//  Delete Gallery Images   @delete_gallery_images      @images->delete

// ORDER (order)
//  Add Order               @add_order                  @order->add
//  Get All Orders          @get_all_orders             @order->get_all
//  Get Order By Date       @get_order_by_date          @order->get_by_date
//  Get Order Details       @get_order_details          @order->get
//  Get Order History       @get_order_history          @order->get_history
//  Add Order Item          @add_order_item             @order->add_item    2011-07-27: seems to be broken
//  Update Order Item       @update_order_item          @order->update_item 2011-07-27: seems broken
//  Get Shipping Status     @get_shipping_status        @order->get_shipping_status     2011-07-27: requires tracking number to test
//  Add Shipping Status     @add_shipping_status        @order->add_shipping_status     2011-07-27: "error in accessing data"
//  Update Shipping Status  @update_shipping_status     @order->update_shipping_status  2011-07-27: requires tracking number to test

// DEPARTMENT (department, dept)
//  Get Department          @get_department             @dept->get
//  Add Department          @add_department             @dept->add
//  Update Department       @update_department          @dept->update   2011-07-27: reports "duplicate name" falsely
//  Delete Department       @delete_department          @dept->delete
//  Get Sub-department      @get_subdepartments         @dept->get_sub
//  Add Sub-department      @add_subdepartment          @dept->add_sub  2011-07-27: "sub-dept could not be added as dept has products"
//  Update Sub-department   @update_subdepartment       @dept->update_sub
//  Delete Sub-department   @delete_subdepartment       @dept->delete_sub 2011-07-27: requires products to be deleted first (expected behaviour)

// $mobi->dept->get();
// $mobi->dept->add(false, false, "Jeans", "active");
// $mobi->dept->update(false, 4053, "Jeans (hidden)", "hidden");
// $mobi->dept->delete(false, 4053);
// $mobi->dept->get_sub(false, 4040);
// $mobi->dept->add_sub(false, 4040, 3851, "Awesomes", "active");
// $mobi->dept->update_sub(false, 3851, 0, "Peoples", "hidden");
// $mobi->dept->delete_sub(false, 3851);

// $mobi->order->add(false, "php_penguin@hotmail.com", "Richard Lyon", "richthegeek@gmail.com", "01492513922",
//           "49 Portman Road", "Liverpool", "Merseyside", "L15 2HH", "United Kingdom",
//           "49 Portman Road", "Liverpool", "Merseyside", "L15 2HH", "United Kingdom",
//           "2011-07-27", "pending");
// $mobi->order->get_by_date(false, "2011-07-01", "2011-08-01");
// $mobi->order->get(false, 69);# not a joke, order ID was that
// $mobi->order->get_history();
// $mobi->order->update_item(false, 61, "14.99", 0);
// $mobi->order->get_shipping_status(false, ...)
// $mobi->order->add_shipping_status(false, 69, "GB-1234", "other", "Royal Mail", "pickedup");

// $mobi->product->bulk_upload(false, false, realpath("products.csv"));

// $mobi->store->get_states(false, 86);
// $mobi->store->get_settings(false, 12345);

print "<pre>";
if($result = $mobi->execute()) {
  print_r($result);
}
else
{
  print_r($mobi->last_request);
}