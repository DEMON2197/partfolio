<?php

if (!defined('ABSPATH')) die('-1'); // Exit if accessed directly

define("__DATE_EVENT__", 0);
define("__URL_XML_FILE__", 1);

vc_add_shortcode_param( 'datumsformat', 'vc_shortcode_param_datumsformat_settings' );
function vc_shortcode_param_datumsformat_settings( $settings, $value ) {
	return '<div class="vc_datumsformat">'
	.'<input name="' . esc_attr( $settings['param_name'] ) . '" class="wpb_vc_param_value wpb-textinput ' .
	esc_attr( $settings['param_name'] ) . ' ' .
	esc_attr( $settings['type'] ) . '_field" type="date" value="' . esc_attr( $value ) . '" />' .
	'</div>'; 
}

class VCExtendAddonClassParserLiderboards {
	
	function __construct(){
		add_action('init', array( $this, 'integrateWithVC' ));
		add_shortcode( 'vcParserLiderboards', array( $this, 'loadvcParserLiderboards' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'loadCssAndJs' ) );
		
		if(current_user_can('read_private_pages')){
			//create table for database
			global $wpdb;
			//require_once ABSPATH . 'wp-admin/includes/upgrade.php';
			$table_name = $wpdb->get_blog_prefix() . 'leaderboard_players_list';
			
			/*$charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset} COLLATE {$wpdb->collate}";
			$sql = "CREATE TABLE {$table_name} (
					id bigint(15) unsigned NOT NULL auto_increment,
					page_id int(8) unsigned NOT NULL default 0,
					date_start DATETIME NOT NULL default '0000-00-00 00:00:00',
					date_finish DATETIME NOT NULL default '0000-00-00 00:00:00',
					nickname varchar(20) NOT NULL default '',
					points int(8) NOT NULL default 0,
					position int(6) NOT NULL default 0,
					prize varchar(50) NOT NULL default '',
					content MEDIUMTEXT NOT NULL default '',
					PRIMARY KEY  (id)
					)
					{$charset_collate};";
			
			dbDelta($sql); //запущено 1 раз для создания таблиц */
			
			/*$wpdb->insert($table_name, array(
						'page_id' => 1,
						'date_start' => '1970-01-01 00:00:00',
						'date_finish' => '1970-01-01 00:00:00',
						'nickname' => 'Isidor',
						'points' => 1200,
						'position' => 1,
						'prize' => '100E'
					), array(
						'%d',
						'%s',
						'%s',
						'%s',
						'%d',
						'%d',
						'%s'
					));
			*/
			
			
			
		}
	}
	
	public function integrateWithVC(){
		if ( ! defined( 'WPB_VC_VERSION' ) ) {
			// Display notice that Visual Compser is required
			add_action('admin_notices', array( $this, 'showVcVersionNotice' ));
			return;
		}
		
		vc_map( array(
			"name" => "Parser Liderboards",
			"description" => "Получает данные из лидерборда и выводит в таблицу",
			"base" => "vcParserLiderboards",
			"class" => "",
			"controls" => "full",
			"icon" => plugins_url('assets/asterisk_yellow.png', __FILE__), // or css class name which you can reffer in your css file later. Example: "vc_extend_my_class"
			"category" => __('vip-grinders', 'js_composer'),
			"params" => array(
					array(
							"type" => "textfield",
							"heading" => "Заголовок",
							"param_name" => "title"
					),
					array(
							"type" => "textfield",
							"heading" => "Ссылка на страницу лидерборда",
							"param_name" => "link_page"
					),
					array(
							"type" => "textfield",
							"heading" => "Пользовательский CSS (через пробел)",
							"param_name" => "user_css"
					),
					array(
							"type" => "datumsformat",
							"heading" => "Дата окончания гонок в месяце: ",
							"param_name" => "date_finish"
					)
				)
		));
	}
	
