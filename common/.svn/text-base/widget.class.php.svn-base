<?php 


defined('ABSPATH') or die("Cannot access pages directly.");


/**
 * Only display once
 * 
 * This line of code will ensure that we only run the master widget class
 * a single time. We don't need to be throwing errors.
 */
if (!class_exists('MasterWidgetClass')) :

/**
 * Initializing 
 * 
 * The directory separator is different between linux and microsoft servers.
 * Thankfully php sets the DIRECTORY_SEPARATOR constant so that we know what
 * to use.
 */
defined("DS") or define("DS", DIRECTORY_SEPARATOR);

/**
 * Actions and Filters
 * 
 * Register any and all actions here. Nothing should actually be called 
 * directly, the entire system will be based on these actions and hooks.
 */
add_action( 'widgets_init', 'widgets_init_declare_registered', 1 );

/**
 * Register a widget
 * 
 * @param $widget
 */
function register_master_widget( $widget = null )
{
	global $masterWidgets;
	if (!isset($masterWidgets))
	{
		$masterWidgets = array();
	}
	
	if (!is_array($widget)) return false;
	
	$defaults = array(
		'id' => '1',
		'title' => 'Generic Widget',
		'classname' => $widget['id'],
		'do_wrapper' => true,
		'description' => '',
		'width' => 200,
		'height' => 200,
		'fields' => array(),
	);
	
	$masterWidgets[$widget['id']] = wp_parse_args($widget, $defaults);
	
	return true;
}

/**
 * Get the registered widgets
 * 
 * @return array
 */
function get_registered_masterwidgets()
{
	global $masterWidgets;
	
	if (!did_action('init_master_widgets'))
		do_action('init_master_widgets');
		
	return $masterWidgets;
}

/**
 * Initialize the widgets
 * 
 * @return boolean
 */
function widgets_init_declare_registered()
{
	//initialziing variables
	global $wp_widget_factory;
	$widgets = get_registered_masterwidgets();
	
	//reasons to fail
	if (empty($widgets) || !is_array($widgets)) return false;
	
	foreach ($widgets as $id => $widget)
	{
		$wp_widget_factory->widgets[$id] = new MasterWidgetClass( $widget );
	}
	
	return false;
}

/**
 * Multiple Widget Master Class
 * 
 * This class allows us to easily create qidgets without having to deal with the
 * mass of php code.
 * 
 * @author byrd
 * @since 1.3
 */
class MasterWidgetClass extends WP_Widget
{
	/**
	 * Constructor.
	 * 
	 * @param $widget
	 */
	function MasterWidgetClass( $widget )
	{
		$this->widget = apply_filters('master_widget_setup', $widget);
		$widget_ops = array(
			'classname' => $this->widget['classname'], 
			'description' => $this->widget['description'] 
		);
		$this->WP_Widget($this->widget['id'], $this->widget['title'], $widget_ops);
	}
	
	/**
	 * Display the Widget View
	 * 
	 * @example extract the args within the view template
	 
	 extract($args[1]); 
	 
	 * @param $args
	 * @param $instance
	 */
	function widget($sidebar, $instance)
	{
		//initializing variables
		$widget = $this->widget;
		$widget['number'] = $this->number;
		
		$args = array(
			'sidebar' => $sidebar,
			'widget' => $widget,
			'params' => $instance,
		);
		
		$show_view = apply_filters('master_widget_view', $this->widget['show_view'], $widget, $instance, $sidebar);
		$title = apply_filters( 'master_widget_title', $instance['title'] );
		
		if ( $widget['do_wrapper'] )
			echo $sidebar['before_widget'];
		
		if ( $title && $widget['do_wrapper'] )
			//echo $sidebar['before_title'] . $title . $sidebar['after_title'];
		
		//give people an opportunity
		do_action('master_widget_show_'.$widget['id']);
		
		//load the file if it exists
		if (file_exists($show_view))
			require $show_view;
			
		//call the function if it exists
		elseif (is_callable($show_view))
			call_user_func( $show_view, $args );
			
		//echo if we can't figure it out
		else echo $show_view;
		
		if ($widget['do_wrapper'])
			echo $sidebar['after_widget'];
		
	}
	
