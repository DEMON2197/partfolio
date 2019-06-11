<?php

if (!defined('ABSPATH')) die('-1'); // Exit if accessed directly


class VCExtendAddonClassAdUnit {
	
	function __construct(){
		add_action('init', array( $this, 'integrateWithVC' ));
		add_shortcode( 'vcAdUnit', array( $this, 'loadvcAdUnit' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'loadCssAndJs' ) );
	}
	
	public function integrateWithVC(){
		if ( ! defined( 'WPB_VC_VERSION' ) ) {
			add_action('admin_notices', array( $this, 'showVcVersionNotice' ));
			return;
		}
		
		vc_map( array(
			"name" => "Рекламный виджет покер-рума",
			"description" => "Блок рекламы покер-рума (ad unit)",
			"base" => "vcAdUnit",
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
							"type" => "textfield",
							"heading" => "Размер бонуса:",
							"param_name" => "bonus"
					),
					array(
							"type" => "textfield",
							"heading" => "Рейкбэк:",
							"param_name" => "rakeback"
					),
					array(
							"type" => "textfield",
							"heading" => "Ссылка на регистрацию:",
							"param_name" => "singup"
					),
					array(
							"type" => "textfield",
							"heading" => "Ссылка на обзор:",
							"param_name" => "review"
					),
					array(
							"type" => "textfield",
							"heading" => "Ссылка на изображение:",
							"param_name" => "image_link"
					),
					array(
							"type" => "textfield",
							"heading" => "Описание изображения (для SEO):",
							"param_name" => "image_alt"
					),
					array(
							"holder" => "div",
							"type" => "textarea_html",
							"param_name" => "content",
							"heading" => __( "Редактор списка акций:", "text-domain" ),
							"value" => '<p>Вывод акций</p>',
							"description" => __( "Редактируемый список акций рума", "text-domain" )
					)
				)
		));
	}
	
	public function loadvcAdUnit($atts, $content = null){
		
		$title = isset($atts['title']) ? $atts['title'] : 'Стандартный заголовок';
		$bonus = isset($atts['bonus']) ? $atts['bonus'] : '';
		$image_link = isset($atts['image_link']) ? ' '.$atts['image_link'] : '';
		$image_alt = isset($atts['image_alt']) ? ' '.$atts['image_alt'] : '';
		$review = isset($atts['review']) ? $atts['review'] : '';
		$rakeback = isset($atts['rakeback']) ? $atts['rakeback'] : '';
		$singup = isset($atts['singup']) ? $atts['singup'] : '';
		$content = wpb_js_remove_wpautop($content, true);
		
		
		if(!empty($bonus)){

			$outertext = '<div class="row" style="margin: 25px 0px; border: 1px solid #bbbbbb; padding: 20px 10px;">
					<div class="col-sm-4">
						<div class="text-center">
							<img src="'.$image_link.'" alt="'.$image_alt.'">
						</div>
					</div>
					<div class="col-sm-4 hidden-xs" style="border-left: solid 1px #bbbbbb">
						<p style="font-size: 14px;margin: 19px 0;"><strong><span style="color: #105b74;">Бонус: </span>'.$bonus.'</strong></p>
						<p style="font-size: 14px;margin: 19px 0;"><strong><span style="color: #105b74;">Рейкбэк: </span>'.$rakeback.'</strong></p>
					</div>
					<div class="col-sm-4 text-center hidden-sm hidden-md hidden-lg">
						<p style="font-size: 14px;margin: 19px 0;"><strong><span><span style="color: #105b74;">Бонус: </span>'.$bonus.'</strong></span></p>
						<p style="font-size: 14px;margin: 19px 0;"><strong><span><span style="color: #105b74;">Рейкбэк: </span>'.$rakeback.'</strong></span></p>
					</div>
					<div class="col-sm-4">
						<div class="row">
							<div style="margin: 10px 0;">
								<a class="c-btn c-btn--primary c-btn--signup" style="width: 100%;" href="'.$review.'" target="_blank" rel="nofollow">
									Обзор
								</a>
							</div>
							<div style="margin: 10px 0;">
								<a class="c-btn c-btn--secondary c-btn--review" style="width: 100%" href="'.$singup.'" target="_blank" rel="nofollow">
									Регистрация
								</a>
							</div>
						</div>
					</div>'.
					($content != '' ? '<div class="col-sm-12 hidden-xs" style="border-top: 1px solid #bbb; padding-top: 15px;margin-top: 15px;">
						<h3 style="padding-bottom: 10px; padding-left: 26px;"><strong>Эксклюзивные акции от VIP-Grinders</strong>:</h3>
						'.$content.'
					</div>
					<div class="hidden-xl hidden-lg hidden-md hidden-sm col-xs-12 text-center" style="border-top: 1px solid #bbb; padding-top: 15px;margin-top: 15px;">
						<h3 style="padding-bottom: 10px;"><strong>Эксклюзивные акции от VIP-Grinders</strong>:</h3>
						'.$content.'
					</div>' : '').
				'</div>';
				};

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

new VCExtendAddonClassAdUnit();