	public function loadvcParserLiderboards($atts){
		$title = isset($atts['title']) ? $atts['title'] : 'Стандартный заголовок';
		$link_page = isset($atts['link_page']) ? $atts['link_page'] : '';
		$user_css = isset($atts['user_css']) ? ' '.$atts['user_css'] : '';
		$date_finish = isset($atts['date_finish']) ? $atts['date_finish'] : '1970-01-01';
		
		
		$outertext = !empty($link_page) ? self::transform($link_page, 0, $date_finish, $user_css) : 'ошибка';
		
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
	
	
	private static function transform($link_page, $number, $date_finish, $user_css = null){
		global $wpdb, $post;
		$outer = '';
		$dates = array();
		$table_name = $wpdb->get_blog_prefix() . 'leaderboard_players_list';
		//Если админ, то тестим
			
			$date_finish .= " 23:59:59";
			if(strtotime("now") <= strtotime($date_finish)){
				$dataset = self::parsing_link($link_page);
				
				$sql = "SELECT * FROM {$table_name} WHERE page_id = {$post->ID} AND date_finish = '{$date_finish}'";
				$result = $wpdb->get_row($sql, ARRAY_A);
				if(!empty($result)){
					//Формирование таблицы лидеров
					for($i = 0; $i <= count($dataset[__DATE_EVENT__])-1; $i++){
						$html = file_get_contents($dataset[__URL_XML_FILE__][1][$i]);
						preg_match_all("/.*\".*([0-9][0-9].*[0-9][0-9].*[0-9][0-9][0-9][0-9]).*\".*\".*([0-9][0-9].*[0-9][0-9].*[0-9][0-9][0-9][0-9]).*\".*/i", $dataset[__DATE_EVENT__][0][$i], $dates);					
						$outer .= "<div style='margin-top: 25px; width: 100%; text-align:center;'>
									<p style='text-align: center; font-size: 15px; font-weight: 700;'>".$dates[1][0]." — ".$dates[2][0]."</p>
									</div>
									";
						if(strlen($html) > 0){
							preg_match_all('@'.'<.*>(.*)</.*>'.'@iu', $html, $result);
							$outer .= '<div class="liderboard-item"><table class="liderboards_table'.$user_css.'">';
								$outer .= '<thead><tr><td>Место</td><td>Игрок</td><td>Очки</td><td>Приз &#8364;</td></tr></thead>';
								$outer .= '<tbody><tr>';
								for($i2 = 0; $i2 < count($result[1]); $i2++){
									$outer .= '<td>'.trim($result[1][$i2]);
									if(($i2+1) % 4 == 0) $outer .= '</td></tr><tr>';
									else $outer .= '</td>';
										
								}
								$outer .= '</tr></tbody>';
								$outer .= '</table></div>';
						}
								
					}
					
					if($wpdb->update(
							$table_name,
							array(
								'content' => $outer
							),
							array(
								'page_id' => $post->ID,
								'date_finish' => $date_finish
							)
					) === false) $outer = 'Ошибка обновления базы данных';
					
				}else {
					
					//Формирование таблицы лидеров
					for($i = 0; $i <= count($dataset[__DATE_EVENT__])-1; $i++){
						$html = file_get_contents($dataset[__URL_XML_FILE__][1][$i]);
						preg_match_all("/.*\".*([0-9][0-9].*[0-9][0-9].*[0-9][0-9][0-9][0-9]).*\".*\".*([0-9][0-9].*[0-9][0-9].*[0-9][0-9][0-9][0-9]).*\".*/i", $dataset[__DATE_EVENT__][0][$i], $dates);					
						$outer .= "<div style='margin-top: 25px; width: 100%; text-align:center;'>
									<p style='text-align: center; font-size: 15px; font-weight: 700;'>".$dates[1][0]." — ".$dates[2][0]."</p>
									</div>
									";
						if(strlen($html) > 0){
							preg_match_all('@'.'<.*>(.*)</.*>'.'@iu', $html, $result);
							$outer .= '<div class="liderboard-item"><table class="liderboards_table'.$user_css.'">';
								$outer .= '<thead><tr><td>Место</td><td>Игрок</td><td>Очки</td><td>Приз &#8364;</td></tr></thead>';
								$outer .= '<tbody><tr>';
								for($i2 = 0; $i2 < count($result[1]); $i2++){
									$outer .= '<td>'.trim($result[1][$i2]);
									if(($i2+1) % 4 == 0) $outer .= '</td></tr><tr>';
									else $outer .= '</td>';
										
								}
								$outer .= '</tr></tbody>';
								$outer .= '</table></div>';
						}
								
					}
					
					if($wpdb->insert(
							$table_name,
							array(
								'date_finish' => $date_finish,
								'content' => $outer,
								'page_id' => $post->ID
							),
							array(
								'%s',
								'%s',
								'%d'
							)
					) === false) $outer = 'Ошибка записи в базу данных';
					
				}
				
			}else {
				
				$sql = "SELECT * FROM {$table_name} WHERE page_id = {$post->ID} AND date_finish = '{$date_finish}'";
				$result = $wpdb->get_row($sql, ARRAY_A);
				if(!empty($result)){
					
					$outer .= $result['content'];
					
				}else {
					
					$outer = 'Нет данных в заданный месяц!';
				}
				
			}
			
		
		//Возвращаем результат для вывода
		return $outer;
	}
	
	private static function parsing_link($link){
		$html = file_get_contents($link);
		$result = array();
		preg_match_all("/element.setAttribute(.*)/i", $html, $result[__DATE_EVENT__]);
		preg_match_all("/data-feedurl=\"(.*)\"/i", $html, $result[__URL_XML_FILE__]);
		return $result;
	}
}

new VCExtendAddonClassParserLiderboards();