	/**
	 * Update from within the admin
	 * 
	 * @param $new_instance
	 * @param $old_instance
	 */
	function update($new_instance, $old_instance)
	{
		//initializing variables
		$new_instance = array_map('strip_tags', $new_instance);
		$instance = wp_parse_args($new_instance, $old_instance);
		
		return $instance;
	}
	
	/**
	 * Display the options form
	 * 
	 * @param $instance
	 */
	function form($instance)
	{
		//reasons to fail
		if (empty($this->widget['fields'])) return false;
		
		$defaults = array(
			'id' => '',
			'name' => '',
			'desc' => '',
			'type' => '',
			'options' => '',
			'std' => '',
		);
		
		do_action('master_widget_before');
		foreach ($this->widget['fields'] as $field)
		{
			//making sure we don't throw strict errors
			$field = wp_parse_args($field, $defaults);
			
			$meta = false;
			if (isset($field['id']) && array_key_exists($field['id'], $instance))
				@$meta = attribute_escape($instance[$field['id']]);
			
			if ($field['type'] != 'custom' && $field['type'] != 'metabox') 
			{
				echo '<p><label for="',$this->get_field_id($field['id']),'">';
			}
			if (isset($field['name']) && $field['name']) echo $field['name'],':';
			
			if( $field['terms'] ){
				$options = get_terms(array(EVENT_TAXONOMY, 'category'), 'fields=names');
				$field['options'] = $options;
			}
			switch ($field['type'])
			{
				case 'text':
					echo '<input type="text" name="', $this->get_field_name($field['id']), '" id="', $this->get_field_id($field['id']), '" value="', ($meta ? $meta : @$field['std']), '" class="vibe_text" />', 
					'<br/><span class="description">', @$field['desc'], '</span>';
					break;
				case 'textarea':
					echo '<textarea class="vibe_textarea" name="', $this->get_field_name($field['id']), '" id="', $this->get_field_id($field['id']), '" cols="60" rows="4" style="width:97%">', $meta ? $meta : @$field['std'], '</textarea>', 
					'<br/><span class="description">', @$field['desc'], '</span>';
					break;
				case 'select':
				//var_dump($field['options']);
					echo '<select class="vibe_select" name="', $this->get_field_name($field['id']), '" id="', $this->get_field_id($field['id']), '">';
					foreach ($field['options'] as $option)
					{
						echo '<option', $meta == $option ? ' selected="selected"' : '', '>', $option, '</option>';
					}
					echo '</select>', 
					'<br/><span class="description">', @$field['desc'], '</span>';
					break;
				case 'radio':
					foreach ($field['options'] as $option)
					{
						echo '<input class="vibe_radio" type="radio" name="', $this->get_field_name($field['id']), '" value="', $option['value'], '"', $meta == $option['value'] ? ' checked="checked"' : '', ' />', 
						$option['name'];
					}
					echo '<br/><span class="description">', @$field['desc'], '</span>';
					break;
				case 'checkbox':
					echo '<input type="hidden" name="', $this->get_field_name($field['id']), '" id="', $this->get_field_id($field['id']), '" /> ', 
						 '<input class="vibe_checkbox" type="checkbox" name="', $this->get_field_name($field['id']), '" id="', $this->get_field_id($field['id']), '"', $meta ? ' checked="checked"' : '', ' /> ', 
					'<br/><span class="description">', @$field['desc'], '</span>';
					break;
				case 'custom':
					echo $field['std'];
					break;
			}
			
			if ($field['type'] != 'custom' && $field['type'] != 'metabox') 
			{
				echo '</label></p>';
			}
		}
		do_action('master_widget_after');
		return;
	}
	
}// ends Master Widget Class

endif; //if !class_exists


