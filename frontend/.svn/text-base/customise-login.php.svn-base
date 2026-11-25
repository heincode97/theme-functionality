<?php
############################################################################################
// customise the login screen
############################################################################################
function change_wp_login_url() {
	return WP_HOME;
}
add_filter('login_headerurl', 'change_wp_login_url');

function change_wp_login_title() {
	return get_option('blogname');
}
add_filter('login_headertitle', 'change_wp_login_title');

function custom_login_js_css() { 
	
?>
	<link rel='stylesheet' id='custom-login-css-css'  href='<?php echo THEME_FUNCTIONALITY_URL ?>assets/css/custom-login.css' />
	<script src='https://ajax.googleapis.com/ajax/libs/jquery/2.0.1/jquery.min.js?ver=2.0.1'></script>
	<script>
	    $(document).ready(function(){			
			var link = '<a class="ce" href="http://www.digitaldots.com.mm" target="_blank">DigitalDots</a>';
			$("#login #nav").append(link);						
		});
	</script>
<?php	
}
add_action('login_head', 'custom_login_js_css');