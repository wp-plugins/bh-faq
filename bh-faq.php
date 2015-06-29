<?php
/**
 * @package BH FAQ
 * @version 1.2
 */
/*
Plugin Name: BH FAQ
Plugin URI: http://wordpress.org/plugins/bh-faq
Description: FAQ plugin. This plugin made with jquery ui. Supported all crosebrowser.
Author: Masum Billah
Version: 1.2
Author URI: http://getmasum.com
*/

function bh_faq_scripts_method() {
// including css file
 wp_enqueue_style('bh_faq_css', plugins_url('/css/bh-faq.css', __FILE__) );
 
// including js file
wp_enqueue_script( 'jquery' );
wp_enqueue_script( 'bh_faq_jquery', plugins_url( '/js/bh-faq.js', __FILE__ ), array( 'jquery' ));

}
add_action( 'wp_enqueue_scripts', 'bh_faq_scripts_method' );

// Adding Admin files
function bh_faq_color_pickr_function( $hook_suffix ) {
    // first check that $hook_suffix is appropriate for your admin page
    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_script( 'my-script-handle', plugins_url('js/color-pickr.js', __FILE__ ), array( 'wp-color-picker' ), false, true );   
}

add_action( 'admin_enqueue_scripts', 'bh_faq_color_pickr_function' );

// Adding BH FAQ Menu
function add_bhfaq_options()  
{  
	add_options_page('BH FAQ Settings', 'BH FAQ Settings', 'manage_options', 'bh-faq-settings','bh_bh_s_settings');  
}  
add_action('admin_menu', 'add_bhfaq_options');

add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'bh_faq_add_action_links' );

function bh_faq_add_action_links ( $bh_falinks ) {
 $bh_faq_links = array(
 '<a href="' . admin_url( 'options-general.php?page=bh-faq-settings' ) . '">Settings</a>',
 );
return array_merge( $bh_falinks, $bh_faq_links );
}

// Default options values
$bh_faq_defaults_options = array(
	'bh_faq_active_color' => '#2980b9',
	'bh_faq_hover_color' => '#3498db'

);

if ( is_admin() ) : // Load only if we are viewing an admin page
function bh_faq_register_settings() {
	// Register settings and call sanitation functions
	register_setting( 'bhfaq_p_options', 'bh_faq_defaults_options', 'bhfaq_validate_options' );
}
add_action( 'admin_init', 'bh_faq_register_settings' );

// Function to generate options page
function bh_bh_s_settings() {

	global $bh_faq_defaults_options;

	if ( ! isset( $_REQUEST['updated'] ) )
		$_REQUEST['updated'] = false; // This checks whether the form has just been submitted.		
?>
	<div class="wrap">

	
	<h2>BH FAQ Settings</h2>

	<?php if ( false !== $_REQUEST['updated'] ) : ?>
	<div class="updated fade"><p><strong><?php _e( 'Options saved' ); ?></strong></p></div>
	<?php endif; // If the form has just been submitted, this shows the notification ?>

	<form method="post" action="options.php">

	<?php $settings = get_option( 'bh_faq_defaults_options', $bh_faq_defaults_options ); ?>
	
	<?php settings_fields( 'bhfaq_p_options' );
	/* This function outputs some hidden fields required by the form,
	including a nonce, a unique number used to ensure the form has been submitted from the admin page
	and not somewhere else, very important for security */ ?>

	
	<table class="form-table"><!-- Grab a hot cup of coffee, yes we're using tables! -->

		<tr valign="top">
			<th scope="row"><label for="bh_faq_active_color">BH FAQ Active Color:</label></th>
			<td>
				<input id="bh_faq_active_color" type="text" name="bh_faq_defaults_options[bh_faq_active_color]" value="<?php echo stripslashes($settings['bh_faq_active_color']); ?>" class="my-color-field" /><p class="description">Please Select active color here. You can also add html HEX color code.</p>
			</td>
		</tr>		
		
		<tr valign="top">
			<th scope="row"><label for="bh_faq_hover_color">BH FAQ Hover Color:</label></th>
			<td>
				<input id="bh_faq_hover_color" type="text" name="bh_faq_defaults_options[bh_faq_hover_color]" value="<?php echo stripslashes($settings['bh_faq_hover_color']); ?>" class="my-color-field" /><p class="description">Please Select hover color here. You can also add html HEX color code.</p>
			</td>
		</tr>		
	
	</table>

	<p class="submit"><input type="submit" class="button-primary" value="Save Options" /></p>
	</form>

	</div>

	<?php

}

function bhfaq_validate_options( $input ) {
	global $bh_faq_defaults_options;

	$settings = get_option( 'bh_faq_defaults_options', $bh_faq_defaults_options );
	
	// We strip all tags from the text field, to avoid vulnerablilties like XSS

	$input['bh_faq_active_color'] = wp_filter_post_kses( $input['bh_faq_active_color'] );
	$input['bh_faq_hover_color'] = wp_filter_post_kses( $input['bh_faq_hover_color'] );

	
	return $input;
}

endif;  // EndIf is_admin() 


function bh_faq_initial(){?>

	<script>
		jQuery(function() {
		jQuery( ".accordion" ).accordion();
		});
	</script>
<?php
} 
add_action( 'wp_footer', 'bh_faq_initial' );

/*Files to Include*/
require_once('register-bh-faq-post.php');

/* BH FAQ Loop */
function bh_faq_template()
{ 
	$bhfaq= '<div class="accordion">';
	query_posts('post_type=bh_faq_faq&posts_per_page=-1');
	if (have_posts()) : while (have_posts()) : the_post(); 
		$faqtitle= get_the_title(); 
		$faqcontent= get_the_content(); 
		$bhfaq .='<h3 class="bh-faq-title">'.$faqtitle.'</h3><div class="bh-faq-content">'.$faqcontent.'</div>';		
	endwhile; endif; wp_reset_query();
	$bhfaq .= '</div>';
	return $bhfaq;

} 

/**add the shortcode for the FAQ- for use in editor**/
function bh_faq_shortcode($atts, $content=null){
	$bhfaq= bh_faq_template();
	return $bhfaq;
}
add_shortcode('BH-FAQ', 'bh_faq_shortcode');

function bh_faq_style(){
	global $bh_faq_defaults_options; $bh_faq_css_settings = get_option( 'bh_faq_defaults_options', $bh_faq_defaults_options ); ?>
	
	<style type="text/css">
	.ui-accordion .ui-accordion-header {
	background:<?php echo $bh_faq_css_settings['bh_faq_active_color']; ?>;
	}
	.ui-accordion .ui-accordion-header:hover{
	background: <?php echo $bh_faq_css_settings['bh_faq_hover_color']; ?>;
	}
	</style>
	
<?php
}
add_action('wp_head' , 'bh_faq_style');
