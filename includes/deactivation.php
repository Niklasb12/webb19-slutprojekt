<?php

    defined('ABSPATH') or die ('You have entered nikamas secret code');

// Deactivate
    function deactivation() {
        global $wpdb;
        $table_name            = $wpdb->prefix . "add_to_cart";
        $table_name_order      = $wpdb->prefix . "order";
        $table_name_order_post = $wpdb->prefix . "order_post";
        $wpdb->query("DROP TABLE IF EXISTS $table_name_order_post");
        $wpdb->query("DROP TABLE IF EXISTS $table_name");
        $wpdb->query("DROP TABLE IF EXISTS $table_name_order"); 
    }
    