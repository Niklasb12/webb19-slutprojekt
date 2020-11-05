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
        $results_orders = $wpdb->get_results("SELECT * FROM wp_order ORDER BY wp_order.order_date DESC");

        foreach($results_orders as $order){
            echo "<div style='display: flex;'><div style='display: flex; flex-direction: column; margin: 40px;'>";
            echo $order->order_date . "<br>" . $order->order_status . " <br>";

            echo "
                <select>
                    <option value=recieved> recieved </option>
                    <option value=canceled> canceled </option>
                    <option value=shipped> shipped </option>
                    <option value=delivered> delivered </option>
                </select> <br>
                <form method=POST>
                    <input name=id type=hidden value=$order->id>
                    <button name=save>Save</button>
                </form>
                </div>
                ";

            }
    }

?>