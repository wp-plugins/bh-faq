<?php
/**
 * @package BH FAQ
 * @version 1.0
 */
/*
Plugin Name: BH FAQ
Plugin URI: http://wordpress.org/plugins/bh-faq
Description: FAQ plugin. This plugin made with jquery ui. Supported all crosebrowser.
Author: Masum Billah
Version: 1.0
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
require_once('register-bh-faq-post-type.php');

/* BH FAQ Loop */
function bh_faq_template()
{ ?>
	<?php if(!is_paged()) { 
		$arg = array( 'post_type' => 'bh_faq_faq', 'posts_per_page' => -1 );
		$loop = new WP_Query( $arg );
		?>
		<div id="accordion">
			<?php while ( $loop->have_posts() ) : $loop->the_post(); ?>
			<h3 class="bh_s_caption"><?php the_title();?></h3>
			<div><?php the_content();?></div>
			<?php endwhile;?>
		</div>
<?php	wp_reset_query(); 
	} 
}

/**add the shortcode for the FAQ- for use in editor**/
function bh_faq_shortcode($atts, $content=null){
	$bhfaq= bh_faq_template();
	return $bhfaq;
}
add_shortcode('BH-FAQ', 'bh_faq_shortcode');


