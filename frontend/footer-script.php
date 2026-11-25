<?php

function footer_scripts() {
	//fallback jquery
	?>		
	<?php if ($analytics = get_theme_option('google_analytics')) : ?>
	<script>		
		 var _gaq = _gaq || [];
		_gaq.push(['_setAccount', '<?php echo $analytics; ?>']);
		_gaq.push(['_trackPageview']);
		(function() {
			var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
			ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
			var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		})();		
	</script>
	<?php endif; ?>	
	<?php
}
add_action('wp_footer', 'footer_scripts', 10);