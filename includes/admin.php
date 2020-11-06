<?php
/*
    Admin Menu
*/


    // Hook for adding admin menus
    add_action('admin_menu', 'mt_add_pages');

    // action function for above hook
    function mt_add_pages() {

        // Add a new top-level menu (ill-advised):
        add_menu_page(__('Order status','menu-test'), __('Order status','menu-test'), 'manage_options', 'order-status', 'order_status_page' );
    }

    // mt_toplevel_page() displays the page content for the custom Test Toplevel menu
    function order_status_page() {
        global $wpdb;
        // echo "<h2>" . __( 'Test Toplevel', 'menu-test' ) . "</h2>";
        echo "<h1> Orders </h1>";
        $results_orders = $wpdb->get_results("SELECT * FROM wp_order ORDER BY wp_order.order_date DESC LIMIT 10");

    

        foreach($results_orders as $order){
            echo "<div style='display: flex;'>
                    <div style='display: flex; flex-direction: column; margin: 40px;'>";
                    if($order->order_status == "recieved" || $order->order_status == "shipped") {
                        echo $order->order_date . "<br>" . 
                        "<p style='color: blue;'>" . $order->order_status . "</p> <br>";
                    }elseif($order->order_status == "canceled") {
                        echo $order->order_date . "<br>" .
                        "<p style='color: red;'>" . $order->order_status . "</p> <br>";
                    }else{
                        echo $order->order_date . "<br>" .
                        "<p style='color: green;'>" . $order->order_status . "</p> <br>";
                    }

            
            
            echo "
                    <form method=POST>
                        <select name=select_order_status>
                            <option value=recieved> recieved </option>
                            <option value=canceled> canceled </option>
                            <option value=shipped> shipped </option>
                            <option value=delivered> delivered </option>
                        </select> <br>                                      
                        <input name=id type=hidden value=$order->id>
                        <button name=save>Save</button>
                    </form>
                </div>";

                
                
                echo $order->id ;
                
            }
            
    }

    function update_order_status() {
        global $wpdb;
        if(isset($_POST['save'])){
            $select_option = $_POST['select_order_status'];
            $id = $_POST['id'];
            
            $wpdb->query("UPDATE wp_order
            SET wp_order.order_status = '$select_option' WHERE $id = wp_order.id ");
        }
    }

    add_action('init', 'update_order_status');

    // function input_status(){
    //     global $wpdb;
    //     if(isset($_POST['id'])){
                
    //      $wpdb->query("UPDATE wp_order
    //      SET order_status =$order->status");


    //     }
    // }
    // add_action('init', 'input_status');

?>