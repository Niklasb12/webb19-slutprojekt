<?php

function input_cart() {
    global $wpdb;
    if(isset($_POST['add_to_cart'])){
        $post_id = $_POST['id'];
        $user_id = get_current_user_id();

        $wpdb->query("INSERT INTO wp_add_to_cart(user_id, post_id) VALUES ($user_id, $post_id)");
    }
}

function add_to_cart($content){
    global $wpdb;
    $id = get_the_ID();

    if(in_the_loop() && is_main_query() ){
        return $content . "<h5>" . get_post_meta($id, 'price', true ) . " Kr</h5>
        <form method=POST>
            <input name=id type=hidden value=$id>
            <button name=add_to_cart>Add to cart</button>
        </form>
        ";
    }
    return $content;
}


function sidebar($content){
    global $wpdb;
    $id = get_the_ID();

    if(is_archive() && is_main_query() ){
            return $content;        
    }
    return $content;
}

add_action('init', 'input_cart');

add_filter('the_content', 'add_to_cart');



