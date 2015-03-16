<?php

// Create Faq Post
function bh_faq_custom_post_register() {  

    $arg = array(  
        'labels' => array (
			'name' => __( "BH FAQ"),
			'singular_label' => __("BH faq"),  
			'add_new_item' => __("Add New faq"),
			'edit_item' => __("Edit faq"),
			'new_item' => __("New faq"),
			'view_item' => __("View faq"),
		), 
        'public' => true,  
        'show_ui' => true,  
        'capability_type' => 'post',  
        'hierarchical' => false,  
        'rewrite' => true,  
        'supports' => array('title', 'editor')  
       );  
    register_post_type("bh_faq_faq" , $arg );  
}
add_action('init', 'bh_faq_custom_post_register');
