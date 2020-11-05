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
        // echo "<h2>" . __( 'Test Toplevel', 'menu-test' ) . "</h2>";
        echo "<h1> Orders </h1>";

        echo "<select>
                <option> recieved </option>
                <option> canceled </option>
                <option> shipped </option>
                <option> delivered </option>
            </select>";
    }

?>