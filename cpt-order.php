<?php
/*
    Plugin Name: Products
    Descripton: Ordersystem for products
    Version: 2.0
    Author: Niklas Borg & Amanda Jakobsson
*/

    function cpt_order() {
        register_post_type(
            'cpt_order', array(
                'labels'            => array(
                    'name'          => 'Orders',
                    'singular_name' => 'Order'
                ),
                'public'            => true,
                'has_archive'       => true,
                'supports'          => array('title', 'editor', 'thumbnail', 'author', 'excerpt')
            )
        );  
    }

    function save_products($product_id){
        if(!empty($_POST['price'])){
            if($_POST['price'] < 0) {
                update_post_meta($product_id, 'price', 300);
            }elseif(!is_numeric($_POST['price'])){
                update_post_meta($product_id, 'price', 300);
            }else{
                update_post_meta($product_id, 'price', $_POST['price']);
            }

        }else{
            update_post_meta($product_id, 'price', 300);
        }
    }

    function post_meta_box_price($product){
        printf('<input type="text" name="price" value="%s">',
        get_post_meta($product->ID, 'price', true )
        );
    }

    function adding_the_meta_boxes(){
        add_meta_box(
            "product_price",
            "Price",
            "post_meta_box_price",
            "cpt_order"
        );
    }

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

            $results = $wpdb->get_results("SELECT * FROM wp_add_to_cart WHERE wp_add_to_cart.user_id=$user_id");

            foreach($results as $result){
                $wpdb->query("INSERT INTO wp_order(user_id, post_id, order_date, order_status) VALUES ($result->user_id, $result->post_id, NOW(), 'recieved')");
            } 
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


    function add_to_cart_register(){
        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();
        $table_name = $wpdb->prefix . "add_to_cart";
        $user_table = $wpdb->prefix . "users";
        $post_table = $wpdb->prefix . "posts";

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
        $table_name = $wpdb->prefix . "order";
        $user_table = $wpdb->prefix . "users";
        $post_table = $wpdb->prefix . "posts";

        $sql = "CREATE TABLE $table_name (
                id BIGINT(20) NOT NULL AUTO_INCREMENT,
                user_id BIGINT(20) UNSIGNED NOT NULL,
                post_id BIGINT(20) UNSIGNED NOT NULL,
                order_date DATETIME,
                order_status VARCHAR(20),
                PRIMARY KEY  (id),
                FOREIGN KEY (user_id) REFERENCES $user_table(ID),
                FOREIGN KEY (post_id) REFERENCES $post_table(ID)
        ) $charset_collate;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
    }



    function deactivation() {
        global $wpdb;
        $table_name = $wpdb->prefix . "add_to_cart";
        $table_name_order = $wpdb->prefix . "order";
        $wpdb->query("DROP TABLE IF EXISTS $table_name");
        $wpdb->query("DROP TABLE IF EXISTS $table_name_order");
    }

      // Load scripts
      require_once(plugin_dir_path(__FILE__). '/includes/order-scripts.php');
   
      //  Load Widget
      require_once(plugin_dir_path(__FILE__). '/includes/order-widget.php');

      //Load Sidebar
      require_once(plugin_dir_path(__FILE__). '/includes/sidebar.php');
  
      // Register Widget
      function register_order_widget(){
          register_widget('ow_widget');
      }


    add_filter('the_content', 'add_to_cart');


    add_action('save_post', 'save_products');
    add_action('init', 'cpt_order');
    add_action('add_meta_boxes', 'adding_the_meta_boxes');
    add_action('init', 'input_cart');
    add_action('init', 'input_order');
    add_action('widgets_init', 'register_order_widget');

    register_activation_hook(__FILE__, 'add_to_cart_register');
    register_activation_hook(__FILE__, 'order_register');
    register_deactivation_hook(__FILE__, 'deactivation');


  
  