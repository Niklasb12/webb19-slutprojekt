<?php
/*
    Order widget
*/

 class ow_widget extends WP_Widget{
    public $order_widget;
    function __construct(){

        parent::__construct(
            // Base ID of your widget
            'ow_order', 
            // Widget name will appear in UI
            'Order widget',
            // Widget description
            array('description' =>'order widget') 
        );

    }

    function widget($args, $instance){
        echo $args['before_widget'];
        global $wpdb;
        $id = get_the_ID();
        $user_id = get_current_user_id(); 

        $results_cart = $wpdb->get_results("SELECT wp_posts.post_title, wp_postmeta.meta_value FROM wp_add_to_cart INNER JOIN wp_posts ON wp_add_to_cart.post_id = wp_posts.ID INNER JOIN wp_postmeta ON wp_add_to_cart.post_id = wp_postmeta.post_id WHERE wp_add_to_cart.user_id = $user_id AND wp_postmeta.meta_key = 'price'");


        $total = array();
        
        echo "<h4 class='order-title'>" . $instance["title"] . "</h4>";
        if(!empty($results_cart)) {
            foreach($results_cart as $cart) {
                echo "<div>
                <p>" . $cart->post_title . " " . $cart->meta_value .  " Kr</p>
                </div>";
                array_push($total, intval($cart->meta_value));
            }
        $total_price = array_sum($total);

        echo "<div class='total-price'><p>Total: " . $total_price . " Kr</p></div>";

        echo "<form method=POST>
        <button> Order </button>
        <input type=hidden name=order value=$id></input>
        </form>";
    }else{
        echo "<p> empty </p>";
    }
        
        
        echo $args['after_widget'];
    }
    
     function form($instance) {
        printf('<input type="text" name="%s" value="' . $instance["title"] . '">',
        $this->get_field_name("title")
        );
     }

}
    function ow_init_widget(){
        register_widget('ow_widget');
    }
    add_action('widgets_init', 'ow_init_widget');
?>