<?php

/******************************************/
/**CPT TO KEEP RECORD OF GENERATED LINKS**/
/*****************************************/
function cptui_register_my_cpts_coupon_link() {

	/**
	 * Post Type: Coupon Links.
	 */

	$labels = [
		"name"                     => __( "Coupon Links", "woocommerce" ),
		"singular_name"            => __( "Coupon Link", "woocommerce" ),
		"menu_name"                => __( "Coupon Links", "woocommerce" ),
		"all_items"                => __( "All Coupon Links", "woocommerce" ),
		"add_new"                  => __( "Add new", "woocommerce" ),
		"add_new_item"             => __( "Add new Coupon Link", "woocommerce" ),
		"edit_item"                => __( "Edit Coupon Link", "woocommerce" ),
		"new_item"                 => __( "New Coupon Link", "woocommerce" ),
		"view_item"                => __( "View Coupon Link", "woocommerce" ),
		"view_items"               => __( "View Coupon Links", "woocommerce" ),
		"search_items"             => __( "Search Coupon Links", "woocommerce" ),
		"not_found"                => __( "No Coupon Links found", "woocommerce" ),
		"not_found_in_trash"       => __( "No Coupon Links found in trash", "woocommerce" ),
		"parent"                   => __( "Parent Coupon Link:", "woocommerce" ),
		"featured_image"           => __( "Featured image for this Coupon Link", "woocommerce" ),
		"set_featured_image"       => __( "Set featured image for this Coupon Link", "woocommerce" ),
		"remove_featured_image"    => __( "Remove featured image for this Coupon Link", "woocommerce" ),
		"use_featured_image"       => __( "Use as featured image for this Coupon Link", "woocommerce" ),
		"archives"                 => __( "Coupon Link archives", "woocommerce" ),
		"insert_into_item"         => __( "Insert into Coupon Link", "woocommerce" ),
		"uploaded_to_this_item"    => __( "Upload to this Coupon Link", "woocommerce" ),
		"filter_items_list"        => __( "Filter Coupon Links list", "woocommerce" ),
		"items_list_navigation"    => __( "Coupon Links list navigation", "woocommerce" ),
		"items_list"               => __( "Coupon Links list", "woocommerce" ),
		"attributes"               => __( "Coupon Links attributes", "woocommerce" ),
		"name_admin_bar"           => __( "Coupon Link", "woocommerce" ),
		"item_published"           => __( "Coupon Link published", "woocommerce" ),
		"item_published_privately" => __( "Coupon Link published privately.", "woocommerce" ),
		"item_reverted_to_draft"   => __( "Coupon Link reverted to draft.", "woocommerce" ),
		"item_scheduled"           => __( "Coupon Link scheduled", "woocommerce" ),
		"item_updated"             => __( "Coupon Link updated.", "woocommerce" ),
		"parent_item_colon"        => __( "Parent Coupon Link:", "woocommerce" ),
	];

	$args = [
		"label"                 => __( "Coupon Links", "woocommerce" ),
		"labels"                => $labels,
		"description"           => "CPT to capture generated coupon links",
		"public"                => true,
		"publicly_queryable"    => false,
		"show_ui"               => true,
		"show_in_rest"          => false,
		"rest_base"             => "",
		"rest_controller_class" => "WP_REST_Posts_Controller",
		"has_archive"           => false,
		"show_in_menu"          => true,
		"show_in_nav_menus"     => false,
		"delete_with_user"      => false,
		"exclude_from_search"   => false,
		"capability_type"       => "post",
		"map_meta_cap"          => true,
		"hierarchical"          => false,
		"rewrite"               => [ "slug" => "coupon_link", "with_front" => true ],
		"query_var"             => true,
		"menu_icon"             => "dashicons-shortcode",
		"supports"              => [ "title" ],
	];

	register_post_type( "coupon_link", $args );
}

add_action( 'init', 'cptui_register_my_cpts_coupon_link' );