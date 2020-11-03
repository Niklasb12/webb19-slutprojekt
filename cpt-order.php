<?php

    /*
        Plugin Name: Products
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

 
    function add_to_cart($content){
        if(in_the_loop() && is_main_query() ){
                return $content . "
                <form method=POST>
                    <input name=id type=hidden value=$id>
                    <button name=buy>Add to cart</button>
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
        $meta_table = $wpdb->prefix . "postmeta";

        $sql = "CREATE TABLE $table_name (
                id BIGINT(20) NOT NULL AUTO_INCREMENT,
                user_id BIGINT(20) UNSIGNED NOT NULL,
                post_id BIGINT(20) UNSIGNED NOT NULL,
                meta_id BIGINT(20) UNSIGNED NOT NULL,
                PRIMARY KEY  (id),
                FOREIGN KEY (user_id) REFERENCES $user_table(ID),
                FOREIGN KEY (post_id) REFERENCES $post_table(ID),
                FOREIGN KEY (meta_id) REFERENCES $meta_table(meta_id)
        ) $charset_collate;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
    }


    add_filter('the_content', 'add_to_cart');


    add_action('save_post', 'save_products');
    add_action('init', 'cpt_order');
    add_action('add_meta_boxes', 'adding_the_meta_boxes');

    register_activation_hook(__FILE__, 'add_to_cart_register');
