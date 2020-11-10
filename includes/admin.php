<?php
/*
    Admin Menu
*/
defined('ABSPATH') or die ('You have entered nikamas secret code');

    add_action('admin_menu', 'mt_add_pages');

    function mt_add_pages() {

        add_menu_page(__('Order status','menu-test'), __('Order status','menu-test'), 'manage_options', 'order-status', 'order_status_page' );
    }

    function order_status_page() {
        global $wpdb;
        echo "<h1> Orders </h1>";
        $results_orders = $wpdb->get_results("SELECT * FROM wp_order ORDER BY wp_order.order_date DESC LIMIT 10");


       
        foreach($results_orders as $order){
            
            $results_products = $wpdb->get_results("SELECT wp_posts.post_title, wp_postmeta.meta_value FROM wp_order_post INNER JOIN wp_posts ON wp_order_post.post_id=wp_posts.id INNER JOIN wp_postmeta ON wp_order_post.post_id = wp_postmeta.post_id WHERE $order->id=wp_order_post.order_id AND wp_postmeta.meta_key = 'price'");

            $items_array      = array();
            $price            = array();

            foreach($results_products as $product){
               array_push($items_array, $product->post_title);
               array_push($price, intval($product->meta_value));
            }

            $total_price = array_sum($price);
            
            echo "<div class='admin-order-wrapper'>
            <div class='order-div'>
            <p>Order Number: &nbsp" . $order->id . "</p> 
            <p class='show-items'> ▼ Order Items </p>
            <div class='products hidden'>" . join("<br>", $items_array) . "<br><p>Total Price: " . $total_price . "</p></div>"
            . $order->order_date . "<br>";
            
            if($order->order_status == "recieved" || $order->order_status == "shipped") {
                echo "<p class='order-status--blue'>" . $order->order_status . "</p> <br>";
            }elseif($order->order_status == "canceled") {
                echo "<p class='order-status--red'>" . $order->order_status . "</p> <br>";
            }else{
                echo "<p class='order-status--green'>" . $order->order_status . "</p> <br>";
            }

            
            
            echo "
                <form method=POST>
                    <select name=select_order_status>
                        <option value=recieved  " . ($order->order_status == 'recieved' ? 'selected': '') ."> recieved </option>
                        <option value=canceled  " . ($order->order_status == 'canceled' ? 'selected': '') ."> canceled </option>
                        <option value=shipped   " . ($order->order_status == 'shipped' ? 'selected': '') ."> shipped </option>
                        <option value=delivered " . ($order->order_status == 'delivered' ? 'selected': '') ."> delivered </option>
                    </select> 
                    <br>
                    <br>                                   
                    <input name=id type=hidden value=$order->id>
                    <button name=save>Save</button>
                </form>
            </div>";                
        }   
    }

    function update_order_status() {
        global $wpdb;
        if(isset($_POST['save'])){
            $select_option = $_POST['select_order_status'];
            $id = $_POST['id'];
            
            $query = $wpdb->prepare("UPDATE wp_order
            SET wp_order.order_status = '$select_option' WHERE $id = wp_order.id ");

            $wpdb->query($query);
        }
    }

    add_action('init', 'update_order_status');