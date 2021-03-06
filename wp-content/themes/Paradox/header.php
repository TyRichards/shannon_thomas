<?php
/**
 * The theme header
 * 
 * @package bootstrap-basic
 */
?>
<!DOCTYPE html>
<!--[if lt IE 7]>  <html class="no-js lt-ie9 lt-ie8 lt-ie7" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 7]>     <html class="no-js lt-ie9 lt-ie8" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 8]>     <html class="no-js lt-ie9" <?php language_attributes(); ?>> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" <?php language_attributes(); ?>> <!--<![endif]-->
	<head>
		<meta charset="<?php bloginfo('charset'); ?>">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<meta name="description" content="<?php bloginfo('description'); ?>">
		<meta name="author" content="Shannon Thomas">
		<meta name="google-site-verification" content="B9AvoLQnRbNKfNFm6RPRgRoQgKJJQdQsa0IHmBgP1AQ" />
		<title><?php wp_title('|', true, 'right'); ?></title>		
		<link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/assets/images/favicon.png">
		<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">	

		<?php wp_head(); ?>
	
		<!--[if lte IE 8]>
			<meta http-equiv="REFRESH" content="0;url=http://www.browsehappy.com/">
		<![endif]-->	
	</head>
	<body <?php body_class(); ?>>

		<!-- Google Analytics -->
		<script>
		  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

		  ga('create', 'UA-67824718-1', 'auto');
		  ga('send', 'pageview');

		</script>
	
		<!--[if lte IE 8]>
			<p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
		<![endif]-->	
					
		<?php do_action('before'); ?> 
		<header class="header-top">			
			
			<section class="top-navbar">				
				<nav class="navbar navbar-default" role="navigation">
					<div class="container">						
						<div class="navbar-header">
							<div class="row">	
								<div class="col-xs-12 col-sm-8 col-md-12">
									<a class="navbar-brand" href="<?php echo esc_url(home_url('/')); ?>" title="<?php echo esc_attr(get_bloginfo('name', 'display')); ?>" rel="home" width="100%">	
										<img src="<?php echo get_template_directory_uri(); ?>/assets/images/logo-sm.png" alt="Southlake Christian Counseling">
									</a>	
								</div>		
								<div class="col-xs-12 col-sm-4">									
									<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-primary-collapse">
										<div class="menu-elements">
											<span class="menu-text">Menu</span>
											<span class="sr-only"><?php _e('Toggle navigation', 'bootstrap-basic'); ?></span>
											<span class="icon-bar"></span>
											<span class="icon-bar"></span>
											<span class="icon-bar"></span>									
										</div>
									</button>
								</div>																		 
							</div> <!-- .row -->
						</div>
						
						<div class="collapse navbar-collapse navbar-primary-collapse">
							<?php wp_nav_menu(array('theme_location' => 'primary', 'container' => false, 'menu_class' => 'nav navbar-nav navbar-right', 'walker' => new BootstrapBasicMyWalkerNavMenu())); ?> 
							<?php if (is_active_sidebar('navbar-right')) { 								
								dynamic_sidebar('navbar-right'); 
							} ?> 

<!-- 							<div class="navbar-right visible-md visible-lg">
								<?php // gravity_form(2, $display_title=true, $display_description=false, $display_inactive=false, $field_values=null, $ajax=false, $tabindex); ?>
							</div>    -->     					
						</div><!--.navbar-collapse-->
					</div>
				</nav>				
			</section> <!-- section-navbar -->
		</header>		
		<div class="body-content">