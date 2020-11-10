<?php

defined('ABSPATH') or die ('You have entered nikamas secret code');

function cpt_order() {
    register_post_type(
        'cpt_order', array(
            'labels'            => array(
                'name'          => 'Products',
                'singular_name' => 'Product'
            ),
            'public'            => true,
            'has_archive'       => true,
            'supports'          => array('title', 'editor', 'thumbnail', 'author', 'excerpt')
        )
    );  
}


add_action('init', 'cpt_order');