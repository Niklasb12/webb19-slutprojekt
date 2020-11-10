<?php

//Add Scripts
function order_add_scripts(){
    wp_enqueue_style('order-main-style', plugins_url('style/style.css', __FILE__));
    wp_enqueue_script('order-main-script', plugins_url('/js/main.js', __FILE__));  
}

add_action('admin_enqueue_scripts', 'order_add_scripts');

add_action('wp_enqueue_scripts', 'order_add_scripts');
