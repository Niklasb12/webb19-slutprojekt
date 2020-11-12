<?php
/*
    Order widget
*/

defined('ABSPATH') or die ('You have entered nikamas secret code');

 class ow_widget extends WP_Widget{
    public $order_widget;
    function __construct(){

        parent::__construct(
            'ow_order', 

            'Order widget',

            array('description' =>'order widget') 
        );

    }

    function widget($args, $instance){
        echo $args['before_widget'];
        global $wpdb;
        $id = get_the_ID();
        $user_id = get_current_user_id(); 

        $results_cart = $wpdb->get_results("SELECT wp_posts.post_title, wp_postmeta.meta_value, wp_add_to_cart.id FROM wp_add_to_cart INNER JOIN wp_posts ON wp_add_to_cart.post_id = wp_posts.ID INNER JOIN wp_postmeta ON wp_add_to_cart.post_id = wp_postmeta.post_id WHERE wp_add_to_cart.user_id = $user_id AND wp_postmeta.meta_key = 'price'");

        $total        = array();
        
        echo "<h4 class='order-title'>" . $instance["title"] . "</h4>";
        if(!empty($results_cart)) {
            foreach($results_cart as $cart) {
                echo "<div class='cart'>
                <p class='cart_item'>" . $cart->post_title . " " . $cart->meta_value .  " Kr</p>
                <form method=POST>
                <button class='delete' name=delete> &#128465 </button>
                <input type=hidden name=id value=$cart->id></input>
                </form>
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
        printf('<input type="text" name="%s" value="%s">',
        $this->get_field_name("title"), $instance["title"]
        );
    }

}

function deleteCartItem(){
    global $wpdb;
    if(isset($_POST['delete'])){
        $id = $_POST['id'];

       $query = $wpdb->prepare("DELETE FROM wp_add_to_cart WHERE wp_add_to_cart.id=%s", $id);

       $wpdb->query($query);
    }
}
    function ow_init_widget(){
        register_widget('ow_widget');
    }
    
    add_action('init', 'deleteCartItem');
    add_action('widgets_init', 'ow_init_widget');
