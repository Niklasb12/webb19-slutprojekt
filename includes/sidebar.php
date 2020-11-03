<?php

/*
    Sidebar
*/


function my_register_sidebars() {
    /* Register the 'primary' sidebar. */
    register_sidebar(
        array(
            'id'            => 'primary',
            'name'          => __( 'Primary Sidebar' ),
            'description'   => __( 'A short description of the sidebar.' ),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>',
            )
        );
    }


    // function register_script_sidebar() { 
    //     wp_register_style( 'sidebar', plugins_url('assets/style.css', __FILE__));
    // }

    // function enqueue_style_sidebar(){
    //     wp_enqueue_style( 'sidebar' );
    // }


    // add_action('init', 'register_script_sidebar');
    // add_action('wp_enqueue_scripts', 'enqueue_style_sidebar');

    
    add_action( 'widgets_init', 'my_register_sidebars' );