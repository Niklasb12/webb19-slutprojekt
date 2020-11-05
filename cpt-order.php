<?php
/*
    Plugin Name: Products
    Descripton: Ordersystem for products
    Version: 2.0
    Author: Niklas Borg & Amanda Jakobsson
*/

    // Query
    function input_cart() {
        global $wpdb;
        if(isset($_POST['add_to_cart'])){
            $post_id = $_POST['id'];
            $user_id = get_current_user_id();

            $wpdb->query("INSERT INTO wp_add_to_cart(user_id, post_id) VALUES ($user_id, $post_id)");
        }
    }

    function input_order() {
        global $wpdb;
        if(isset($_POST['order'])){
            
            $user_id = get_current_user_id();
            
            $wpdb->query("INSERT INTO wp_order(user_id, order_date, order_status) VALUES ($user_id, NOW(), 'recieved')");

            $results = $wpdb->get_results("SELECT * FROM wp_add_to_cart WHERE wp_add_to_cart.user_id=$user_id");

            $results_order = $wpdb->get_results("SELECT wp_order.id FROM wp_order ORDER BY wp_order.order_date DESC LIMIT 1");
            
            foreach($results_order as $order){
                $order_id = $order->id;
            }

            foreach($results as $result) {
                $wpdb->query("INSERT INTO wp_order_post(add_to_cart_id, order_id) VALUES ($result->id, $order_id)");
            }

            
            // $wpdb->query("DELETE FROM wp_add_to_cart WHERE wp_add_to_cart.user_id=$user_id");
            
            
        }
    }

    function add_to_cart($content){
        $id = get_the_ID();
        if(in_the_loop() && is_main_query() ){
                return $content . "
                <form method=POST>
                    <input name=id type=hidden value=$id>
                    <button name=add_to_cart>Add to cart</button>
                </form>
                ";
        }
        return $content;
    }

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
                FOREIGN KEY (user_id) REFERENCES $user_table(ID) ON DELETE CASCADE,
                FOREIGN KEY (post_id) REFERENCES $post_table(ID) ON DELETE CASCADE
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
                FOREIGN KEY (user_id) REFERENCES $user_table(ID) ON DELETE CASCADE
        ) $charset_collate;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
    }

    function order_post_register() {
        global $wpdb;

        $charset_collate   = $wpdb->get_charset_collate();
        $table_name        = $wpdb->prefix . "order_post";
        $add_to_cart_table = $wpdb->prefix . "add_to_cart";
        $order_table       = $wpdb->prefix . "order";

        $sql = "CREATE TABLE $table_name (
            id BIGINT(20) NOT NULL AUTO_INCREMENT,
            add_to_cart_id BIGINT(20) NOT NULL,
            order_id BIGINT(20) NOT NULL,
            PRIMARY KEY (id),
            FOREIGN KEY (add_to_cart_id) REFERENCES $add_to_cart_table(id) ON DELETE CASCADE,
            FOREIGN KEY (order_id) REFERENCES $order_table(id) ON DELETE CASCADE

        ) $charset_collate;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
    }

    // Register Widget
    function register_order_widget(){
        register_widget('ow_widget');
    }

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


    // Load scripts
    require_once(plugin_dir_path(__FILE__). '/includes/order-scripts.php');
   
    //  Load Widget
    require_once(plugin_dir_path(__FILE__). '/includes/order-widget.php');

    //Load Sidebar
    require_once(plugin_dir_path(__FILE__). '/includes/sidebar.php');

    //Load Admin Menu
    require_once(plugin_dir_path(__FILE__). '/includes/admin.php');

    //Load CPT
    require_once(plugin_dir_path(__FILE__). '/includes/cpt.php');

    //Load Meta Boxes
    require_once(plugin_dir_path(__FILE__). '/includes/meta_boxes.php');

    //Load Deactivation
    // require_once(plugin_dir_path(__FILE__). '/includes/deactivation.php');
    
    add_filter('the_content', 'add_to_cart');
  
    add_action('init', 'input_cart');
    add_action('init', 'input_order');
    add_action('widgets_init', 'register_order_widget');

    register_activation_hook(__FILE__, 'add_to_cart_register');
    register_activation_hook(__FILE__, 'order_register');
    register_activation_hook(__FILE__, 'order_post_register');
    register_deactivation_hook(__FILE__, 'deactivation');
    


  
  