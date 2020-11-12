<?php

defined('ABSPATH') or die ('You have entered nikamas secret code');

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
    printf('<input type="text" name="%s" value="%s">',
     'price', get_post_meta($product->ID, 'price', true )
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



add_action('save_post', 'save_products');

add_action('add_meta_boxes', 'adding_the_meta_boxes');