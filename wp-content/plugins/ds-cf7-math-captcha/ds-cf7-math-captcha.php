<?php
/*
Plugin Name: DS CF7 Math Captcha
Version: 2.0
Author: Dotsquares WPTeam
Plugin URI: dotsquares.com
Description: To stop spam email add math captcha.
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


define( 'DSCF7_VERSION', '2.0' );

define( 'DSCF7_REQUIRED_WP_VERSION', '6.3.1' );

define( 'DSCF7_TEXT_DOMAIN', 'dscf7-math-captcha' );

define( 'DSCF7_PLUGIN', __FILE__ );

define( 'DSCF7_PLUGIN_BASENAME', plugin_basename( DSCF7_PLUGIN ) );

define( 'DSCF7_PLUGIN_NAME', trim( dirname( DSCF7_PLUGIN_BASENAME ), '/' ) );

define( 'DSCF7_PLUGIN_URL', WP_CONTENT_URL . '/plugins/'. DSCF7_PLUGIN_NAME ); 

define( 'DSCF7_PLUGIN_DIR', untrailingslashit( dirname( DSCF7_PLUGIN ) ) );


add_action( 'wpcf7_init', 'dscf7_capctha' );	
add_filter( 'wpcf7_validate_dscf7captcha', 'dscf7_captcha_validation', 10, 2 );
add_filter( 'wpcf7_validate_dscf7captcha*', 'dscf7_captcha_validation', 10, 2 );
add_action( 'wp_enqueue_scripts', 'dscf7_ajaxify_scripts' );
add_action( 'wp_ajax_dscf7_refreshcaptcha','dscf7_refreshcaptcha_callback');
add_action( 'wp_ajax_nopriv_dscf7_refreshcaptcha','dscf7_refreshcaptcha_callback');

function dscf7_capctha() {
			wpcf7_add_form_tag(
				'dscf7captcha',
				'dscf7_captcha_handler', 
				array( 
					'name-attr' => true 
					)
			);
 }

function dscf7_captcha_validation( $result, $tag ) {
			//check captcha type
			
			
			if( $tag->type=='dscf7captcha' ) {
				
				//$tag->name= 'dscf7-captcha';
				$finalCechking = '';
				$cptha1=sanitize_text_field($_POST['dscf7_hidden_val1-'.$tag->name]);
				$cptha2=sanitize_text_field($_POST['dscf7_hidden_val2-'.$tag->name]);
				$cptha3=sanitize_text_field($_POST['dscf7_hidden_action-'.$tag->name]);
				
				if( $cptha3=='x' ) { 
					$finalCechking=( $cptha1*$cptha2 );
				}elseif( $cptha3=='+' ) { 
					$finalCechking=( $cptha1+$cptha2 );
				}else {
					$finalCechking=( $cptha1-$cptha2 );
					}
				$cptcha_value = isset( $_POST[$tag->name] )?trim( wp_unslash( strtr( (string) $_POST[$tag->name], "\n", " " ) ) ):'';
				
				if( $cptcha_value=='' ) {
					$result->invalidate($tag, apply_filters( 'dscf7_captcha_required', 'Please enter Captcha.'));
				}
				
				if( $cptcha_value!='' && $cptcha_value!=$finalCechking ) {
					
					$result->invalidate($tag, apply_filters( 'dscf7_captcha_invalidate', 'Incorrect Captcha!'));
				 }
				 
			 
			}
			
			return $result;
     }

function dscf7_captcha_handler( $tag ) {
		
		$operationAry=array('+','x','-');
		$random_action=array_rand($operationAry,2);
		$random_actionVal=$operationAry[$random_action[0]];
		$actnVal1=rand(1,9);
		$actnVal2=rand(1,9);
		
		
		
		$ds_cf7_captcha='<p class="dscf7captcha"><input name="dscf7_hidden_val1-'.$tag->name.'" id="dscf7_hidden_val1-'.$tag->name.'" type="hidden" value="'.$actnVal1.'" /><input name="dscf7_hidden_val2-'.$tag->name.'" id="dscf7_hidden_val2-'.$tag->name.'" type="hidden" value="'.$actnVal2.'" /><input name="dscf7_hidden_action-'.$tag->name.'" id="dscf7_hidden_action-'.$tag->name.'" type="hidden" value="'.$random_actionVal.'" />';
		$ds_cf7_captcha.='What is <span class="cf7as-firstAct">'.$actnVal2.'</span> '.$random_actionVal.'<span class="cf7as-firstAct"> '.$actnVal1.'</span> ? <a href="javascript:void(0)" id='.$tag->name.' class="dscf7_refresh_captcha"><img class="dscf7_captcha_icon" src="'.DSCF7_PLUGIN_URL.'/assets/img/icons8-refresh-30.png"/><img class="dscf7_captcha_reload_icon" src="'.DSCF7_PLUGIN_URL.'/assets/img/446bcd468478f5bfb7b4e5c804571392_w200.gif" style="display:none; width:30px" /></a><br><span class="wpcf7-form-control-wrap" data-name="'.$tag->name.'"> <input type="text" aria-invalid="false" aria-required="true" class="wpcf7-form-control wpcf7-text wpcf7-validates-as-required" size="5" value="" name="'.$tag->name.'" placeholder="Type your answer" style="width:200px;margin-bottom:10px;" oninput="this.value = this.value.replace(/[^0-9.]/g, \'\').replace(/(\..*)\./g, \'$1\');"></span></p>';
		return $ds_cf7_captcha;
}


function dscf7_ajaxify_scripts() {

	wp_enqueue_script( 'dscf7_refresh_script', DSCF7_PLUGIN_URL.'/assets/js/script.js', array('jquery'), '1.0.0', true );
	wp_localize_script( 'dscf7_refresh_script', 'ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
	wp_register_style( 'dscf7-math-captcha-style',  DSCF7_PLUGIN_URL.'/assets/css/style.css', array(), '', true );
	wp_enqueue_style( 'dscf7-math-captcha-style' );
   
}


function dscf7_refreshcaptcha_callback( $tag ) {
		$operationAry=array('+','x','-');
		$random_action=array_rand($operationAry,2);
		$random_actionVal=$operationAry[$random_action[0]];
		$actnVal1=rand(1,9);
		$actnVal2=rand(1,9);
		
		$tagName = $_POST['tagname'];
		
		$ds_cf7_captcha='<input name="dscf7_hidden_val1-'.$tagName.'" id="dscf7_hidden_val1-'.$tagName.'" type="hidden" value="'.$actnVal1.'" /><input name="dscf7_hidden_val2-'.$tagName.'" id="dscf7_hidden_val2-'.$tagName.'" type="hidden" value="'.$actnVal2.'" /><input name="dscf7_hidden_action-'.$tagName.'" id="dscf7_hidden_action-'.$tagName.'" type="hidden" value="'.$random_actionVal.'" />';
		$ds_cf7_captcha.='What is <span class="cf7as-firstAct">'.$actnVal2.'</span> '.$random_actionVal.'<span class="cf7as-firstAct"> '.$actnVal1.'</span> ? <a href="javascript:void(0)" id='.$tagName.' class="dscf7_refresh_captcha"><img class="dscf7_captcha_icon" src="'.DSCF7_PLUGIN_URL.'/assets/img/icons8-refresh-30.png"/><img class="dscf7_captcha_reload_icon" src="'.DSCF7_PLUGIN_URL.'/assets/img/446bcd468478f5bfb7b4e5c804571392_w200.gif" style="display:none; width:30px" /></a><br><span class="wpcf7-form-control-wrap" data-name="'.$tagName.'"> <input type="text" aria-invalid="false" aria-required="true" class="wpcf7-form-control wpcf7-text wpcf7-validates-as-required" size="5" value="" name="'.$tagName.'" placeholder="Type your answer" style="width:200px;margin-bottom:10px;" oninput="this.value = this.value.replace(/[^0-9.]/g, \'\').replace(/(\..*)\./g, \'$1\');"></span>';
		echo $ds_cf7_captcha;
		exit;
}

add_action( 'wpcf7_admin_init', 'wpcf7_add_tag_generator_dsmathcaptcha', 65, 0 );

function wpcf7_add_tag_generator_dsmathcaptcha() {
	$tag_generator = WPCF7_TagGenerator::get_instance();
	$tag_generator->add( 'dscf7captcha', __( 'math-captcha', 'contact-form-7' ),
		'wpcf7_tag_generator_dsmathcaptcha' );
	
}

function wpcf7_tag_generator_dsmathcaptcha( $contact_form, $args = '' ) {
	$args = wp_parse_args( $args, array() );
	$type = $args['id'];


	if ( 'dscf7captcha' == $type ) {
		$description = __( "Copy given below shortcode in form , see %s.", 'contact-form-7' );
	} 

?>
<div class="control-box">
<fieldset>

<table class="form-table">
<tbody>
	<tr>
	<th scope="row"><label for="<?php echo esc_attr( $args['content'] . '-name' ); ?>"><?php echo esc_html( __( 'Name', 'contact-form-7' ) ); ?></label></th>
	<td><input type="text" name="name" class="tg-name oneline" id="<?php echo esc_attr( $args['content'] . '-name' ); ?>" /></td>
	</tr>
</tbody>
</table>
</fieldset>
</div>
<div class="insert-box">
	<input type="text" name="<?php echo $type; ?>" class="tag code" readonly="readonly" onfocus="this.select()" />
	<div class="submitbox">
	<input type="button" class="button button-primary insert-tag" value="<?php echo esc_attr( __( 'Insert Tag', 'contact-form-7' ) ); ?>" />
	</div>
</div>
<?php
}