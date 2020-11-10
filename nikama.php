<?php
/*
    Plugin Name: Nikama Ordersystem
    Descripton: Ordersystem for products
    Version: 2.0
    Author: Niklas Borg & Amanda Jakobsson
*/

    defined('ABSPATH') or die ('You have entered nikamas secret code');

    // Load scripts
    require_once(plugin_dir_path(__FILE__). '/includes/order-scripts.php');
   
    //  Load Widget
    require_once(plugin_dir_path(__FILE__). '/includes/order-widget.php');

    //Load Admin Menu
    require_once(plugin_dir_path(__FILE__). '/includes/admin.php');

    //Load CPT
    require_once(plugin_dir_path(__FILE__). '/includes/cpt.php');

    //Load Meta Boxes
    require_once(plugin_dir_path(__FILE__). '/includes/meta_boxes.php');

   //Load Deactivation
    require_once(plugin_dir_path(__FILE__). '/includes/deactivation.php');
      register_deactivation_hook(__FILE__, 'deactivation');

    //Load Cart
    require_once(plugin_dir_path(__FILE__). '/includes/cart.php');

    //Load Order
    require_once(plugin_dir_path(__FILE__). '/includes/order.php');

    //Load SQL Tables
    require_once(plugin_dir_path(__FILE__). '/includes/tables.php');
        register_activation_hook(__FILE__, 'add_to_cart_register');
        register_activation_hook(__FILE__, 'order_register');
        register_activation_hook(__FILE__, 'order_post_register');