<?php





function add_to_cart($content){
        if(in_the_loop() && is_main_query() ){
                return $content . "
                <form method=POST>
                    <input name=id type=hidden value=$id>
                    <button name=buy>Add to cart</button>
                </form>
                ";
        }
        return $content;
    }





    add_filter('the_content', 'add_to_cart');