<?php

defined('ABSPATH') or die ('You have entered nikamas secret code');    

    // Tables
    function add_to_cart_register(){
        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();
        $table_name      = $wpdb->prefix . "add_to_cart";
        $user_table      = $wpdb->prefix . "users";
        $post_table      = $wpdb->prefix . "posts";

        $sql = "CREATE TABLE $table_name (
                id BIGINT(20) NOT NULL AUTO_INCREMENT,
                user_id BIGINT(20) UNSIGNED NOT NULL,
                post_id BIGINT(20) UNSIGNED NOT NULL,
                PRIMARY KEY  (id),
                FOREIGN KEY (user_id) REFERENCES $user_table(ID),
                FOREIGN KEY (post_id) REFERENCES $post_table(ID)
        ) $charset_collate;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
    }
    
    function order_register(){
        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();
        $table_name      = $wpdb->prefix . "order";
        $user_table      = $wpdb->prefix . "users";

        $sql = "CREATE TABLE $table_name (
                id BIGINT(20) NOT NULL AUTO_INCREMENT,
                user_id BIGINT(20) UNSIGNED NOT NULL,
                order_date DATETIME,
                order_status VARCHAR(20),
                PRIMARY KEY  (id),
                FOREIGN KEY (user_id) REFERENCES $user_table(ID)
        ) $charset_collate;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
    }

    function order_post_register() {
        global $wpdb;

        $charset_collate   = $wpdb->get_charset_collate();
        $table_name        = $wpdb->prefix . "order_post";
        $posts_table       = $wpdb->prefix . "posts";
        $order_table       = $wpdb->prefix . "order";

        $sql = "CREATE TABLE $table_name (
            id BIGINT(20) NOT NULL AUTO_INCREMENT,
            order_id BIGINT(20) NOT NULL,
            post_id BIGINT(20) UNSIGNED NOT NULL,
            PRIMARY KEY (id),
            FOREIGN KEY (post_id) REFERENCES $posts_table(ID),
            FOREIGN KEY (order_id) REFERENCES $order_table(id)

        ) $charset_collate;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
    }