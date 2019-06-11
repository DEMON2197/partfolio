<?php
if (!defined('ABSPATH')) exit;


class LatestNews extends WP_Widget
{
  function __construct()
  {
    parent::__construct('LatestNews',
						'Последние новости',
						array( 'description' => 'Выводит последние 5 новостей')
						);
  }
 
  function form($instance)
  {
	$instance = wp_parse_args( (array) $instance, array( 'title' => 'Пустой заголовок','quantity' => 3 ) );
	$title = $instance['title'];
	$quantity = $instance['quantity'];
	
	
	
	
?>
<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Заголовок:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></label></p>

<p><label for="<?php echo $this->get_field_id('quantity'); ?>"><?php _e('Колличество записей:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('quantity'); ?>" name="<?php echo $this->get_field_name('quantity'); ?>" type="text" value="<?php echo esc_attr($quantity); ?>" /></label></p>
<?php
  }
 
  function update($new_instance, $old_instance)
  {
	$instance = $old_instance;
    $instance['title'] = $new_instance['title'];
    $instance['quantity'] = $new_instance['quantity'];
	
    return $instance;
  }
 
  function widget($args, $instance)
  {
	extract($args, EXTR_SKIP);
	echo $args['before_widget'];

    $title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
	$quantity = empty($instance['quantity']) ? 3 : $instance['quantity'];
	
	if($quantity){
		echo '
				<div class="vc_row box__bordered box__bordered--sm box__bordered--blue vipg_startpage_deals_wrapper" style="margin-left: 0; margin-right: 0;">
				<div class="wpb_wrapper">
				<div class="wpb_text_column wpb_content_element revhead--table-l box__title--md">
				<div class="wpb_wrapper">
				<p class="panel-title vipg_vc_lposts__panel_title">Последние новости</p>
				</div>
				</div>
				<div class="box-widget__content box__bordered-body no-gap topFeaturedDealContainer">
			  ';
				$arguments = array(
							'posts_per_page' => $quantity,
							'exclude' => get_the_ID(),
							'orderby' => 'ID'
						);
				echo '<table>';
					$posts = get_posts($arguments);
					foreach($posts as $single_post){
						$link = get_permalink($single_post);
						echo '
							<tr>
								<td class="news_img"><figure class="vipg_vc_lposts__right_image animate-wrap"><a href="'.$link.'"><img class="img-responsive animate animate--scale" src="'.get_the_post_thumbnail_url($single_post).'" style="height:100%; width:110px;"></td>
								<td class="news_link"><strong><a href="'.$link.'">'.$single_post->post_title.'</a></strong></td>
							</tr>
						';
					}
				echo '</table>
					</div>
					</div>
					</div>
				';
	}
	
	echo $after_widget;
	
  }
 
}

function LatestNews_register_function()
{
    register_widget('LatestNews');
}

add_action('widgets_init', 'LatestNews_register_function');

?>