//twitter widget output
function twitter_widget_view( $args ){
	extract($args);
	global $post, $artist_twitter_username;
?>	
	 <aside id="twitter" class="widget light">
		<div class="twitterbg"></div>
		<a href="http://twitter.com/<?php echo $params['twitter_id'] ?>/" class="followme" title="Follow us on Twitter">Follow Vivid Sydney on Twitter</a>
		<div class="twitter-feed"></div>
		<script>	
			var _tweeterUserName = '<?php echo $params['twitter_id'] ?>';		
			var _numTweets = '<?php echo $params['num_of_tweets'] ?>';
			 var tweetCount = 1;
			 $(document).ready(function () {
				$('.twitter-feed').jTweetsAnywhere({
					username: _tweeterUserName,
					count: _numTweets, 
					tweetTimestampDecorator: function (tweet, options) {						
						var tweetDate = new Date(Date.parse(Utils.format_my_date(tweet.created_at)));	
						var today = new Date();					
						var utcoffset = Date.today().getUTCOffset(); //it is returning as +1100
						utcoffset = parseInt(utcoffset[1]) * 10 +  parseInt(utcoffset[2]);
						tweetDate = new Date(tweetDate.getTime() + (utcoffset * 60 * 60 * 1000));												
						return ' ' + $.timeago(tweetDate);					
					},
					showTweetFeed: {
						showTimestamp: {
         				   refreshInterval: 15
					    }	
					}
				});
			});
     </script>	
	 </aside>	
<?php
}

// facebook fanbox widget output
function facebook_widget_view( $args ){
	extract($args);
?>
<h5><span class="fb-widget-title"></span><?php echo $params['facebook_widget_title']; ?></h5>
<div class="textwidget fb-like-box" data-href="<?php the_theme_option('facebookid') ?>" data-width="300" data-height="290" data-show-faces="true" data-stream="false" data-header="false">
</div>
<?php	
}
//settings for twitter widget.
$twitterWidget = array(
	'id'			=> 'twitter-custom-widget',	//make sure that this is unique
	'title' 		=> '[DOT]Twitter',	
	'description'	=> 'Showing twitter feed',
	'classname'		=> 'st-custom-wi',	
	'do_wrapper' 	=> true,	
	'show_view' 	=> 'twitter_widget_view',	
	'fields' => array(
		array(
			'name' 	=> 'Title',
			'desc' 	=> '',
			'id' 	=> 'title',
			'type' 	=> 'text',
			'std' 	=> 'Twitter Feed'
		),
		array(
			'name' 	=> 'Twitter Username',
			'desc' 	=> '',
			'id' 	=> 'twitter_id',
			'type' 	=> 'text',
			'std' 	=> get_theme_option('twitterid')
		),
		array(
			'name' 	=> 'Number of Tweets',
			'desc' 	=> '',
			'id' 	=> 'num_of_tweets',
			'type' 	=> 'text',
			'std' 	=> '4'
		),
	
	)
);
//register this widget
register_master_widget($twitterWidget);

//settings for facebook widget.
$facebookWidget = array(
	'id'			=> 'facebook-custom-widget',	//make sure that this is unique
	'title' 		=> '[DOT]Facebook',	
	'description'	=> 'Facebook Like Box',
	'classname'		=> 'st-custom-wi',	
	'do_wrapper' 	=> true,	
	'show_view' 	=> 'facebook_widget_view',
        'fields' => array(
		array(
			'name' 	=> 'Title',
			'desc' 	=> '',
			'id' 	=> 'facebook_widget_title',
			'type' 	=> 'text',
			'std' 	=> ''
		)
        )
);
//register this widget
register_master_widget($facebookWidget);

// Gallery Widget for footer
function gallery_widget_view( $args ){
    extract($args);
    ?>  
    <div class="widget footer-widgets flickr-widget">
        <h4><?php echo $params['info_widget_title']; ?></h4>	
        <ul class="flickr-list">
                <li><a href="#"><img alt="" src="<?php echo ASSET_URL; ?>images/flickr1.png"></a></li>
                <li><a href="#"><img alt="" src="<?php echo ASSET_URL; ?>images/flickr1.png"></a></li>
                <li><a href="#"><img alt="" src="<?php echo ASSET_URL; ?>images/flickr1.png"></a></li>
                <li><a href="#"><img alt="" src="<?php echo ASSET_URL; ?>images/flickr1.png"></a></li>
                <li><a href="#"><img alt="" src="<?php echo ASSET_URL; ?>images/flickr1.png"></a></li>
                <li><a href="#"><img alt="" src="<?php echo ASSET_URL; ?>images/flickr1.png"></a></li>
        </ul>
    </div>
<?php      

}
    
