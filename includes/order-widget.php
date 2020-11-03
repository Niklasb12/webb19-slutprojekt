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

        echo "<form method=POST>
        <button> Order </button>
        <input type=hidden name=order value=$id></input>
        </form>"; 

        
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