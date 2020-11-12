<?php
    defined('ABSPATH') or die ('You have entered nikamas secret code');

    function input_order() {
        global $wpdb;
        if(isset($_POST['order'])){
            
            $user_id       = get_current_user_id();
            
            $query = $wpdb->prepare("INSERT INTO wp_order(user_id, order_date, order_status) VALUES (%s, NOW(), %s)", $user_id, 'recieved');

            $wpdb->query($query);

            $results       = $wpdb->get_results("SELECT * FROM wp_add_to_cart WHERE wp_add_to_cart.user_id=$user_id");

            $results_order = $wpdb->get_results("SELECT wp_order.id FROM wp_order ORDER BY wp_order.order_date DESC LIMIT 1");
            
            foreach($results_order as $order){
                $order_id  = $order->id;
            }

            foreach($results as $result) {
                 
                $query = $wpdb->prepare("INSERT INTO wp_order_post(order_id, post_id) VALUES (%s, %s)", $order_id, $result->post_id);

                $wpdb->query($query);
            }
  
            $query = $wpdb->prepare("DELETE FROM wp_add_to_cart WHERE wp_add_to_cart.user_id= %s", $user_id );

            $wpdb->query($query);

        }
    }
    add_action('init', 'input_order');