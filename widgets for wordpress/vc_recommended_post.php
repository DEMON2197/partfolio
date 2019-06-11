<?php

if (!defined('ABSPATH')) die('-1'); // Exit if accessed directly


class VCExtendAddonClassRecommendedPost {
	
	function __construct(){
		add_action('init', array( $this, 'integrateWithVC' ));
		add_shortcode( 'vcRecommendedPost', array( $this, 'loadvcRecommendedPost' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'loadCssAndJs' ) );
	}
	
	public function integrateWithVC(){
		if ( ! defined( 'WPB_VC_VERSION' ) ) {
			add_action('admin_notices', array( $this, 'showVcVersionNotice' ));
			return;
		}
		
		$posts_list = get_posts(array('numberposts' => -1, 'suppress_filters' => false));
		$params = array();
		
		foreach($posts_list as $single_post){
			
			$params[$single_post->post_title] = $single_post->ID;
			
		}
		
		vc_map( array(
			"name" => "Рекомендуемая новость",
			"description" => "Всплывающий блок рекомендуемой новости, редактируемый для каждой страницы/поста",
			"base" => "vcRecommendedPost",
			"class" => "",
			"controls" => "full",
			"icon" => plugins_url('assets/asterisk_yellow.png', __FILE__),
			"category" => __('vip-grinders', 'js_composer'),
			"params" => array(
					array(
							"type" => "textfield",
							"heading" => "Заголовок:",
							"param_name" => "title"
					),
					array(
							"type" => "dropdown",
							"heading" => "Выберите новость:",
							"param_name" => "post_id",
							"value" => $params
					)
				)
		));
		
		
	}
	
	public function loadvcRecommendedPost($atts, $content = null){
		
		$title = isset($atts['title']) ? $atts['title'] : 'Стандартный заголовок';
		$post_id = isset($atts['post_id']) ? $atts['post_id'] : 1;
		
		$view_post = get_post($post_id, ARRAY_A);
		$post_link = get_permalink($view_post['ID']);
		$outertext = "
				<div class='article__suggest'>
					<div class='col-sm-12' style='padding: 0;'><h3 style='color: #105b74; margin-bottom: 15px;'><strong>Рекомендуем к прочтению</strong></h3></div>
					<div class='col-sm-12' style='padding: 0;
					border-right: 1px solid #ccc;
					border-top: 1px solid #ccc;
					border-bottom: 1px solid #ccc;'><div class='col-sm-6' style='padding: 0;'><figure class='vipg_vc_lposts__right_image animate-wrap'><a href='".$post_link."'><img class='img-responsive animate animate--scale' src='".get_the_post_thumbnail_url($view_post['ID'])."' style='height:100%; width:250px;'></a></figure></div>
					<div class='col-sm-6' style='display: table;
					height: 100%;
					position: absolute;
					right: 0;'><a href='".$post_link."' style='display: table-cell;
					vertical-align: middle;'>".$view_post['post_title']."</a></div></div>
				</div>
		";
		

		return $outertext;
	}
	
	public function loadCssAndJs() {

	}
	
	public function showVcVersionNotice() {
		$plugin_data = get_plugin_data(__FILE__);
		echo '
				<div class="updated">
					<p>'.sprintf(__('<strong>%s</strong> requires <strong><a href="http://bit.ly/vcomposer" target="_blank">Visual Composer</a></strong> plugin to be installed and activated on your site.', 'vc_extend'), $plugin_data['Name']).'</p>
				</div>';
	}
}

new VCExtendAddonClassRecommendedPost();



?>

