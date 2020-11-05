<?php

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


    add_action('init', 'cpt_order');