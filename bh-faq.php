<?php
/**
 * @package BH FAQ
 * @version 1.1
 */
/*
Plugin Name: BH FAQ
Plugin URI: http://wordpress.org/plugins/bh-faq
Description: FAQ plugin. This plugin made with jquery ui. Supported all crosebrowser.
Author: Masum Billah
Version: 1.1
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

function bh_faq_initial(){?>

	<script>
		jQuery(function() {
		jQuery( "#accordion" ).accordion();
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
	$bhfaq= '<div id="accordion">';
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