$galleryWidget = array(
	'id'		=> 'photo_gallery_widget',	//make sure that this is unique
	'title' 	=> '[DOT]Gallery',		
	'description'	=> 'To Show Photo Gallery Widget',
	'classname'     => 'gallery-wrapper',	
	'do_wrapper' 	=> true,	
	'show_view' 	=> 'gallery_widget_view',
        'fields' => array(
                    array(
                            'name' 	=> 'Title',
                            'desc' 	=> '',
                            'id' 	=> 'info_widget_title',
                            'type' 	=> 'text',
                            'std' 	=> ''
                    )
        )
	
);
//register this widget
register_master_widget($galleryWidget);

##################  custom widgets ###################################
// newsletter widget
function newsletter_widget_view( $args ){
	extract($args);
?>
<?php GLOBAL $THEME_OPTIONS; ?>
<div class="widget footer-widgets message-widget">
    <h4><?php echo $params['info_widget_title']; ?></h4>
    <div class="msg"></div>
    <form action="<?php echo WP_HOME ?>" method="post" id="footer-contact" class="contact-work-form">
            <input type="text" name="name" id="name" placeholder="Name" class="required">
            <input type="text" name="mail" id="mail" placeholder="Email" class="required">
            <textarea name="comment" id="comment" placeholder="Message" class="required"></textarea>
            <button type="submit" name="contact-submit" class="submit_contact">
                    <i class="fa fa-envelope"></i> Send
            </button>
    </form>
</div>
<script type="text/javascript">
    jQuery(document).ready(function($){
        $("#footer-contact").validate({
            submitHandler: function(form) {                   
                    var $fm = $("#footer-contact")
                    var form_data    = $fm.serialize();
                    var data_string  = 'action=mail_data&' + form_data;
                    $.ajax({
                        type: "POST",
                        url:  "/wp-admin/admin-ajax.php",
                        data: data_string,
                        success: function() {
                            $fm.hide();
                            $(".msg").fadeIn();
                    }
                    });
                    return false;                        
            }

        });
    });
</script> 
<?php 
} 
$newsletterWidget = array(
	'id'			=> 'newsletter-custom-widget',	//make sure that this is unique
	'title' 		=> '[DOT]Newsletter',	
	'description'	=> 'Subscription form',
	'classname'		=> 'st-custom-wi',	
	'do_wrapper' 	=> true,	
	'show_view' 	=> 'newsletter_widget_view',
        'fields' => array(
                    array(
                            'name' 	=> 'Title',
                            'desc' 	=> '',
                            'id' 	=> 'info_widget_title',
                            'type' 	=> 'text',
                            'std' 	=> ''
                    )
	)
);
register_master_widget($newsletterWidget);


// Main Info Widget
function info_widget_view( $args ){
	extract($args);
?>
<div class="widget footer-widgets info-widget">
    <h4><?php echo $params['info_widget_title']; ?></h4>
    <?php GLOBAL $THEME_OPTIONS; ?>
    <ul class="contact-list">
            <li><a class="phone" href="#"><i class="fa fa-phone"></i><?php echo $THEME_OPTIONS['info_mobile']; ?></a></li>
            <li><a class="phone" href="#"><i class="fa fa-phone"></i><?php echo $THEME_OPTIONS['info_contact']; ?></a></li>
            <li><a class="mail" href="#"><i class="fa fa-envelope"></i><?php echo $THEME_OPTIONS['info_email1']; ?></a></li>
            <li><a class="mail" href="#"><i class="fa fa-envelope"></i><?php echo $THEME_OPTIONS['info_email2']; ?></a></li>
            <li><a class="address" href="#"><i class="fa fa-home"></i><?php echo $THEME_OPTIONS['info_address']; ?></a></li>
    </ul>
</div>
<?php 
} 
$infoWidget = array(
	'id'		=> 'info-custom-widget',	//make sure that this is unique
	'title' 	=> '[DOT]Footer Contact Information',	
	'description'	=> 'It will show contact information including phone numbers, fax numbers and address.',
	'classname'	=> 'st-custom-wi',	
	'do_wrapper' 	=> true,	
	'show_view' 	=> 'info_widget_view',
        'fields' => array(
                    array(
                            'name' 	=> 'Title',
                            'desc' 	=> '',
                            'id' 	=> 'info_widget_title',
                            'type' 	=> 'text',
                            'std' 	=> ''
                    )
        )
	
);
register_master_widget($infoWidget);