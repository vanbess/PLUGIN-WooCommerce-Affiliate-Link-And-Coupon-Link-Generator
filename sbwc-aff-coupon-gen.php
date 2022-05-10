<?php

/**
 * Plugin Name: SBWC Aff Link and Coupon Generator
 * Author: WC Bessinger
 * Version: 1.0.1
 * Description: Allows for generation of unique affiliate link and generation of unique one time use coupon when link is visited
 */
if (!defined('ABSPATH')) :
    exit();
endif;

define('SBWCAFF_PATH', plugin_dir_path(__FILE__));
define('SBWCAFF_URL', plugin_dir_url(__FILE__));

add_action('plugins_loaded', 'sbwcaff_init');

function sbwcaff_init()
{
    //scripts front
    add_action('wp_enqueue_scripts', 'sbwcaff_front');
    function sbwcaff_front()
    {
        wp_enqueue_style('sbwcaff-front', SBWCAFF_URL . 'assets/front.css');
        wp_enqueue_script('sbwcaff-front', SBWCAFF_URL . 'assets/front.js', ['jquery']);
    }

    //scripts back
    add_action('admin_enqueue_scripts', 'sbwcaff_back');
    function sbwcaff_back()
    {
        wp_enqueue_style('sbwcaff-back', SBWCAFF_URL . 'assets/back.css');
        wp_enqueue_script('sbwcaff-back', SBWCAFF_URL . 'assets/back.js', ['jquery']);
    }

    //functions
    include SBWCAFF_PATH . 'functions/cpt.php';
    include SBWCAFF_PATH . 'functions/admin-page.php';
    include SBWCAFF_PATH . 'functions/create-coupon.php';
}
