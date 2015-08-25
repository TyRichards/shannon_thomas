<?php
/* Plugin name: Justified Image Grid
   Author: Firsh
   Author URI: http://stateofbliss.us
   Plugin URI: http://stateofbliss.us/justified-image-grid
   Version: 1.2
   Description: Aligns a gallery into a justified, Flickr/Google+ style grid
   Text Domain: jig_td
*/
if(!class_exists("JustifiedImageGrid")){
	class JustifiedImageGrid {
		const PAGE_NAME = 'justified-image-grid';
		const SETTINGS_NAME = 'jig_settings';
		protected $defaults = array(		'thumbs_spacing'		=> 4,
											'animation_speed'		=> 300,
											'row_height'			=> 190,
											'height_deviation'		=> 40,
											'limit'					=> '',
											'max_rows'				=> '',
											'last_row'				=> 'normal',
											'orderby'				=> 'menu_order',
											'error_checking'		=> 'yes',
											'mouse_disable'			=> 'no',
											'jquery'				=> 'nochange',
											'jquery_location'		=> 'header',
											'link_class'			=> '',
											'link_rel'				=> 'auto',
											'link_title_field'		=> 'description',
											'img_alt_field'			=> 'title',
											'caption'				=> 'fade',
											'title_field'			=> 'title',
											'caption_field'			=> 'description',
											'caption_opacity'		=> 0.6,
											'caption_bg_color'		=> '#000',
											'caption_text_color'	=> '#FFF',
											'caption_text_shadow'	=> '',
											'caption_title_css'		=> "font-size: 15px;
font-weight: bold;
text-align:left;",
											'caption_desc_css'		=> "font-size: 12px;
font-weight: normal;
text-align:left;",
											'overlay'				=> 'hovered',
											'overlay_color'			=> '#000',
											'overlay_opacity'		=> 0.2,
											'desaturate'			=> 'off',
											'lightbox'				=> 'prettyphoto',
											'lightbox_max_size'		=> 'large',
											'prettyphoto_settings'	=> "
animation_speed: 'normal',
slideshow: 3500,
opacity: 0.6,
show_title: true,
counter_separator_label: '/',
theme: 'pp_default',
deeplinking: true,
overlay_gallery: false",
											'colorbox_settings'		=> "
speed: 350,
slideshowSpeed: 3500,
opacity: 0.6,
maxWidth: '100%',
maxHeight: '100%'",
											'min_height'			=> 0,
											'margin'				=> 0,
											'fb_app_id'				=> '',
											'fb_app_secret'			=> '',
											'fb_authed'				=> '',
											'facebook_caching'		=> 60,
											'timthumb_path'			=> '',
											'jig_activated'			=> '',
											'quality'				=> 90),
			$presets = 	array(	// Default out of the box
								'1' => array(),
								// Author's favorite
								'2' => array(		'thumbs_spacing' => 1,
													'row_height' => 215,
													'height_deviation' => 65,
													'caption' => 'fade',
													'caption_bg_color' => '#000',
													'caption_text_color' => '#FFF',
													'overlay' => 'others',
													'overlay_color' => '#000',
													'overlay_opacity' => 0.5,
													'desaturate' => 'others',
													'lightbox' => 'colorbox',
													'link_title_field' => 'title'),
								
								// Flickr style
								'3' => array(		'thumbs_spacing' => 8,
													'row_height' => 230,
													'height_deviation' => 80,
													'overlay' => 'off',
													'caption' => 'mixed',
													'caption_opacity' => 1,
													'caption_bg_color' => 'rgba(0,0,0,0.6)',
													'caption_text_color' => '#FFF',
													'caption_text_shadow' => '1px 1px 0px black',
													'caption_title_css'		=> "font-size: 13px;
font-weight: bold;
text-align:left;",
													'caption_desc_css'		=> "font-size: 11px;
font-weight: normal;
text-align:left;",
													'animation_speed' => 250,
													'desaturate' => 'off'),
								// G+ style
								'4' => array(		'thumbs_spacing' => 6,
													'row_height' => 280,
													'height_deviation' => 55,
													'overlay' => 'off',
													'caption' => 'fade',
													'caption_opacity' => 1,
													'caption_bg_color' => 'rgba(0,0,0,0.35)',
													'caption_text_color' => '#FFF',
													'caption_text_shadow' => '0px 0px 2px black',
													'animation_speed' => 150,
													'desaturate' => 'off'),
								// Fixed height, no fancy 
								'5' => array(		'thumbs_spacing' => 5,
													'row_height' => 175,
													'height_deviation' => 0,
													'caption' => 'off',

													'overlay' => 'off',
													'desaturate' => 'off'),
								// Artistic zen
								'6' => array(		'thumbs_spacing' => 0,
													'row_height' => 240,
													'height_deviation' => 60,
													'overlay' => 'others',
													'mouse_disable' => 'yes',
													'overlay_color' => '#000',
													'overlay_opacity' => 0.5,
													'caption' => 'fade',
													'caption_bg_color' => 'rgba(0,0,0,0.25)',
													'caption_text_color' => '#FFF',
													'caption_opacity' => 1,
													'caption_text_shadow' => '0px 0px 2px black',
													'animation_speed' => 600,
													'lightbox' => 'colorbox',
													'desaturate' => 'everything',
													'link_title_field' => 'title'),
								// Color magic funky style
								'7' => array(		'thumbs_spacing' => 0,
													'row_height' => 250,
													'height_deviation' => 100,
													'overlay' => 'others',
													'overlay_color' => '#5E005E',
													'overlay_opacity' => 0.6,
													'caption' => 'slide',
													'caption_bg_color' => '#FFBB00',
													'caption_text_color' => '#000',
													'caption_text_shadow' => '0px 1px 0px #FFEBB5',
													'caption_opacity' => 1,
													'caption_title_css'		=> "font-size: 18px;
font-weight: bold;
text-align:center;
text-transform:uppercase;",
													'caption_desc_css'		=> "font-size: 12px;
font-weight: normal;
text-align:center;
text-transform:uppercase;",
													'desaturate' => 'others'),
								// No links big images
								'8' => array(		'thumbs_spacing' => 1,
													'row_height' => 350,
													'height_deviation' => 50,
													'overlay' => 'others',
													'overlay_color' => '#000',
													'overlay_opacity' => 0.1,
													'caption' => 'fade',
													'caption_bg_color' => '#FFF',
													'caption_text_color' => '#000',
													'caption_opacity' => 0.7,
													'lightbox' => 'links-off',
													'desaturate' => 'off'),
								// Focus on the text
								'9' => array(		'thumbs_spacing' => 3,
													'row_height' => 250,
													'height_deviation' => 50,
													'caption' => 'mixed',
													'caption_text_color' => '#FFF',
													'caption_bg_color' => 'rgba(0,0,0,0.75)',
													'caption_opacity' => 1,
													'caption_title_css'		=> "font-size: 18px;
font-weight: bold;
text-align:left;
padding:8px 4px 8px;",
													'caption_desc_css'		=> "font-size: 14px;
font-weight: normal;
text-align:left;
padding:0 4px 8px;",
													'overlay' => 'hovered',
													'overlay_opacity' => 0.6,
													'desaturate' => 'hovered'),
			);


		// Hooks up the new settings page and its options, the shortcode, and loads the settings
		function JustifiedImageGrid($case = false){
			$this->default_settings = $this->defaults;
			$this->settings = $this->get_options();
			$this->settings_override = array(	'fb_app_id' 			=> $this->settings['fb_app_id'],
												'fb_app_secret' 		=> $this->settings['fb_app_secret'],
												'fb_authed' 			=> $this->settings['fb_authed'],
												'facebook_caching'		=> $this->settings['facebook_caching'],
												'jquery'				=> $this->settings['jquery'],
												'jquery_location'		=> $this->settings['jquery_location'],
												'prettyphoto_settings'	=> $this->settings['prettyphoto_settings'],
												'colorbox_settings'		=> $this->settings['colorbox_settings']
												);
			if(!$case){
				add_action('wp_enqueue_scripts', array(&$this, 'jquery_override'), 0);
				add_action('init', array(&$this, 'jig_init'), 100);
				add_action('plugins_loaded', array(&$this, 'jig_plugins_loaded'));
				add_shortcode('justified_image_grid', array(&$this, 'jig_init_shortcode'));
				add_filter("attachment_fields_to_edit", array($this, 'jig_image_attachment_fields_to_edit'), null, 2);
				add_filter("attachment_fields_to_save", array($this, 'jig_image_attachment_fields_to_save'), null , 2);
				add_filter('widget_text', 'do_shortcode');
				$this->template_tag = false;
			}else{
				if($case == 'activate'){
					//die("bazeg");
					//add_action( 'init', array( &$this, 'activate_cbfff' ) );
					$this->activate_cb();
					
				}
			}	
		}

		// Adds settings to the database along with a freshly activated setting
		function activate_cb(){
			$this->settings['jig_activated'] = "hot";
			update_option(self::SETTINGS_NAME,$this->settings);
		}

		// This will call the class in activation mode
	    function on_activate(){
			new JustifiedImageGrid( 'activate' );
	    }

	   
		function jig_init(){
			global $current_user;
			if(current_user_can('manage_options')){
				add_action('admin_menu', array(&$this, 'jig_init_settings_page'));
				add_action('admin_init', array(&$this, 'jig_init_options'));
			}
			// Don't bother doing this stuff if the current user lacks permissions
			if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') )
				return;
			// Add only in Rich Editor mode
			if ( get_user_option('rich_editing') == 'true') {
				// filter the tinyMCE buttons and add our own
				add_filter("mce_external_plugins", array(&$this, 'add_jig_shortcode_editor'));
				add_filter('mce_buttons', array(&$this, 'register_jig_shortcode_editor'));
				add_action('wp_ajax_jig_shortcode_editor', array(&$this, 'jig_shortcode_editor'));
			}
		}
		function jquery_override(){
			if($this->settings['jquery_location'] == 'header'){
				$footer = false;
			}else{
				$footer = true;
			}	
			if ($this->settings['jquery'] != 'nochange') {
				switch($this->settings['jquery']){
					case 'googlewp':
					case 'googleplugin':
						wp_deregister_script('jquery');
						$fallback_url = array();
						$fallback_url['googlewp'] = get_bloginfo('wpurl') . '/wp-includes/js/jquery/jquery.js';
						$fallback_url['googleplugin'] = plugins_url('js/jquery-1.7.2.min.js', __FILE__);
						$protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ? 'https' : 'http';
						$url = $protocol . '://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js';
						if (get_transient('google_jquery') == true) {	    
							wp_register_script('jquery', $url, array(), null, $footer);
						} 
						else {
							$resp = wp_remote_head($url);
							if (!is_wp_error($resp) && 200 == $resp['response']['code']) {
								set_transient('google_jquery', true, 60 * 5);
								wp_register_script('jquery', $url, array(), null, $footer);
							} 
							else {
								set_transient('google_jquery', false, 60 * 5);
								$url = $fallback_url[$this->settings['jquery']];
								wp_register_script('jquery', $url, array(), '1.7.2', $footer);
							}
						}
					break;
					case 'plugin':
						wp_deregister_script('jquery');
						$url = plugins_url('js/jquery-1.7.2.min.js', __FILE__);
						wp_register_script('jquery', $url, array(), '1.7.2', $footer);
					break;
				}
			}
			if(!$footer){
				wp_enqueue_script('jquery');
			}
		}
		// Loads the language file if found for the current locale
		function jig_plugins_loaded(){
			load_plugin_textdomain('jig_td', false, basename(dirname(__FILE__)) . '/languages/');
		}		

		// Adds the new settings page
		function jig_init_settings_page(){
			add_options_page(
				__('Justified Image Grid', 'jig_td'),
				__('Justified Image Grid', 'jig_td'),
				'manage_options',
				self::PAGE_NAME,
				array(&$this, 'jig_build_settings_page')
			);
		} 

		// Adds the new settings page
		function jig_build_settings_page(){
			wp_enqueue_script('jquery');
			echo '<div id="icon-options-general" class="icon32"></div><h2>'.__('Justified Image Grid', 'jig_td').'</h2>';
			echo '<p class="long-text">'.__("<strong>Add images to the gallery of a page/post, then use the shortcode [justified_image_grid] instead of the gallery block or [gallery] inside the edit area.</strong><br/><br/>Refer to the right side help bubbles or the documentation for more information.<br/><br/> Here you can choose the base settings that every gallery will share. You can override these settings on a per-gallery basis using shortcode attributes. If you'd like to start with a preset, feel free to click the buttons below which will load and apply the preset's settings. You can use this as a base then fine-tune settings. Selecting a preset will overwrite every setting below.", 'jig_td').'</p>';
?>		
			<script type="text/javascript">
					// sends an AJAX request to fetch the access token and other data from the session
					var page_types = new Object;
					page_types['current_user'] = '<?php _e("current user", "jig_td"); ?>';
					page_types['page'] = '<?php _e("page", "jig_td"); ?>';
					page_types['other_user'] = '<?php _e("other user", "jig_td"); ?>';
					function jig_get_fb_access_token(code){
						if(typeof(code)==='undefined'){
							code = 'current';
						}else if (code == 'other'){
							code = jQuery('#jigFbOtherUserCode').val();
						}
						if(code == ''){
							jig_message_box('Enter the code!');
						}else{
							jig_ajax_loading('show');
							jQuery.post(
								ajaxurl,
								{
									'action': 'jig_get_fb_access_token',
									'security': '<?php echo wp_create_nonce("jig_get_fb_access_token") ?>',
									'code' : code
								},
								function(data) {
									jig_ajax_process(data);
								},
								'json');
							}
					};
					function jig_add_fb_page(token){
						var page = jQuery('#jigAddFbPageInput').val();
						if(page.lastIndexOf('/') > -1){
							page = page.substr(page.lastIndexOf('/')+1);
							if(page.lastIndexOf('?') > -1){
								page = page.substr(0,page.lastIndexOf('?'));
							}
						}
						if(typeof(token)==='undefined'){
							token = '';
						}
						if(page == ''){
							jig_message_box('Enter the page!');
						}else{
							jig_ajax_loading('show');
							jQuery.post(
								ajaxurl,
								{
									'action': 'jig_add_fb_page',
									'security': '<?php echo wp_create_nonce("jig_add_fb_page") ?>',
									'page' : page,
									'token' : token

								},
								function(data) {
									jig_ajax_process(data);
								},
								'json');
						}
					}
					function jig_remove_fb_authed(element){
						var id = element.attr('id');
						id = id.substr(8)
						var user_name = element.find('.jigFbAuthedName').text();
						var type = element.attr('data-type');
						element.fadeOut(400, function(){jQuery(this).remove()})
						jQuery('#fbField'+id).remove()
						var authed_with = jQuery('#jigFbAuthedHidden input.jig_fb_field_access_token_owner_id[value="'+id+'"]')
						jig_message_box('The '+page_types[type]+': '+user_name+' is removed from the list.');

						if(authed_with.length != 0){
							authed_with.each(function(key,element){
								var page_name = jQuery(element).siblings('.jig_fb_field_user_name').val();
								var page_id = jQuery(element).siblings('.jig_fb_field_user_id').val();
								jQuery('#fbAuthed'+page_id).fadeOut(400, function(){jQuery(this).remove()})
								jQuery(element).parent().remove()
								jig_message_box('Page '+page_name+' is also removed because it required authentication from user '+user_name+'!');
							})
						}
						if(jQuery('.jigFbAuthedElement').length == 1){
							if(!jQuery('#jigFbEmptyList').is(':visible')){
								jQuery('#jigFbEmptyList').show()
							}
						}
					}
					function jig_verify_fb_authed(element){
						var id = element.attr('id');
						id = id.substr(8)
						var type = element.attr('data-type');
						var token = element.attr('data-access-token');
						jig_ajax_loading('show');
						jQuery.post(
						ajaxurl,
						{
							'action': 'jig_verify_fb_authed',
							'security': '<?php echo wp_create_nonce("jig_verify_fb_authed") ?>',
							'token' : token,
							'user_id' : id

						},
						function(data) {
							if(!data['error']){
								jig_message_box('<?php _e("The authentication of", "jig_td"); ?> '+
									page_types[type]+': '+data['user_name']+
									' <?php _e("is valid. You can choose from", "jig_td"); ?> '+
									data['info']['album_count']+'.'+
									(data['info']['expires']
										? (data['access_token_owner_name'] && type == 'page' 
											? ' <?php _e("It has access from the", "jig_td"); ?> '+page_types[data['info']['owner_type']]+': '+data['access_token_owner_name']+'.' 
											: '')
										+' <?php _e("It will expire in", "jig_td"); ?> '+data['info']['time_remaining']+
										' <?php _e("which is on", "jig_td"); ?> '+
										jig_expires_date(parseFloat(data['info']['time_added'])+parseFloat(data['info']['expires']))+'.'
										: ''));
							}else{
								jig_message_box(data['error']);
							}
							jig_ajax_loading('hide');
						},
						'json');
					}

					function jig_ajax_process(data){
						if(!data['error']){
							var existing = jQuery('#fbAuthed'+data['user_id']);
							if(existing.length == 0){
								jQuery('#jigFbAuthedPrototype')
									.clone()
									.attr({		'id':					'fbAuthed'+data['user_id'],
											 	'data-access-token': 	data['access_token'],
											 	'data-type':			data['type']  
											})
									.css('display','none')
									.appendTo(jQuery('#jigFbAuthed'))
									.find('.jigFbAuthedName')
									.html(data['user_name'])
									//.parent()
									.siblings('.jigFbAccessFrom')
									.html((data['access_token_owner_name'] ?
											 	'<div class="jigFbAccessFromInner">(via  '+data['access_token_owner_name']+')</div>' : ''
									));
									jQuery('#fbAuthed'+data['user_id']).fadeIn(300)
								jQuery('#jigFbAuthedHidden').append(function(){
									var settingName = jQuery('#jigFbAuthedHidden').attr('data-name');
									var output = '<div id="fbField'+data['user_id']+'">';
									jQuery.each(data, function(index, value) { 
										if(index != 'info'){
											output += '<input class="jig_fb_field_'+index+'" type="hidden" name="'+settingName+'['+data['user_id']+']['+index+']" value="'+value+'" />';
										}
									});
									output += '</div>';

									jig_message_box('<?php _e("The authentication of", "jig_td"); ?> '+
										page_types[data['type']]+': '+data['user_name']+
										' <?php _e("is successful. You can choose from", "jig_td"); ?> '+
										data['info']['album_count']+'.'+
										(data['info']['expires']
											? (data['access_token_owner_name'] && data['type'] == 'page' 
												? ' <?php _e("It has access from the", "jig_td"); ?> '+page_types[data['info']['owner_type']]+': '+data['access_token_owner_name']+'.' 
												: '')
											+' <?php _e("It will expire in", "jig_td"); ?> '+data['info']['time_remaining']+
											' <?php _e("which is on", "jig_td"); ?> '+
											jig_expires_date(parseFloat(data['info']['time_added'])+parseFloat(data['info']['expires']))+'.'
											: ''));

									return output;
								})
								if(jQuery('#jigFbEmptyList').is(':visible')){
									jQuery('#jigFbEmptyList').hide()
								}
							}else{
								existing.attr('data-access-token',data['access_token'])
									.removeClass('fbExpirdRedAlert')
									.find('.jigFbAuthedName')
									.html(data['user_name'])
									.siblings('.jigFbAccessFrom')
									.html((data['access_token_owner_name'] ?
											 	'<div class="jigFbAccessFromInner">(via  '+data['access_token_owner_name']+')</div>' : ''
									));
								var hidden_field = jQuery('#fbField'+data['user_id'])

								jQuery.each(data, function(index, value) { 
									if(index != 'info'){
										hidden_field.find('.jig_fb_field_'+index).val(value)
									}
								});

								//when an existing user is updated with a new token, search for pages authed with this token and update too
								var existing_authed_with = jQuery('#jigFbAuthedHidden .jig_fb_field_access_token_owner_id[value="'+data['user_id']+'"]')

								if(existing_authed_with.length != 0){
									existing_authed_with.each(function(index,element){
										element = jQuery(element);
										jQuery('#fbAuthed'+element.siblings('.jig_fb_field_access_token').val(data['access_token']).siblings('.jig_fb_field_user_id').val()).attr('data-access-token', data['access_token']).removeClass('fbExpirdRedAlert')
										var page_name = element.siblings('.jig_fb_field_user_name').val();
										jig_message_box('<?php _e("The re-authentication of already existing", "jig_td"); ?> '+
											page_types['page']+': '+page_name+
											' <?php _e("is done. It was necessary due to the authorization change in ", "jig_td"); ?> '+
											page_types[data['type']]+': '+data['user_name']+'.');
									})
									
								}

								jig_message_box('<?php _e("The re-authentication of already existing", "jig_td"); ?> '+
										page_types[data['type']]+': '+data['user_name']+
										' <?php _e("is done. You can choose from", "jig_td"); ?> '+
										data['info']['album_count']+'.'+
										(data['info']['expires']
											? (data['access_token_owner_name'] && data['type'] == 'page' 
												? ' <?php _e("It has access from the", "jig_td"); ?> '+page_types[data['info']['owner_type']]+': '+data['access_token_owner_name']+'.' 
												: '')
											+' <?php _e("It will expire in", "jig_td"); ?> '+data['info']['time_remaining']+
											' <?php _e("which is on", "jig_td"); ?> '+
											jig_expires_date(parseFloat(data['info']['time_added'])+parseFloat(data['info']['expires']))+'.'
											: ''));

							}
							jQuery('#jigFbAuthManualBtn').addClass('jig_disable')
							jQuery('#jigAddFbPageInput').val('');
						}else{
							jig_message_box(data['error']);
						}
						jig_ajax_loading('hide');
						jQuery(window).unbind('focus')
					}
					function jig_message_box(message){
						var entry = jQuery('<div class="jigFbAuthLogEntry">'+jig_timestamp()+message+'</div>')
						var box = jQuery('#jigFbAuthLog').prepend(entry)
						var new_entry = box.find('div:first').slideDown(400)
							box.find('div').each(function(index, element){
								if(index != 0){							
									var targetOpacity = 1-index*0.2;
									if(targetOpacity > 0){
										jQuery(element).animate({opacity:targetOpacity}, 400)
										jQuery(element).text(jQuery(element).text());
									}else{
										jQuery(element).slideUp(400, function(){jQuery(this).remove()})
									}
								}
							});					
					}
					function jig_toggle_fb_app_help(){
						jQuery('#jigFbAppHelp').slideToggle(600)
					}
					function jig_attempt_chmod(permission){
							jQuery.post(
							ajaxurl,
							{
								'action': 'jig_attempt_chmod',
								'security': '<?php echo wp_create_nonce("jig_attempt_chmod") ?>',
								'permission': permission
							},
							function(data) {

							/*	var entry = jQuery('<div class="jigFbAuthLogEntry">'+jig_timestamp()+message+'</div>')
						var box = jQuery('#jigFbAuthLog').prepend(entry)
						var new_entry = box.find('div:first').slideDown(400)*/


								jQuery("#ttChmodFeedback").html(data['message'])
								jQuery("#ttChmodFeedback").slideDown(300);
								jig_on_demand_check_permissions();
							},
							'json');
					}
					function jig_on_demand_check_permissions(){
							jQuery.post(
							ajaxurl,
							{
								'action': 'jig_on_demand_check_permissions',
								'security': '<?php echo wp_create_nonce("jig_on_demand_check_permissions") ?>'
							},
							function(data) {
								jQuery("#ttWritable").html(data['writable']);
								jQuery("#ttPermissionCache").html(data['permission_cache']);
								jQuery("#ttPermissionPlugin").html(data['permission_plugin']);
								jQuery("#ttPermissionResults").slideDown(300)
							},
							'json');
					}
					function jig_timestamp() {
						var d=new Date();
						return '['+d.toLocaleTimeString()+'] ';
					}
					function jig_expires_date(s) {
						var d=new Date(s*1000);
						return d.toLocaleDateString()+" ("+d.toLocaleTimeString()+")";
					}
					function jig_ajax_loading(direction){
						switch(direction){
							case 'show':
								jQuery('#jigFbLoadingAJAX').show()
								jQuery('#jigFb').css('opacity',0.1)
							break;
							case 'hide':
								jQuery('#jigFbLoadingAJAX').hide()
								jQuery('#jigFb').css('opacity',1)
							break;
							default:
						}
					}
					jQuery(document).ready(function(jQuery) {
						if(!(jQuery("#fb_app_id").val() != '' && jQuery("#fb_app_secret").val() != '')){
							jQuery('#jigFbWithAppOnly').html(<?php echo "'".'<div id="jigFbToAddUserHelpTitle">'.__('To add users', 'jig_td').':</div><ol><li>'.__('Create a Facebook App', 'jig_td').'</li><li>'.__('Fill out the App ID and App Secret fields', 'jig_td').'</li><li>'.__('Click Save Changes', 'jig_td').'</li></ol>'."'"; ?>)
						}
						jQuery('#jigFbAuthRequest').click(function(){
							jQuery('#jigFbAuthManualBtn').removeClass('jig_disable')
							jQuery(window).focus(function(){
								jig_get_fb_access_token();
							})
						})
						jQuery('#jigFbAuthed').on("click", ".jigFbAuthedRemove", function(event){
							event.stopImmediatePropagation();
							jig_remove_fb_authed(jQuery(this).parent());
						})
						.on("click", ".jigFbAuthedElement", function(event){
							jig_verify_fb_authed(jQuery(this).closest('.jigFbAuthedElement'));
						});

						jQuery('#jigFb').on("click", "#jigAddFbPageInput", function(event){
							event.stopImmediatePropagation();
						})
						.on("keypress", "#jigAddFbPageInput", function(event){
							if(event.which == 13){
								event.preventDefault();
								jQuery(this).parent().click()
							}
						})
						.on("keypress", "#jigFbOtherUserCode", function(event){
							if(event.which == 13){
								event.preventDefault();
								jig_get_fb_access_token('other');
							}
						})	
						.on("click", "#jigFbAuthManualBtn", function(event){
							jig_get_fb_access_token();
						})	
						.on("click", "#jigFbOtherUserLoad", function(event){
							jig_get_fb_access_token('other');
						})	
						.on("click", "#jigAddFbPage", function(event){
							jig_add_fb_page();
						})	
						.on("click", "#jigFbOtherUserLink", function(event){
							jQuery(this).select();
						})
						.on("click", "#jigFbAuthRequest", function(event){
							jig_ajax_loading('show');
						});
						jQuery('#jigFbOtherUserLink').val(jQuery('#jigFbOtherUserLink').attr('data-force'))
						jQuery('#jigFbOtherUserCode').val('')
					});

			</script>
			<style type="text/css">
				.form-table{
					background: none repeat scroll 0 0 #F3F3F7;
					border: 1px solid #DEDEE3;
					border-radius: 5px 5px 5px 5px;
					width: 98%;
				}
				h3{
					font-size: 18px;
	    			margin: 30px 0 0;
				}
				label{
					background: none repeat scroll 0 0 #FEFEFE;
					border: 1px solid #DFDFDF;
					border-radius: 5px 5px 5px 5px;
					color: #666;
					cursor: default;
					float: right;
					padding: 0 5px;
					text-align:right;
					max-width:400px;
				}
				.form-table tr:hover{
					background-color: #E7E7EB;
				}
				.button-secondary{
					width:180px;
					text-align:left;
				}
				.long-text{
					width:605px;
				}
				#jigFbLeft, #jigFbRight{
					float:left;
					width:50%;
				}
				.jig_disable,
				.jigFbAuthLogEntry,
				#jigFbAuthedPrototype,
				#ttPermissionResults,
				#ttChmodFeedback,
				#jigFbEmptyList{
					display: none;
				}
				#jigAddFbPage,
				#jigFbAuthBtn,
				#jigFbAuthManualBtn,
				#jigFbOtherUserLoad,
				.jigFbAuthedElement{
					margin: 5px 10px 5px 0;
					padding: 3px 8px;
					color: black;
					float: left;
					font-weight: normal;
					line-height: 24px;
					text-align: center;
					text-decoration: none;
					width: auto;
					background-color:#EEEEEE;		
					background-image: -ms-linear-gradient(top, #FFFFFF 0%, #DDDDDD 100%);
					background-image: -moz-linear-gradient(top, #FFFFFF 0%, #DDDDDD 100%);
					background-image: -o-linear-gradient(top, #FFFFFF 0%, #DDDDDD 100%);
					background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0, #FFFFFF), color-stop(1, #DDDDDD));
					background-image: -webkit-linear-gradient(top, #FFFFFF 0%, #DDDDDD 100%);
					background-image: linear-gradient(to bottom, #FFFFFF 0%, #DDDDDD 100%);		
					border: 1px solid #BBBBBB;
					border-radius: 3px 3px 3px 3px;
					cursor: pointer;
					font-size: 12px;
					min-height: 24px;
				}
				#jigAddFbPage:hover,
				#jigFbAuthBtn:hover,
				#jigFbAuthManualBtn:hover,
				#jigFbOtherUserLoad:hover,
				.jigFbAuthedElement:hover{
					border:1px solid #555555;
				}
				.fbExpirdRedAlert,
				.fbExpirdRedAlert:hover{
					border:1px solid red;
				}
				#jigFbAuthed{
					border-bottom: 1px solid #DFDFDF;
					margin-bottom: 10px;
					padding-bottom: 10px;
				}
				#jigFbAuthLogTitle{
					font-weight: bold;
				}
				.jigFbAuthLogEntry{
					margin-top:6px;
				}
				#jigFbAuthLogWrapper{
					background: none repeat scroll 0 0 #FEFEFE;
					border: 1px solid #DFDFDF;
					border-radius: 5px 5px 5px 5px;
					margin-top: 5px;
					padding: 5px;
				}
				.jigFbAuthedName,
				.jigFbAccessFrom,
				.jigFbAuthedRemove{
					float:left;
				}
				.jigFbAuthedRemove{
					color: #666;
					font-weight: bold;
					margin-left:10px;
				}
				.jigFbAccessFrom{
					font-size:10px;
					color:#AAA;
				}
				.jigFbAccessFromInner{
					margin-left:7px;
				}
				.jigFbAuthedRemove:hover{
					color:red;
				}
				#jigAddFbPageInput{
					margin-left: 7px;
				}
				#jigFbAuthOtherUserPanelTitle,
				#jigFbToAddUserHelpTitle
				{
					font-weight: bold;
					margin-top:12px;
				}

				#jigFbWrapper{
					position: relative;
				}
				#jigFbLoadingAJAX{			
					height: 100%;
					position: absolute;
					text-shadow: 0 1px white;
					width: 100%;
					z-index: 5;
					display: none;
				}
				#jigFbLoadingInner{
					background: url("<?php echo plugins_url('images/ajax-loader.gif', __FILE__); ?>") no-repeat left 30px;
					font-weight: bold;
					height: 55px;
					left: 50%;
					letter-spacing: 0.4px;
					line-height: 15px;
					margin: -30px 0 0 -120px;
					min-width: 215px;
					padding-left: 6px;
					position: absolute;
					text-align: left;
					text-transform: uppercase;
					top: 50%;
				}
				#jigFbLoadingInnerSmallText{
					color: #666;
					font-size: 10px;
					letter-spacing: 0;
				}
				#jigFbIcon{
					height: 49px;
					left: -49px;
					position: absolute;
					top: 0;
					width: 50px;
					background: url("<?php echo plugins_url('images/facebook-icon.png', __FILE__); ?>") no-repeat center center;
				}
				#jigFbAppHelp{
					display:none;
				}
				#jigFbAppHelpTitle
				{
					font-weight: bold;
				}
				#submitButton{
					background: url("images/button-grad.png") repeat-x scroll left top #21759B;
					border: 2px solid #164B63;
					border-radius: 3px 3px 3px 3px;
					color: #FFF;
					cursor: pointer;
					font-weight: bold;
					padding: 6px;
					text-shadow: 0px -1px 0 black;
					bottom: 5px;
					right: 5px;
					position: fixed;
					width: 200px;
					z-index: 1000;
				}
				#submitButton:hover{
					border: 2px solid #000;
				}
				.clearboth{
					clear:both;
				}
				.clearfix:after {
					content: ".";
					display: block;
					clear: both;
					visibility: hidden;
					line-height: 0;
					height: 0;
				}
				.clearfix {
					display: inline-block;
				}
				html[xmlns] .clearfix {
					display: block;
				}
				* html .clearfix {
					height: 1%;
				}
			</style>
			<form action="" method="post">
				<?php wp_nonce_field('jig_presets','jig_presets_nonce'); ?>
				<input type="hidden" name="presets" value="1" />
				<input type="submit" name="preset1" class="button-secondary" value="<?php _e('Preset 1: Out of the box (default)', 'jig_td'); ?>" />
				<input type="submit" name="preset2" class="button-secondary" value="<?php _e("Preset 2: Author's favorite", 'jig_td'); ?>" />
				<input type="submit" name="preset3" class="button-secondary" value="<?php _e('Preset 3: Flickr style', 'jig_td'); ?>" /><br/>
				<input type="submit" name="preset4" class="button-secondary" value="<?php _e('Preset 4: Google+ style', 'jig_td'); ?>" />
				<input type="submit" name="preset5" class="button-secondary" value="<?php _e('Preset 5: Fixed height, no fancy', 'jig_td'); ?>" />
				<input type="submit" name="preset6" class="button-secondary" value="<?php _e('Preset 6: Artistic-zen', 'jig_td'); ?>" /><br/>
				<input type="submit" name="preset7" class="button-secondary" value="<?php _e('Preset 7: Color magic fancy style', 'jig_td'); ?>" />
				<input type="submit" name="preset8" class="button-secondary" value="<?php _e('Preset 8: Big images no click', 'jig_td'); ?>" />
				<input type="submit" name="preset9" class="button-secondary" value="<?php _e('Preset 9: Focus on the text', 'jig_td'); ?>" />
			</form>
			<form method="post" action="options.php">
				<?php settings_fields(self::SETTINGS_NAME); ?>
				<?php do_settings_sections(self::PAGE_NAME); ?>
				<input id="submitButton" name="Submit" type="submit" value="<?php esc_attr_e('Save Changes'); ?>" />
				<!-- <p class="submit">
					<input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e('Save Changes'); ?>" />
				</p> -->
			</form>
	<?php
		}

		// updates and returns the defaults with settings from the database
		function get_options(){
			$saved_options = get_option(self::SETTINGS_NAME);
			if (!empty($saved_options)){
				foreach($this->default_settings as $key => $val){
					// if the user enters -1 it'll revert to the default value
					if(isset($saved_options[$key])){
						if($saved_options[$key] !== '-1'){
							$this->default_settings[$key] = $saved_options[$key];
						}
					}
				}
			}
			return $this->default_settings;
		}

		// Registers/adds the presets, sections, and settings fields.
		function jig_init_options(){
			if (isset($_POST['presets']) && check_admin_referer('jig_presets','jig_presets_nonce')){
				for ($i = 1; $i < 10; $i++) {
					if (isset($_POST['preset'.$i])){
						$preset_settings = $this->presets[$i];
						global $jig_preset_notice;
						$jig_preset_notice = "<div class='updated'><p><strong>".stripslashes($_POST['preset'.$i]).' '.__('has been successfully applied!')."</strong></p></div>"; 
						function print_preset_notice(){
							global $jig_preset_notice;
							echo $jig_preset_notice;
						}
						add_action('admin_notices', 'print_preset_notice');	
						break;
					}
				}
				update_option(self::SETTINGS_NAME, array_merge(array_merge($this->defaults, $this->settings_override), $preset_settings));
				$this->settings = $this->get_options();
			}  
			$this->jig_init_check_permissions();
			$this->jig_fb_check_expired();
			register_setting(self::SETTINGS_NAME, self::SETTINGS_NAME);
			// --------------------------------
			//    General settings section
			// --------------------------------
			add_settings_section(
				"jig_general_settings_section",						// Section ID  
				__('General settings', 'jig_td'),					// Section Title
				array(&$this, 'jig_print_general_settings_desc'),	// Callback for the description of the section
				self::PAGE_NAME										// Page to add the section to
			);  
			// Row height
			add_settings_field(
				'jig_row_height',									// Field ID
				__('Height of the rows', 'jig_td'),				// Field title 
				array(&$this, 'jig_print_text_input'),				// Field's callback
				self::PAGE_NAME,									// The field's parent page
				"jig_general_settings_section",						// The field's parent section
				array(	'id' => 'row_height',
						'label' => __('target height in pixels: 200 without px', 'jig_td'))
			); 
			// Row height deviation
			add_settings_field(
				'jig_height_deviation',
				__('Row height max deviation (+-)', 'jig_td'),
				array(&$this, 'jig_print_text_input'),
				self::PAGE_NAME,
				"jig_general_settings_section",
				array(	'id' => 'height_deviation',
						'label' => __('height +/- this value: 50 without px', 'jig_td'))
			); 
			// Thumbnails spacing
			add_settings_field(
				'jig_thumbs_spacing',
				__('Spacing between the thumbnails', 'jig_td'),
				array(&$this, 'jig_print_text_input'),
				self::PAGE_NAME,
				"jig_general_settings_section",
				array(	'id' => 'thumbs_spacing',
						'label' => __('0 or 4 or 10 etc... without px', 'jig_td'))
			);	
			// Margin around gallery
			add_settings_field(
				'jig_margin',
				__('Margin around gallery', 'jig_td'),
				array(&$this, 'jig_print_text_input'),
				self::PAGE_NAME,
				"jig_general_settings_section",
				array(	'id' => 'margin',
						'label' => __('CSS margin value: 10px or 0px 10px without \' or "', 'jig_td'))
			);
			// Animation speed
			add_settings_field(
				'jig_animation_speed',
				__('Animation speed', 'jig_td'),
				array(&$this, 'jig_print_text_input'),
				self::PAGE_NAME,
				"jig_general_settings_section",
				array(	'id' => 'animation_speed',
						'label' => __('as milliseconds: 200 is fast, 600 is slow', 'jig_td'))
			);
			// Min-height to avoid "jumping"
			add_settings_field(
				'jig_min_height',
				__('Min height to avoid "jumping"', 'jig_td'),
				array(&$this, 'jig_print_text_input'),
				self::PAGE_NAME,
				"jig_general_settings_section",
				array(	'id' => 'min_height',
						'label' => __('to avoid jumping footer if you have no sidebar: 800 without px<br />don\'t set it higher than the gallery itself', 'jig_td'))
			);
			// Limit image count
			add_settings_field(
				'jig_limit',
				__('Limit image count', 'jig_td'),
				array(&$this, 'jig_print_text_input'),
				self::PAGE_NAME,
				"jig_general_settings_section",
				array(	'id' => 'limit',
						'label' => __('only load this amount of images maximum - empty, 0 or -1 for unlimited', 'jig_td'))
			);
			// Max rows
			add_settings_field(
				'jig_max_rows',
				__('Max rows', 'jig_td'),
				array(&$this, 'jig_print_text_input'),
				self::PAGE_NAME,
				"jig_general_settings_section",
				array(	'id' => 'max_rows',
						'label' => __('only load x rows - empty, 0 or -1 for unlimited<br/>combine with fixed row height (0 deviation) to create a banner', 'jig_td'))
			);
			// Incomplete last row
			add_settings_field(
				'jig_last_row',
				__('Incomplete last row', 'jig_td'),
				array(&$this, 'jig_print_last_row_input'),
				self::PAGE_NAME,
				"jig_general_settings_section"
			);
			
			// The order of the images
			add_settings_field(
				'jig_orderby',
				__('Order of the images', 'jig_td'),
				array(&$this, 'jig_print_orderby_input'),
				self::PAGE_NAME,
				"jig_general_settings_section"
			);
			// Right click disable
			add_settings_field(
				'jig_mouse_disable',
				__("Disable right mouse menu", 'jig_td'),
				array(&$this, 'jig_print_mouse_disable_input'),
				self::PAGE_NAME,
				"jig_general_settings_section"
			);
			// Error checking switch
			add_settings_field(
				'jig_error_checking',
				__("Error checking", 'jig_td'),
				array(&$this, 'jig_print_error_checking_input'),
				self::PAGE_NAME,
				"jig_general_settings_section"
			);
			
			// jQuery load location
			add_settings_field(
				'jig_jquery',
				__('jQuery source', 'jig_td'),
				array(&$this, 'jig_print_jquery_input'),
				self::PAGE_NAME,
				"jig_general_settings_section"
			);
			// jQuery load location
			add_settings_field(
				'jig_jquery_location',
				__('jQuery load location', 'jig_td'),
				array(&$this, 'jig_print_jquery_location_input'),
				self::PAGE_NAME,
				"jig_general_settings_section"
			); 
			// --------------------------------
			//             Lightboxes
			// --------------------------------
			add_settings_section(
				"jig_lightboxes_section",
				__('Lightboxes', 'jig_td'),
				array(&$this, 'jig_print_lightboxes_desc'),
				self::PAGE_NAME
			);  
			// Lightbox type
			add_settings_field(
				'jig_lightbox',
				__('Lightbox type', 'jig_td'),
				array(&$this, 'jig_print_lightbox_input'),
				self::PAGE_NAME,
				"jig_lightboxes_section"
			); 
			// Link class
			add_settings_field(
				'jig_link_class',
				__('Link class(es)', 'jig_td'),
				array(&$this, 'jig_print_text_input'),
				self::PAGE_NAME,
				"jig_lightboxes_section",
				array(	'id' => 'link_class',
						'label' => __("class of the image's anchor tag", 'jig_td'))
			);
			// Link rel
			add_settings_field(
				'jig_link_rel',
				__('Link rel', 'jig_td'),
				array(&$this, 'jig_print_text_input'),
				self::PAGE_NAME,
				"jig_lightboxes_section",
				array(	'id' => 'link_rel',
						'label' => __('groups images together like gallery[modal]<br/>make the field empty to ungroup or enter auto for prettyPhoto deeplinking', 'jig_td'))
			);
			// Maximum size for lightbox (the image will link to this size)
			add_settings_field(
				'jig_lightbox_max_size',
				__('Maximum size for lightbox (the image will link to this size)', 'jig_td'),
				array(&$this, 'jig_print_lightbox_max_size_input'),
				self::PAGE_NAME,
				"jig_lightboxes_section"
			);
			// prettyPhoto JS settings
			add_settings_field(
				'jig_prettyphoto_settings',
				__('prettyPhoto JS settings', 'jig_td'),
				array(&$this, 'jig_print_textarea_input'),
				self::PAGE_NAME,
				"jig_lightboxes_section",
				array(	'id' => 'prettyphoto_settings',
						'label' => __("extra JavaScript settings for <a href=\"http://www.no-margin-for-errors.com/projects/prettyphoto-jquery-lightbox-clone/documentation/\" target=\"_blank\">prettyPhoto</a><br/>try theme: 'light_rounded', 'dark_rounded',<br/>'light_square', 'dark_square', or 'facebook',<br/>to disable social tools add the following line<br/>social_tools: false<br/>If deeplinking is enabled, the social tools share the individual images by their ID/position in the gallery. This is only unreliable if the image order changes or updates - disable deeplinking in that case!<br/>Watch for commas: every row ends with a comma except the last one!", 'jig_td'),
						'rows' => 8)
			);
			// ColorBox JS settings
			add_settings_field(
				'jig_colorbox_settings',
				__('ColorBox JS settings', 'jig_td'),
				array(&$this, 'jig_print_textarea_input'),
				self::PAGE_NAME,
				"jig_lightboxes_section",
				array(	'id' => 'colorbox_settings',
						'label' => __('extra JavaScript settings for <a href="http://www.jacklmoore.com/colorbox" target="_blank">ColorBox</a>', 'jig_td'),
					'rows' => 5)
			);
			// WP field for link title (anchor tag's title attribute)
			add_settings_field(
				'jig_link_title_field',
				__("WP field for link title (anchor tag's title attribute)", 'jig_td'),
				array(&$this, 'jig_print_link_title_field_input'),
				self::PAGE_NAME,
				"jig_lightboxes_section"
			);
			// WP field for img alt (image tag's alt attribute)
			add_settings_field(
				'jig_img_alt_field',
				__("WP field for img alt (image tag's alt attribute)", 'jig_td'),
				array(&$this, 'jig_print_img_alt_field_input'),
				self::PAGE_NAME,
				"jig_lightboxes_section"
			);
			// --------------------------------
			//             Captions
			// --------------------------------
			add_settings_section(
				"jig_captions_section",
				__('Captions', 'jig_td'),
				array(&$this, 'jig_print_captions_desc'),
				self::PAGE_NAME
			);  
			// Caption style
			add_settings_field(
				'jig_caption',
				__('Caption style', 'jig_td'),
				array(&$this, 'jig_print_caption_input'),
				self::PAGE_NAME,
				"jig_captions_section"

			);  

			// Caption opacity
			add_settings_field(
				'jig_caption_opacity',
				__('Caption opacity', 'jig_td'),
				array(&$this, 'jig_print_text_input'),
				self::PAGE_NAME,
				"jig_captions_section",
				array(	'id' => 'caption_opacity',
						'label' => __('affects entire caption, a number between 0 and 1', 'jig_td'))
			);
			// Caption background color
			add_settings_field(
				'jig_caption_bg_color',
				__('Caption background color', 'jig_td'),
				array(&$this, 'jig_print_text_input'),
				self::PAGE_NAME,
				"jig_captions_section",
				array(	'id' => 'caption_bg_color',
						'label' => __('any CSS color,<br />for opacity use rgba(0,0,0,0.3) only when the caption_opacity is 1', 'jig_td'))
			);
			// Caption text color
			add_settings_field(
				'jig_caption_text_color',
				__('Caption text color', 'jig_td'),
				array(&$this, 'jig_print_text_input'),
				self::PAGE_NAME,
				"jig_captions_section",
				array(	'id' => 'caption_text_color',
						'label' => __('any CSS color except rgba', 'jig_td'))
			);
			// Caption title CSS
			add_settings_field(
				'jig_caption_title_css',
				__('Caption title CSS', 'jig_td'),
				array(&$this, 'jig_print_textarea_input'),
				self::PAGE_NAME,
				"jig_captions_section",
				array(	'id' => 'caption_title_css',
						'label' => __('extra CSS settings for the caption title', 'jig_td'),
					'rows' => 3)
			);
			// Caption description CSS
			add_settings_field(
				'jig_caption_desc_css',
				__('Caption description CSS', 'jig_td'),
				array(&$this, 'jig_print_textarea_input'),
				self::PAGE_NAME,
				"jig_captions_section",
				array(	'id' => 'caption_desc_css',
						'label' => __('extra CSS settings for the caption description', 'jig_td'),
					'rows' => 3)
			);
			// Field for title
			add_settings_field(
				'jig_title_field',
				__('WP field to use for title (main caption)', 'jig_td'),
				array(&$this, 'jig_print_title_field_input'),
				self::PAGE_NAME,
				"jig_captions_section"
			);
			// Field for caption
			add_settings_field(
				'jig_caption_field',
				__('WP field to use for caption (description)', 'jig_td'),
				array(&$this, 'jig_print_caption_field_input'),
				self::PAGE_NAME,
				"jig_captions_section"
			);
			// Text shadow
			add_settings_field(
				'jig_caption_text_shadow',
				__('Text shadow<br /><b>Only when caption opacity is 1</b>.<br />IE is unsupported.', 'jig_td'),
				array(&$this, 'jig_print_text_input'),
				self::PAGE_NAME,
				"jig_captions_section",
				array(	'id' => 'caption_text_shadow',
						'label' => __('1px 1px 0px black (x, y, blur, color - respectively)<br />it\'s only applied when caption_opacity is set to 1', 'jig_td'))
			);
			// --------------------------------
			//          Color overlay
			// --------------------------------
			add_settings_section(
				"jig_overlay_section",
				__('Color overlay', 'jig_td'),
				array(&$this, 'jig_print_overlay_desc'),
				self::PAGE_NAME
			);  
			// Overlay type
			add_settings_field(
				'jig_overlay',
				__('Overlay type', 'jig_td'),
				array(&$this, 'jig_print_overlay_input'),
				self::PAGE_NAME,
				"jig_overlay_section"
			);
			// Overlay opacity
			add_settings_field(
				'jig_overlay_opacity',
				__('Overlay opacity', 'jig_td'),
				array(&$this, 'jig_print_text_input'),
				self::PAGE_NAME,
				"jig_overlay_section",
				array(	'id' => 'overlay_opacity',
						'label' => __('a number between 0 and 1', 'jig_td'))
			);
			// Overlay color
			add_settings_field(
				'jig_overlay_color',
				__('Overlay color', 'jig_td'),
				array(&$this, 'jig_print_text_input'),
				self::PAGE_NAME,
				"jig_overlay_section",
				array(	'id' => 'overlay_color',
						'label' => __('any CSS color except rgba', 'jig_td'))
			);
			// --------------------------------
			//        Desaturate
			// --------------------------------
			add_settings_section(
				"jig_desaturate_section",
				__('Desaturate', 'jig_td'),
				array(&$this, 'jig_print_desaturate_desc'),
				self::PAGE_NAME
			);  
			// Desaturate method
			add_settings_field(
				'jig_desaturate',
				__('Desaturate method', 'jig_td'),
				array(&$this, 'jig_print_desaturate_input'),
				self::PAGE_NAME,
				 "jig_desaturate_section"
			);
			// --------------------------------
			//        Facebook
			// --------------------------------
			add_settings_section(
				"jig_facebook_section",
				__('Facebook', 'jig_td'),
				array(&$this, 'jig_print_facebook_desc'),
				self::PAGE_NAME
			); 
			// App ID
			add_settings_field(
				'jig_fb_app_id',
				__('App ID', 'jig_td'),
				array(&$this, 'jig_print_text_input'),
				self::PAGE_NAME,
				 "jig_facebook_section",
 				array(	'id' => 'fb_app_id',
						'label' => __('The App ID of the application you have created on FB.', 'jig_td'))
			);
			// App Secret
			add_settings_field(
				'jig_fb_app_secret',
				__('App Secret', 'jig_td'),
				array(&$this, 'jig_print_text_input'),
				self::PAGE_NAME,
				 "jig_facebook_section",
 				array(	'id' => 'fb_app_secret',
						'label' => __('The App Secret of the application you have created on FB.', 'jig_td'))
			);
			// Authorization manager for pages and profiles
			add_settings_field(
				'jig_fb_authed',
				__('Authorization manager for pages and profiles', 'jig_td'),
				array(&$this, 'jig_print_fb_authed'),
				self::PAGE_NAME,
				 "jig_facebook_section"
			);
			// Facebook caching time
			add_settings_field(
				'jig_facebook_caching',
				__('Facebook caching time', 'jig_td'),
				array(&$this, 'jig_print_text_input'),
				self::PAGE_NAME,
				"jig_facebook_section",
				array(	'id' => 'facebook_caching',
						'label' => __('in minutes: 4 hours is 240, a day is 1440, a week is 10080<br/>speeds up loading as the photos list for each album is cached<br />this is the time it takes to see the FB album change on the site', 'jig_td'))
			);
			// --------------------------------
			//        TimThumb
			// --------------------------------
			add_settings_section(
				"jig_timthumb_section",
				__('TimThumb', 'jig_td'),
				array(&$this, 'jig_print_timthumb_desc'),
				self::PAGE_NAME
			);  
			// TimThumb quality
			add_settings_field(
				'jig_quality',
				__('TimThumb quality', 'jig_td'),
				array(&$this, 'jig_print_text_input'),
				self::PAGE_NAME,
				"jig_timthumb_section",
				array(	'id' => 'quality',
						'label' => __('a number between 0 and 100, 90 is good quality', 'jig_td'))
			);

			// Custom TimThumb path
			add_settings_field(
				'jig_timthumb_path',
				__('Custom TimThumb path (leave empty if unsure)', 'jig_td'),
				array(&$this, 'jig_print_text_input'),
				self::PAGE_NAME,
				"jig_timthumb_section",
				array(	'id' => 'timthumb_path',
						'label' => __('absolute path (full URL)', 'jig_td'))
			);
			
			add_action('wp_ajax_jig_fb_auth', array(&$this, 'jig_fb_auth'));
			add_action('wp_ajax_jig_get_fb_access_token', array(&$this, 'jig_get_fb_access_token'));
			add_action('wp_ajax_jig_add_fb_page', array(&$this, 'jig_add_fb_page'));
			add_action('wp_ajax_jig_get_fb_albums', array(&$this, 'jig_get_fb_albums'));
			add_action('wp_ajax_jig_get_fb_album_cover_on_demand', array(&$this, 'jig_get_fb_album_cover_on_demand'));		
			add_action('wp_ajax_jig_verify_fb_authed', array(&$this, 'jig_verify_fb_authed'));
			add_action('wp_ajax_jig_attempt_chmod', array(&$this, 'jig_attempt_chmod'));
			add_action('wp_ajax_jig_on_demand_check_permissions', array(&$this, 'jig_on_demand_check_permissions'));
		} // end jig_init_options  

		// The sections' description
		function jig_print_general_settings_desc(){
			echo '<p>'.__('General layout/appearance settings for your Justified Image Grid galleries', 'jig_td').':</p>';  
		} 
		function jig_print_lightboxes_desc(){
			echo '<p>'.__('All settings related to lightbox (the modal gallery window that opens your images)', 'jig_td').':</p>';  
		} 
		function jig_print_captions_desc(){
			echo '<p>'.__('Settings for the text caption over the thumbnails that replaces browser tooltips', 'jig_td').':</p>';  
		} 
		function jig_print_overlay_desc(){
			echo '<p>'.__('Setup the looks of the color overlay. This is mainly used to darken/lighten the images on mouse over', 'jig_td').':</p>';  
		} 
		function jig_print_desaturate_desc(){
			echo '<p>'.__('This can turn the images to grayscale on the fly. Choose the setting that best suits your needs', 'jig_td').':</p>';  
		}
		function jig_print_facebook_desc(){
			echo '<a id="Facebook"></a><div class="long-text"><p>'.
				__("You can load photos from Facebook on the fly. This means if an album changes on Facebook, it will on your site as well. Thus the images are not copied here, but served from Facebook's CDN.", 'jig_td').
				'</p><p>'.
				__('You only need to enter the App ID and App Secret if you are accessing profiles or restricted pages. If you want to load images from a public unrestricted page, you can go ahead and just add it below (try it). In any other case you need to create a simple application on Facebook once. ', 'jig_td').
				'<a href="javascript:jig_toggle_fb_app_help();">'.
				__('How to set up an app?', 'jig_td').
				'</a></p><div id="jigFbAppHelp">'.
				'<div id="jigFbAppHelpTitle">'.__('Quick instructions for setting up a Facebook app', 'jig_td').':</div><ol>'.
				'<li>'.sprintf(__('Go to %1$s.', 'jig_td'), '<a href="https://developers.facebook.com/apps" target="_blank">Facebook Developers</a>').'</li>'.
				'<li>'.__('If this is your first time being on this page, you might need to click "Allow".', 'jig_td').'</li>'.
				'<li>'.__('Click "Create New App" in the top right corner.', 'jig_td').'</li>'.
				'<li>'.__('In the "Create New App" dialog, enter an "App Name" that is relevant to your site, then hit "Continue".', 'jig_td').'</li>'.
				'<li>'.__('You might need to complete a captcha security check and/or verify your Facebook account.', 'jig_td').'</li>'.
				'<li>'.__('Under the "Website with Facebook Login" area, add your site beginning with http or https and ending with the domain, click "Save Changes" there.', 'jig_td').'</li>'.
				'<li>'.__('Copy the "App ID" and "App Secret" fields to the appropriate fields below.', 'jig_td').'</li>'.
				'<li>'.__('Click "Save Changes" here! You are done! Now you can add your profile or restricted pages.', 'jig_td').'</li>'.
				'<li>'.__('To add yourself: Click "Add current Facebook user", then on Facebook click "Go to App". Done.', 'jig_td').'</li>'.
				'</ol></div><p>'.
				__('Please keep in mind that the authorization (for loading albumbs from user profiles or pages that require age/location verification) EXPIRES after 2 months. The gallery will show a yellow alert in the WordPress administration area on all pages one week prior to expiration or a red one if it has expired. To renew you need to authorize the user again, which is done with just one click.', 'jig_td').
				'</p><p>'.
				__("To begin, add a page below. If you set up the App ID and App Secret you can authorize yourself or another user as well. Once added you can see your authorized profiles/pages. You will be able to select an album belonging to these in the shortcode editor on a per gallery basis. Click on them to verify their status. Hit X to remove them. Don't forget to save changes!", 'jig_td').
				'</p></div>';  
		} 
		function jig_print_timthumb_desc(){
			echo '<p><a id="TimThumb" href="javascript:jig_on_demand_check_permissions()">'.
				__('Click here to check permissions to write to the cache folder. This is vital for the plugin to be working correctly.', 'jig_td').'</a></p>'; 
				echo '<p id="ttPermissionResults">'.
				sprintf(__('The permission for the cache folder is: %1$s and it seems to be %2$s. The plugin folder permission is %3$s.', 'jig_td'), '<span id="ttPermissionCache"></span>', '<span id="ttWritable"></span>', '<span id="ttPermissionPlugin"></span>').
				'<br /><br /><a href="javascript:jig_attempt_chmod(\'0755\');">'.
				__('Click here to change the permission 0755 - It should fix it in most cases.', 'jig_td').
				'</a><br /><a href="javascript:jig_attempt_chmod(\'0777\');">'.
				__('As a last resort, click to try 0777 (not recommended).', 'jig_td').
				'</a></p><p id="ttChmodFeedback"></p>';
			echo '<p>'.__('This is for advanced users/developers. The quality is fine at 90. If you use TimThumb already (by your theme) you can also point to its path. If you pull images from Facebook make sure you allow external sites.', 'jig_td').':</p>';
		}

		// Field callback functions
		function jig_print_text_input($args){
			extract($args);
			echo '<input id="'.$id.'" name="'.self::SETTINGS_NAME.'['.$id.']" type="text" value="'.$this->settings[$id].'" /><label>'.$label.'</label>';
		}
		function jig_print_textarea_input($args){
			extract($args);
			echo '<textarea cols="40" rows="'.$rows.'" id="'.$id.'" name="'.self::SETTINGS_NAME.'['.$id.']" >'.$this->settings[$id].'</textarea><label>'.$label.'</label>';
		}
		function jig_print_jquery_input(){
			$id = 'jquery';
			$output = '<label>'.__('choose where would you like to load jQuery from<br />loading from Google CDN is protocol flexible<br />change this if you experience errors<br />like captions not showing', 'jig_td').'</label>';
			$output .= '<input type="radio" name="'.self::SETTINGS_NAME.'['.$id.']" value="nochange" '.checked($this->settings[$id] , 'nochange',false).'/> '.__('No change, load the one already in use (can be outdated).', 'jig_td').' <br />';
			$output .= '<input type="radio" name="'.self::SETTINGS_NAME.'['.$id.']" value="googlewp" '.checked($this->settings[$id] , 'googlewp', false).'/> '.__('Latest from Google with local fallback to WordPress\' version.', 'jig_td').' <br />';
			$output .= '<input type="radio" name="'.self::SETTINGS_NAME.'['.$id.']" value="googleplugin" '.checked($this->settings[$id] , 'googleplugin', false).'/> '.__('Latest from Google with local fallback to the Plugin\'s version.', 'jig_td').' <br />';
			$output .= '<input type="radio" name="'.self::SETTINGS_NAME.'['.$id.']" value="plugin" '.checked($this->settings[$id] , 'plugin', false).'/> '.__('Load the one bundled with this plugin.', 'jig_td').' <br />';
			echo $output;
		}
		function jig_print_jquery_location_input(){
			$id = 'jquery_location';
			$output = '<label>'.__('most reliable when jQuery source is changed<br/>this is a sitewide setting<br/>footer would be best, however it\'s possible that<br/>some scripts get loaded in the header that would depend on jQuery', 'jig_td').'</label>';
			$output .= '<input type="radio" name="'.self::SETTINGS_NAME.'['.$id.']" value="header" '.checked($this->settings[$id] , 'header',false).'/> '.__('In the header (forced first - most compatible).', 'jig_td').' <br />';
			$output .= '<input type="radio" name="'.self::SETTINGS_NAME.'['.$id.']" value="footer" '.checked($this->settings[$id] , 'footer', false).'/> '.__('In the footer (atomatic/lazy).', 'jig_td').' <br />';
			echo $output;
		}
		function jig_print_caption_input(){
			$id = 'caption';
			$output = '<label>'.__('choose how would you like the caption to appear', 'jig_td').'</label>';
			$output .= '<input type="radio" name="'.self::SETTINGS_NAME.'['.$id.']" value="fade" '.checked($this->settings[$id] , 'fade',false).'/> '.__('Fade in/out', 'jig_td').' <br />';
			$output .= '<input type="radio" name="'.self::SETTINGS_NAME.'['.$id.']" value="slide" '.checked($this->settings[$id] , 'slide', false).'/> '.__('Slide up/down (IE7 unsupported, falls back to Fade)', 'jig_td').' <br />';
			$output .= '<input type="radio" name="'.self::SETTINGS_NAME.'['.$id.']" value="mixed" '.checked($this->settings[$id] , 'mixed', false).'/> '.__('Mixed - Tilte always visible but sliding description', 'jig_td').' <br />';
			$output .= '<input type="radio" name="'.self::SETTINGS_NAME.'['.$id.']" value="fixed" '.checked($this->settings[$id] , 'fixed', false).'/> '.__('Fixed - Whole caption is always visible', 'jig_td').' <br />';
			$output .= '<input type="radio" name="'.self::SETTINGS_NAME.'['.$id.']" value="off" '.checked($this->settings[$id] , 'off', false).'/> '.__('Off', 'jig_td');
			echo $output;
		}
		function jig_print_lightbox_input(){
			$id = 'lightbox';
			$output = '<label>'.__('decide what happens when an image is clicked', 'jig_td').'</label>';
			$output .= '<input type="radio" name="'.self::SETTINGS_NAME.'['.$id.']" value="prettyphoto" '.checked($this->settings[$id] , 'prettyphoto',false).'/> '.__("prettyPhoto (load the plugin's instance of prettyPhoto).", 'jig_td').'  <br />';
			$output .= '<input type="radio" name="'.self::SETTINGS_NAME.'['.$id.']" value="colorbox" '.checked($this->settings[$id] , 'colorbox', false).'/> '.__("ColorBox (load the plugin's instance of ColorBox).", 'jig_td').'  <br />';
			$output .= '<input type="radio" name="'.self::SETTINGS_NAME.'['.$id.']" value="custom" '.checked($this->settings[$id] , 'custom', false).'/> '.__("I already use a lightbox pugin so I'll set up the link class and/or rel accordingly.", 'jig_td').'  <br />';
			$output .= '<input type="radio" name="'.self::SETTINGS_NAME.'['.$id.']" value="no" '.checked($this->settings[$id] , 'no', false).'/> '.__("No lightbox: the image will be opened by the browser. Disables link class and rel.", 'jig_td').'  <br />';
			$output .= '<input type="radio" name="'.self::SETTINGS_NAME.'['.$id.']" value="links-off" '.checked($this->settings[$id] , 'links-off', false).'/> '.__("Turn the links off, only thumbnails. Disable pointer cursor and clickability.", 'jig_td');
			echo $output;
		}
		function jig_print_lightbox_max_size_input(){
			$id = 'lightbox_max_size';
			$output = '<label>'.__('max size of the image that loads in the lightbox', 'jig_td').'</label>';
			$output .= '<input type="radio" name="'.self::SETTINGS_NAME.'['.$id.']" value="large" '.checked($this->settings[$id] , 'large',false).'/> '.__("Large - this should be best for most cases. ", 'jig_td').'  <br />'; // If it's too small to have a 'large' image created by WP then it'll use the largest available.
			$output .= '<input type="radio" name="'.self::SETTINGS_NAME.'['.$id.']" value="full" '.checked($this->settings[$id] , 'full', false).'/> '.__("Full - it can be an overkill as it'll load the original size in the lightbox.", 'jig_td').'  <br />'; // It's your responsibility to resize the images to a web-friendly size before uploading.
			$output .= '<input type="radio" name="'.self::SETTINGS_NAME.'['.$id.']" value="medium" '.checked($this->settings[$id] , 'medium', false).'/> '.__("Medium - if you wish to limit the lightbox to a relatively small size.", 'jig_td').'  <br />';
			echo $output;
		}
		function jig_print_overlay_input(){
			$id = 'overlay';
			$output = '<label>'.__('choose a behavior for the overlay', 'jig_td').'</label>';
			$output .= '<input type="radio" name="'.self::SETTINGS_NAME.'['.$id.']" value="others" '.checked($this->settings[$id] , 'others', false).'/> '.__("Other images have colored overlay, hovered returns to normal.", 'jig_td').'  <br />';
			$output .= '<input type="radio" name="'.self::SETTINGS_NAME.'['.$id.']" value="hovered" '.checked($this->settings[$id] , 'hovered',false).'/> '.__("Hovered image has color overlay, others do not.", 'jig_td').'  <br />';
			$output .= '<input type="radio" name="'.self::SETTINGS_NAME.'['.$id.']" value="off" '.checked($this->settings[$id] , 'off', false).'/> '.__("No overlay.", 'jig_td').'  <br />';
			echo $output;
		}
		function jig_print_desaturate_input(){
			$id = 'desaturate';
			$output = '<label>'.__('choose a behavior for the on the fly grayscale effect', 'jig_td').'</label>';
			$output .= '<input type="radio" name="'.self::SETTINGS_NAME.'['.$id.']" value="off" '.checked($this->settings[$id] , 'off',false).'/> '.__("Turn desaturation effect off.", 'jig_td').'  <br />';
			$output .= '<input type="radio" name="'.self::SETTINGS_NAME.'['.$id.']" value="others" '.checked($this->settings[$id] , 'others', false).'/> '.__("Other images are desaturated, hovered returns to color.", 'jig_td').'  <br />';
			$output .= '<input type="radio" name="'.self::SETTINGS_NAME.'['.$id.']" value="hovered" '.checked($this->settings[$id] , 'hovered', false).'/> '.__("Hovered image gets desaturated, the others remain in color.", 'jig_td').'  <br />';
			$output .= '<input type="radio" name="'.self::SETTINGS_NAME.'['.$id.']" value="everything" '.checked($this->settings[$id] , 'everything', false).'/> '.__("Everything is desaturated, even on hover.", 'jig_td').'  <br />';
			echo $output;
		}
		
		function jig_print_title_field_input(){
			$id = 'title_field';
			$output = '<label>'.__('choose a WP field as title from the image details<br />choose something other than "Title" to only show those that you filled out, because Title is mandatory in WP and you might not want to display a caption on all items', 'jig_td').'</label>';
			$output .= '<input type="radio" name="'.self::SETTINGS_NAME.'['.$id.']" value="title" '.checked($this->settings[$id] , 'title', false).'/> '.__("Title", 'jig_td').'  <br />';
			$output .= '<input type="radio" name="'.self::SETTINGS_NAME.'['.$id.']" value="description" '.checked($this->settings[$id] , 'description',false).'/> '.__("Description", 'jig_td').'  <br />';
			$output .= '<input type="radio" name="'.self::SETTINGS_NAME.'['.$id.']" value="caption" '.checked($this->settings[$id] , 'caption', false).'/> '.__("Caption", 'jig_td').'  <br />';
			$output .= '<input type="radio" name="'.self::SETTINGS_NAME.'['.$id.']" value="alternate" '.checked($this->settings[$id] , 'alternate', false).'/> '.__("Alternate Text", 'jig_td').'  <br />';
			echo $output;
		}
		function jig_print_caption_field_input(){
			$id = 'caption_field';
			$output = '<label>'.__('choose a WP field as caption description from the image details', 'jig_td').'</label>';
			$output .= '<input type="radio" name="'.self::SETTINGS_NAME.'['.$id.']" value="title" '.checked($this->settings[$id] , 'title', false).'/> '.__("Title", 'jig_td').'  <br />';
			$output .= '<input type="radio" name="'.self::SETTINGS_NAME.'['.$id.']" value="description" '.checked($this->settings[$id] , 'description',false).'/> '.__("Description", 'jig_td').'  <br />';
			$output .= '<input type="radio" name="'.self::SETTINGS_NAME.'['.$id.']" value="caption" '.checked($this->settings[$id] , 'caption', false).'/> '.__("Caption", 'jig_td').'  <br />';
			$output .= '<input type="radio" name="'.self::SETTINGS_NAME.'['.$id.']" value="alternate" '.checked($this->settings[$id] , 'alternate', false).'/> '.__("Alternate Text", 'jig_td').'  <br />';
			echo $output;
		}
		function jig_print_link_title_field_input(){
			$id = 'link_title_field';
			$output = '<label>'.__('choose a WP field as link title from the image details', 'jig_td').'</label>';
			$output .= '<input type="radio" name="'.self::SETTINGS_NAME.'['.$id.']" value="description" '.checked($this->settings[$id] , 'description',false).'/> '.__("Description", 'jig_td').'  <br />';
			$output .= '<input type="radio" name="'.self::SETTINGS_NAME.'['.$id.']" value="title" '.checked($this->settings[$id] , 'title', false).'/> '.__("Title", 'jig_td').'  <br />';
			$output .= '<input type="radio" name="'.self::SETTINGS_NAME.'['.$id.']" value="caption" '.checked($this->settings[$id] , 'caption', false).'/> '.__("Caption", 'jig_td').'  <br />';
			$output .= '<input type="radio" name="'.self::SETTINGS_NAME.'['.$id.']" value="alternate" '.checked($this->settings[$id] , 'alternate', false).'/> '.__("Alternate Text", 'jig_td').'  <br />';
			echo $output;
		}
		function jig_print_img_alt_field_input(){
			$id = 'img_alt_field';
			$output = '<label>'.__('choose a WP field as img alt from the image details', 'jig_td').'</label>';
			$output .= '<input type="radio" name="'.self::SETTINGS_NAME.'['.$id.']" value="title" '.checked($this->settings[$id] , 'title',false).'/> '.__("Title", 'jig_td').'  <br />';
			$output .= '<input type="radio" name="'.self::SETTINGS_NAME.'['.$id.']" value="description" '.checked($this->settings[$id] , 'description', false).'/> '.__("Description", 'jig_td').'  <br />';
			$output .= '<input type="radio" name="'.self::SETTINGS_NAME.'['.$id.']" value="caption" '.checked($this->settings[$id] , 'caption', false).'/> '.__("Caption", 'jig_td').'  <br />';
			$output .= '<input type="radio" name="'.self::SETTINGS_NAME.'['.$id.']" value="alternate" '.checked($this->settings[$id] , 'alternate', false).'/> '.__("Alternate Text", 'jig_td').'  <br />';
			echo $output;
		}
		function jig_print_last_row_input(){
			$id = 'last_row';
			$output = '<label>'.__('the last row is not always full - choose how to handle it<br />if incomplete, the last row will try to fit the available width until max height (row height+deviation) is reached, otherwise the incomplete last row will be visible and as tall as the row height setting', 'jig_td').'</label>';
			$output .= '<input type="radio" name="'.self::SETTINGS_NAME.'['.$id.']" value="normal" '.checked($this->settings[$id] , 'normal',false).'/> '.__("Normal - try to fill width OR fall back to target height (visibly incomplete)", 'jig_td').'  <br />';
			$output .= '<input type="radio" name="'.self::SETTINGS_NAME.'['.$id.']" value="hide" '.checked($this->settings[$id] , 'hide', false).'/> '.__("Hide the incomplete row (the gallery forms a perfect justified block)", 'jig_td').'  <br />';
			$output .= '<input type="radio" name="'.self::SETTINGS_NAME.'['.$id.']" value="match" '.checked($this->settings[$id] , 'match', false).'/> '.__("Match the previous row's height (use for same shape images e.g. logo showcase)", 'jig_td').'  <br />';
			echo $output;
		}
	
		function jig_print_orderby_input(){
			$id = 'orderby';
			$output = '<label>'.__('choose the order the images appear in, only when images are from WordPress', 'jig_td').'</label>';
			$output .= '<input type="radio" name="'.self::SETTINGS_NAME.'['.$id.']" value="menu_order" '.checked($this->settings[$id] , 'menu_order',false).'/> '.__("Menu order", 'jig_td').'  <br />';
			$output .= '<input type="radio" name="'.self::SETTINGS_NAME.'['.$id.']" value="rand" '.checked($this->settings[$id] , 'rand', false).'/> '.__("Random", 'jig_td').'  <br />';
			$output .= '<input type="radio" name="'.self::SETTINGS_NAME.'['.$id.']" value="title_asc" '.checked($this->settings[$id] , 'title_asc', false).'/> '.__("Title ascending", 'jig_td').'  <br />';
			$output .= '<input type="radio" name="'.self::SETTINGS_NAME.'['.$id.']" value="title_desc" '.checked($this->settings[$id] , 'title_desc', false).'/> '.__("Title descending", 'jig_td').'  <br />';
			$output .= '<input type="radio" name="'.self::SETTINGS_NAME.'['.$id.']" value="date_asc" '.checked($this->settings[$id] , 'date_asc', false).'/> '.__("Date ascending", 'jig_td').'  <br />';
			$output .= '<input type="radio" name="'.self::SETTINGS_NAME.'['.$id.']" value="date_desc" '.checked($this->settings[$id] , 'date_desc', false).'/> '.__("Date descending", 'jig_td').'  <br />';
			echo $output;
		}
		function jig_print_mouse_disable_input(){
			$id = 'mouse_disable';
			$output = '<label>'.__('choose yes if you wish to disable right click menu', 'jig_td').'</label>';
			$output .= '<input type="radio" name="'.self::SETTINGS_NAME.'['.$id.']" value="no" '.checked($this->settings[$id] , 'no',false).'/> '.__("No", 'jig_td').'  <br />';
			$output .= '<input type="radio" name="'.self::SETTINGS_NAME.'['.$id.']" value="yes" '.checked($this->settings[$id] , 'yes', false).'/> '.__("Yes", 'jig_td').'  <br />';
			echo $output;
		}
		function jig_print_error_checking_input(){
			$id = 'error_checking';
			$output = '<label>'.__('yes to hide unloadable images from the grid / no to show all', 'jig_td').'</label>';
			$output .= '<input type="radio" name="'.self::SETTINGS_NAME.'['.$id.']" value="yes" '.checked($this->settings[$id] , 'yes',false).'/> '.__("Yes", 'jig_td').'  <br />';
			$output .= '<input type="radio" name="'.self::SETTINGS_NAME.'['.$id.']" value="no" '.checked($this->settings[$id] , 'no', false).'/> '.__("No", 'jig_td').'  <br />';
			echo $output;
		}
		function jig_print_fb_authed(){
			$id = 'fb_authed';
			$hidden = '';
			$output = '<div id="jigFbWrapper">
							<div id="jigFbLoadingAJAX">
								<div id="jigFbLoadingInner">'.
									__('communicating with Facebook', 'jig_td').
									'<br /><span id="jigFbLoadingInnerSmallText">'.__('please be patient, it takes a moment', 'jig_td').
									'</span>						
									<div id="jigFbIcon"></div>
								</div>
							</div>
							<div id="jigFb">';
			$output .= '<div id="jigFbAuthed" class="clearfix">';
			$output .= '<div id="jigFbAuthedPrototype" class="jigFbAuthedElement"><div class="jigFbAuthedName"></div><div class="jigFbAccessFrom"></div><div class="jigFbAuthedRemove">X</div></div>';
			$output .= '<div id="jigFbEmptyList">'.__('No pages or profiles added yet.', 'jig_td').'</div>';
			if($this->settings[$id] != ''){
				foreach ($this->settings[$id] as $key => $val){
					$expired = '';
					if(isset($val['access_token_owner_id'])){
						if($this->settings[$id][$val['access_token_owner_id']]['time_added']+$this->settings[$id][$val['access_token_owner_id']]['expires'] < time()){
							$expired = ' fbExpirdRedAlert';
						}
					}else if(isset($val['time_added'])){
						if($val['time_added']+$val['expires'] < time() ){
							$expired = ' fbExpirdRedAlert';
						}
					}
					
					$output .= '<div id="fbAuthed'.$val['user_id'].'" data-access-token="'.$val['access_token'].'" class="jigFbAuthedElement'.$expired.'" data-type="'.$val['type'].'"><div class="jigFbAuthedName">'.$val['user_name'].'</div><div class="jigFbAccessFrom">'.(isset($val['access_token_owner_name']) ? '<div class="jigFbAccessFromInner">(via  '.$val['access_token_owner_name'].')</div>' : '').'</div><div class="jigFbAuthedRemove">X</div></div>';
					$hidden .= '<div id="fbField'.$val['user_id'].'">';
					foreach ($val as $k => $v){
						$hidden .= '<input class="jig_fb_field_'.$k.'" type="hidden" name="'.self::SETTINGS_NAME.'['.$id.']['.$val['user_id'].']['.$k.']" value="'.$v.'" />';
					}
					$hidden .= '</div>';
				} 
			}
			$output .= '</div><div id="jigFbLeft">';
			$output .= '<div id="jigAddFbPage">'.__('Add a new  page', 'jig_td').'<input type="text" id="jigAddFbPageInput" value="" /></div><div class="clearboth"></div><div id="jigFbWithAppOnly">';

			$output .= '<a id="jigFbAuthRequest" href="'.admin_url('admin-ajax.php').'?action=jig_fb_auth" target="_blank"><div id="jigFbAuthBtn">'.__('Add current Facebook user', 'jig_td').'</div></a><div class="clearboth"></div>';

			$output .= '<div id="jigFbAuthManualBtn" class="jig_disable">'.__('Manually load Facebook data', 'jig_td').'</div><div class="clearboth"></div>';

			$output .= '<div id="jigFbAuthOtherUserPanelTitle">'.__('To add other user, complete the steps below', 'jig_td').':</div>';

			$output .= '<div id="jigFbAuthOtherUserPanel">
							<span>1. '.__('Send this link to the other user', 'jig_td').':</span><br />
							<input type="text" id="jigFbOtherUserLink" value="" data-force="https://www.facebook.com/dialog/oauth?client_id='.$this->settings['fb_app_id'].'&scope=user_photos&redirect_uri='.urlencode(plugins_url('fb-auth-other-user.php', __FILE__)).'" /><br />
							<span>2. '.__('Enter the code the user received', 'jig_td').':</span><br />
							<input type="text" id="jigFbOtherUserCode" value="" /><br />
							<div id="jigFbOtherUserLoad">'.__('Add other user', 'jig_td').'</div>
							<div class="clearboth"></div>
						</div>';

			$output .= '</div></div><div id="jigFbRight"><div id="jigFbAuthLogWrapper"><div id="jigFbAuthLogTitle">Message log:</div><div id="jigFbAuthLog"></div></div>';
			$output .= '<div id="jigFbAuthedHidden" data-name="'.self::SETTINGS_NAME.'['.$id.']">'.$hidden.'</div>';
			$output .= '</div><div class="clearboth"></div></div>';
			echo $output;
		}

		

		public function add_from_template_tag($atts){
			$this->template_tag = true;
			return $this->jig_init_shortcode($atts);
		}
		// the main function which is attached to a shortcode
		// prints inline CSS and JS + enqueues CSS and JS
		function jig_init_shortcode($atts){
			global $justified_image_grid_instance;
			$justified_image_grid_instance++;
			$jig_id = $justified_image_grid_instance;
			global $post;  
			extract(shortcode_atts(array(
				"preset" => NULL
			), $atts));
			if(isset($preset)){
				$this->settings = array_merge(array_merge($this->defaults, $this->settings_override), $this->presets[$preset]);
			}
			extract(shortcode_atts(array(
				"thumbs_spacing" => $this->settings['thumbs_spacing'],
				"row_height" => $this->settings['row_height'],
				"animation_speed" => $this->settings['animation_speed'],			
				"height_deviation" => $this->settings['height_deviation'],
				"limit" => $this->settings['limit'],
				"max_rows" => $this->settings['max_rows'],
				"last_row" => $this->settings['last_row'],
				"orderby" => $this->settings['orderby'],			
				"link_class" => $this->settings['link_class'],
				"link_rel" => $this->settings['link_rel'],	
				"link_title_field" => $this->settings['link_title_field'],
				"img_alt_field" => $this->settings['img_alt_field'],
				"title_field" => $this->settings['title_field'],
				"caption_field" => $this->settings['caption_field'],
				"caption" => $this->settings['caption'],
				"caption_opacity" => $this->settings['caption_opacity'],
				"caption_bg_color" => $this->settings['caption_bg_color'],
				"caption_text_color" => $this->settings['caption_text_color'],
				"caption_text_shadow" => $this->settings['caption_text_shadow'],
				"overlay" => $this->settings['overlay'],
				"overlay_color" => $this->settings['overlay_color'],
				"overlay_opacity" => $this->settings['overlay_opacity'],
				"desaturate" => $this->settings['desaturate'],
				"lightbox" => $this->settings['lightbox'],
				"lightbox_max_size" => $this->settings['lightbox_max_size'],
				"min_height" => $this->settings['min_height'],
				"margin" => $this->settings['margin'],
				"timthumb_path" => $this->settings['timthumb_path'],
				"quality" => $this->settings['quality'],
				"mouse_disable" => $this->settings['mouse_disable'],
				"error_checking" => $this->settings['error_checking'],		
				"id" => $post->ID,
				"exclude" => '',
				"include" => '',
				"facebook_id" => '',
				"facebook_album" => '',
				"facebook_caching" => $this->settings['facebook_caching']
			), $atts));
			$facebook = false;
			if($facebook_id && $facebook_album){
				$facebook = true;
			}
			if(!$facebook){
				$order = 'ASC';
				switch($orderby){
					case 'title_asc':
						$orderby = 'title';
					break;
					case 'title_desc':
						$orderby = 'title';
						$order = 'DESC';
					break;
					case 'date_desc':
						$orderby = 'date';
						$order = 'DESC';
					break;
					case 'date_asc':
						$orderby = 'date';
					break;
					default:
				}
				if($limit == '' || $limit == 0){
					$limit = -1;
				}
				// build the image list json object for JS
				$args = array(
					'post_parent'		=> $id,				// From this or that post,
					'post_type'			=> 'attachment',	// get attachments  
					'post_mime_type'	=> 'image',			// but only images (partial mime type),
					'order'				=> $order,			// in ascending/descending order of
					'orderby'			=> $orderby,		// the set or the default: menu order (this is the order you set up with drag n drop, it IS available for image attachements)
					'post_status'		=> null, 			// for any status. 
					'exclude'			=> $exclude,
					'include'			=> $include,
					'numberposts'		=> $limit
				); 
				$attachments = get_posts($args); // Fetch the images with a WP query
				if ($attachments){ // If there are images attached to the post  
					$this->images = array(); // Create a new array for the images
					foreach ($attachments as $attachment){ // Loop through each
						$image = wp_get_attachment_image_src($attachment->ID, $lightbox_max_size); // Get URL [0], width [1], and height [2]
						if($image[1] != 0 && $image[2] != 0){// If none of the dimensions are 0
							$data = $d = array(); // Create 2 arrays for this image one temporary and one that gets pushed
							$data['url'] = $image[0]; // Store the full URL value		
							$data['width'] = floor($image[1]/$image[2]*($row_height+$height_deviation)); // Calculate new width of TimThumb by getting the ratio and multiplying it with the set row height
							// Get title
							$d['title'] =  trim(strip_tags($attachment->post_title));
							if($d['title'] != '') $data['title'] = $d['title'];
							// Get caption
							$d['caption'] =  trim(strip_tags($attachment->post_excerpt));
							if($d['caption'] != '') $data['caption'] = $d['caption'];
							// Get description
							$d['description'] =  trim(strip_tags($attachment->post_content));
							if($d['description'] != '') $data['description'] = $d['description'];
							// Get alternate
							$d['alternate'] =  trim(strip_tags(get_post_meta($attachment->ID, '_wp_attachment_image_alt', true)));
							if($d['alternate'] != '') $data['alternate'] = $d['alternate'];
							// Get link
							$d['link'] = trim(strip_tags(get_post_meta($attachment->ID, '_jig_image_link', true)));
							if($d['link'] != '') $data['link'] = $d['link'];
							// Add to the main images array
							array_push($this->images, $data); 
						}
					}
				}else{
					return sprintf(__('Post %1$s does not have any attached images!', 'jig_td'),$id);
				}
			}else{
				if(isset($this->settings['fb_authed'][$facebook_id])){
					$user = $this->settings['fb_authed'][$facebook_id];
					if($limit != '' || $limit != 0){
						$limit = "&limit=".$limit;
					}
					$photos_url = "https://graph.facebook.com/".$facebook_album."/photos?fields=source,height,width,name".$limit.($user['access_token'] != 'public' ? '&access_token='.$user['access_token'] : '');
					if($facebook_caching > 0){
						if(get_transient('jigfb_'.md5($photos_url.$facebook_caching)) == true){
							$photos = get_transient('jigfb_'.md5($photos_url.$facebook_caching));
						}else{
							$photos = json_decode(file_get_contents($photos_url));
							set_transient('jigfb_'.md5($photos_url.$facebook_caching), $photos, 60 * $facebook_caching);
						}			
					}else{
						$photos = json_decode(file_get_contents($photos_url));
					}
					if(!empty($photos->data)){
						$this->images = array(); // Create a new array for the images
						foreach ($photos->data as $image) {
							$data = array(); // Create a new array for this image
							$data['url'] = $image->source; // Store the full URL value
							if(isset($image->name)){
								$data['title'] = $image->name;
							}
							$data['width'] = floor($image->width/$image->height*($row_height+$height_deviation)); // Calculate new width of TimThumb by getting the ratio and multiplying it with the set row height
							array_push($this->images, $data); // Add to the main images array

						}
					}else{
						return __('The requested album cannot be loaded at this time.', 'jig_td');
					}

				}else{
					return __('That Facebook ID is unauthorized for use, please go to Settings and add it.', 'jig_td');
;
				}
			}

			if($link_rel){
				$link_rel = strtr($link_rel, array("(" => "[", ")" => "]"));
			}
			$overlay_CSS = '';
			if($overlay != 'off'){
				$overlay_appearance_CSS = '';
				if($overlay == 'hovered'){
					$overlay_appearance_CSS .='display:none;';
				}
				$overlay_CSS = 
						"#jig{$jig_id} .jig-overlay {
							background:{$overlay_color};
							opacity: {$overlay_opacity};
							-moz-opacity: {$overlay_opacity};
							filter:alpha(opacity=".($overlay_opacity*100).");
							height:100%;
						}
						#jig{$jig_id} .jig-overlay-wrapper {
							{$overlay_appearance_CSS}
							position: absolute;
							bottom: 0;
							left: 0;
							right: 0;
							top: 0;
						}";
			}

			$caption_CSS = '';
			if($caption != 'off'){
				$caption_appearance_CSS = $caption_desc_appearance_CSS = '';
				if($caption == 'slide' || $caption == 'fade'){
					$caption_appearance_CSS = 'display:none;';
				}
				if($caption == 'mixed'){
					$caption_desc_appearance_CSS = "#jig{$jig_id} .jig-caption-description-wrapper {display:none;}";
				}
				$caption_CSS = 
						"#jig{$jig_id} .jig-caption-wrapper {
							bottom: 0;
							right: 0;
							left: 0;
							position: absolute;
							margin:0;
							padding:0;
							z-index:100;
							overflow:hidden;
							opacity: {$caption_opacity};
							-moz-opacity: {$caption_opacity};
							filter:alpha(opacity=".($caption_opacity*100).");
						}
						#jig{$jig_id} .jig-caption {
							{$caption_appearance_CSS}
							margin: 0;
							padding:0 7px;
							background: {$caption_bg_color}; ".($caption_opacity == 1 && $caption_text_shadow != '' ? 'text-shadow: '.$caption_text_shadow.';' : '')."
						}
						#jig{$jig_id} .jig-caption-title {
							padding:5px 0 3px;
							overflow: hidden;
							color:{$caption_text_color};
							".$this->settings['caption_title_css']."
						}
						{$caption_desc_appearance_CSS}
						#jig{$jig_id} .jig-caption-description {
							padding-bottom: 5px;
							margin-top: -3px;
							overflow: hidden;
							color:{$caption_text_color};
							".$this->settings['caption_desc_css']."
						}
						#jig{$jig_id} .jig-alone{
							padding-top:5px;
							margin-top: 0;
						}";
			}
			$output = "<style type='text/css'>
						#jig{$jig_id} {
							padding:0px;
							margin:{$margin};
							min-height:{$min_height}px;
						}
						#jig{$jig_id} img, #jig{$jig_id} .jig-desaturated {
							position:absolute;
							top:0;
							left:0;
							margin: 0;
							padding: 0 !important;
							border-style: none !important;
							vertical-align: baseline;
							max-width:none;
							max-height:none;
							border-radius: 0 !important;
							box-shadow: none !important;
							z-index: auto !important;
						}
						#jig{$jig_id} .jig-imageContainer {
							margin-right: {$thumbs_spacing}px;
							margin-bottom: {$thumbs_spacing}px;
							-webkit-user-select: none;
							float: left;	
							padding: 0px;
						}
						#jig{$jig_id} .jig-imageContainer a {
							margin: 0px !important;
							padding: 0px !important;
							position: static !important;
							display: inline;
						}
						#jig{$jig_id} .jig-overflow {
							position: relative; 
							overflow:hidden;
							vertical-align:baseline;
						}
						#jig{$jig_id} a:link, #jig{$jig_id} a:hover, #jig{$jig_id} a:visited {
							text-decoration:none;
						}
						#jig{$jig_id} .jig-removeThis {
							visibility:hidden;
						}
						{$caption_CSS}
						{$overlay_CSS}					
						#jig{$jig_id} .jig-clearfix:before, #jig{$jig_id} .jig-clearfix:after { content: ''; display: table; }
						#jig{$jig_id} .jig-clearfix:after { clear: both; }
						#jig{$jig_id} .jig-clearfix { zoom: 1; }						
					</style>
					".$this->jig_rgbaIE($caption_bg_color);
			$output .= '<div id="jig'.$jig_id.'"><div class="jig-clearfix"></div></div>';
			wp_enqueue_script('jquery');
			$lightbox_JS = "function addLightbox{$jig_id}(){return}";
			if($lightbox == 'prettyphoto'){
				wp_deregister_script('prettyphoto');
				wp_register_script("prettyphoto", plugins_url('js/jquery.prettyPhoto.js', __FILE__), 'jquery', '3.1.4.2', true);
				wp_enqueue_script("prettyphoto");
				wp_register_style('prettyphoto-style', plugins_url('css/prettyPhoto.css', __FILE__), false, '3.1.4.2');
				wp_enqueue_style('prettyphoto-style');
				$lightbox_JS = "function addLightbox{$jig_id}(){
									$('#jig{$jig_id} a').not('.jig-customLink').prettyPhoto({
										".$this->settings['prettyphoto_settings']."
									});
								}";
			}
			if($lightbox == 'colorbox'){
				wp_deregister_script('colorbox');
				wp_register_script("colorbox", plugins_url('js/jquery.colorbox-min.js', __FILE__), 'jquery', '1.3.19', true);
				wp_enqueue_script("colorbox");
				wp_register_style('colorbox-style', plugins_url('css/colorbox.css', __FILE__), false, '1.3.19');
				wp_enqueue_style('colorbox-style');
				$lightbox_JS = "function addLightbox{$jig_id}(){
									$('#jig{$jig_id} a').not('.jig-customLink').colorbox({
										".$this->settings['colorbox_settings']."
									});
								}";
			}
			$mouse_JS = '';
			if($mouse_disable == 'yes'){
				$mouse_JS = "$('#jig{$jig_id}').on('contextmenu', function(e){
								e.preventDefault();
								return false;
							});";
				if($lightbox == 'colorbox'){
					$mouse_JS .= '$("body").on("contextmenu", "#colorbox", function(e){
									e.preventDefault();
									return false;
								});';
				}

			}
			$instance_js = "{$lightbox_JS}
							$('#jig{$jig_id}').justifiedImageGrid({
								targetHeight: {$row_height},
								heightDeviation: {$height_deviation},
								margins: {$thumbs_spacing},
								animSpeed: {$animation_speed},
								items: ".json_encode($this->images).",
								maxRows: ".($max_rows != '' ? $max_rows : "''").",
								linkClass: '{$link_class}',
								linkRel: '{$link_rel}',
								linkTitleField: '{$link_title_field}',
								imgAltField: '{$img_alt_field}',
								timthumb: '".($timthumb_path ? $timthumb_path : plugins_url('timthumb.php', __FILE__))."',
								quality: {$quality},
								caption: '{$caption}',
								titleField: '{$title_field}',
								captionField: '{$caption_field}',
								lightbox: '{$lightbox}',
								lightboxInit: addLightbox{$jig_id},
								overlay: '{$overlay}',
								desaturate: '{$desaturate}',
								incompleteLastRow: '{$last_row}',
								errorChecking: '{$error_checking}',
								instance: {$jig_id}
							});
							var resizeTO{$jig_id} = false;
							$(window).resize(function(){
								if(resizeTO{$jig_id} !== false){
									clearTimeout(resizeTO{$jig_id});
									}
								resizeTO{$jig_id} = setTimeout(function(){
									$('#jig{$jig_id}').data('justifiedImageGrid').createGallery('resize');
								}, 100); 
							});
							{$mouse_JS}";
			if($this->template_tag == false){
				global $justified_image_grid_js;
				$justified_image_grid_js .= $instance_js;
				$js_print = $justified_image_grid_js;
			}else{
				$js_print = $instance_js;
			}							
			$this->dynamic_script = "<script type='text/javascript'>
									(function($){
										$js_print
									})(jQuery);
									</script>";
			add_action('wp_print_footer_scripts', array(&$this, 'jig_print_script'), 100);
			if($desaturate != 'off'){
				wp_enqueue_script('pixastic.custom.desaturate', plugins_url('js/pixastic.custom.desaturate.js', __FILE__), 'jquery', null, true);
			}
			wp_enqueue_script('jig', plugins_url('js/justified-image-grid-min.js', __FILE__), 'jquery', '1.2', true);
			return $output;
		}// end of jig_init_shortcode

		// print the dynamic inline JS at the end of the footer scripts
		function jig_print_script(){
			echo $this->dynamic_script;
		}

		// registers the buttons for use
		function register_jig_shortcode_editor($buttons){
			array_push($buttons, "|", "jig_shortcode_editor");
			return $buttons;
		}	 
		
		// adds the button to the tinyMCE bar
		function add_jig_shortcode_editor($plugin_array){
			$plugin_array['jig_shortcode_editor'] = plugins_url('js/jig-shortcode-editor.js', __FILE__);
			return $plugin_array;
		}

		// loads the shortcode editor this way because of the translation of the strings
		function jig_shortcode_editor(){
			include 'jig-shortcode-editor.php';
			die();
		}

		// loads the FB auth page with ajaxurl, sets up a session if valid
		function jig_fb_auth(){
			$app_id = $this->settings['fb_app_id'];
			$app_secret = $this->settings['fb_app_secret'];
			$my_url = admin_url('admin-ajax.php').'?action=jig_fb_auth';
			session_start();
			if(empty($_REQUEST["code"])) {
				$_SESSION['state'] = md5(uniqid(rand(), TRUE)); //CSRF protection
				$dialog_url = "https://www.facebook.com/dialog/oauth?client_id=" 
				. $app_id . "&scope=user_photos&redirect_uri=" . urlencode($my_url) . "&state="
				. $_SESSION['state'];
				echo("<script> top.location.href='" . $dialog_url . "'</script>");
				die();
			}

			if($_SESSION['state'] && ($_SESSION['state'] === $_REQUEST['state'])) {
				$token_url = "https://graph.facebook.com/oauth/access_token?"
				. "client_id=" . $app_id . "&redirect_uri=" . urlencode($my_url)
				. "&client_secret=" . $app_secret . "&code=" . $_REQUEST["code"];

				$response = file_get_contents($token_url);
				$params = null;
				parse_str($response, $params);

				$graph_url = "https://graph.facebook.com/me?access_token=" 
				. $params['access_token'];

				$user = json_decode(file_get_contents($graph_url));
			     $_SESSION['fb_details'] = array(	'access_token' => $params['access_token'],
			     									'user_name' => $user->name,
			     									'expires' => $params['expires'],
			     									'time_added' => time(),
			     									'user_id' => $user->id,
			     									'type' => 'current_user');
				$_SESSION['fb_details']['info']['expires'] = $params['expires'];
				$_SESSION['fb_details']['info']['time_added'] =  $_SESSION['fb_details']['time_added'];
				$_SESSION['fb_details']['info']['time_remaining'] = $this->jig_time_left( $_SESSION['fb_details']['time_added']+$params['expires']);
			    $albums_url = "https://graph.facebook.com/me/albums?fields=id,link,count,from&limit=1000&access_token=".$params['access_token'];
				$albums = json_decode(file_get_contents($albums_url));
				// if there is album data
				if(!empty($albums->data)){
					$found = 0;
					foreach ($albums->data as $key => $value) {
						if(!empty($value->count) && !empty($value->link)){
							$found++;
						}
					}
					if($found > 0){
						$_SESSION['fb_details']['info']['album_count'] = $found.' '._n('album', 'albums', $found, 'jig_td');
					}else{
						$_SESSION['fb_details']['info']['album_count'] = __('no albums so far, start adding some', 'jig_td');
					}
				}else{
					$_SESSION['fb_details']['info']['album_count'] = __('no albums so far, start adding some', 'jig_td');
				}
				echo("<script> window.close(); </script>");
			} else {
				 _e("The state does not match. You may be a victim of CSRF.", 'jig_td');
			}
		   die();
		}

		// used for Facebook auth: gets access token, name, and expiry from the session
		function jig_get_fb_access_token(){
			check_ajax_referer('jig_get_fb_access_token', 'security');
			$output = array();
			$code = $_REQUEST['code'];
			if($code == 'current'){
					if ( !session_id() )
						session_start();
					if(isset($_SESSION['fb_details'])){
						$output = $_SESSION['fb_details'];
						unset($_SESSION['fb_details']);
					}else{
						$output = array('error' => __("Access token acquisition wasn't successful. Please authorize yourself on Facebook then click 'Manually load Facebook data'. If you already closed the Facebook dialog, click 'Add current Facebook user' again.", 'jig_td'));
					}
			}else{
				$token_url = "https://graph.facebook.com/oauth/access_token?"
				. "client_id=" . $this->settings['fb_app_id'] . "&redirect_uri=" . urlencode(plugins_url('fb-auth-other-user.php', __FILE__))
				. "&client_secret=" . $this->settings['fb_app_secret'] . "&code=" . base64_decode($code);

				$response = file_get_contents($token_url);
				$params = null;
				parse_str($response, $params);
				if(isset($params['access_token'])){
					$graph_url = "https://graph.facebook.com/me?access_token=" 
					. $params['access_token'];
					$user = json_decode(file_get_contents($graph_url));

				    $output = array(	'access_token' => $params['access_token'],
	 									'user_name' => $user->name,
	 									'expires' => $params['expires'],
	 									'time_added' => time(),
	 									'user_id' => $user->id,
	 									'type' => 'other_user');
					$output['info']['expires'] = $params['expires'];
					$output['info']['time_added'] = $output['time_added'];
					$output['info']['time_remaining'] = $this->jig_time_left($output['time_added']+$params['expires']);
					$albums_url = "https://graph.facebook.com/me/albums?fields=id,link,count,from&limit=1000&access_token=".$params['access_token'];
					$albums = json_decode(file_get_contents($albums_url));
					// if there is album data
					if(!empty($albums->data)){
						$found = 0;
						foreach ($albums->data as $key => $value) {
							if(!empty($value->count) && !empty($value->link)){
								$found++;
							}
						}
						if($found > 0){
							$output['info']['album_count'] = $found.' '._n('album', 'albums', $found, 'jig_td');
						}else{
							$output['info']['album_count'] = __('no albums so far, start adding some', 'jig_td');
						}
					}else{
						$output['info']['album_count'] = __('no albums so far, start adding some', 'jig_td');
					}
				}else{
					$output = array('error' => __('Invalid code!', 'jig_td'));
				}
			}
			echo json_encode($output);
			die();
		}

		// adds a Facebook page
		function jig_add_fb_page($token = '', $user_name = ''){
			check_ajax_referer('jig_add_fb_page', 'security');
			$output = array();
			$page = $_REQUEST['page'];		
			if($token == '' && $_REQUEST['token'] != ''){
				$token = $this->settings['fb_authed'][$_REQUEST['token']]['access_token'];
				$user_name = $this->settings['fb_authed'][$_REQUEST['token']]['user_name'];
			}
			if($page != ''){
				$albums_url = "https://graph.facebook.com/".$page."/albums?fields=id,link,count,from&limit=1000".($token != '' ? '&access_token='.$token : '');
				$albums = json_decode(file_get_contents($albums_url));
				// if there is album data
				if(!empty($albums->data)){
					$found = 0;
					foreach ($albums->data as $key => $value) {
						if(!empty($value->count) && !empty($value->link)){
							$found++;
						}
					}
					if($found > 0){
						$output = array(	
	     									'user_name' => $albums->data[0]->from->name,
	     									'user_id' => $albums->data[0]->from->id,
	     									'access_token' => 'public',
											'type' => 'page');
						$output['info']['album_count'] = $found.' '._n('album', 'albums', $found, 'jig_td');	
						if($token != ''){
							$output['access_token'] = $token;
							foreach($this->settings['fb_authed'] as $key => $val){
								if($val['access_token'] === $token && $val['type'] != 'page'){
									$output['access_token'] = $token;
									$output['access_token_owner_name'] = $val['user_name'];
									$output['access_token_owner_id'] = $val['user_id'];
									$output['info']['expires'] = $val['expires'];
									$output['info']['time_added'] = $val['time_added'];
									$output['info']['time_remaining'] = $this->jig_time_left($val['time_added']+$val['expires']);
									$output['info']['owner_type'] = $val['type'];	
									break;
								}
							}
						} 
						
					}else{
						$output = array('error' => __('No pictures in any album.', 'jig_td'));
					}
				}else{ // find out the reason why album data is missing
					$main_url = "https://graph.facebook.com/".$page.($token != '' ? '?access_token='.$token : '');
					$main = json_decode(file_get_contents($main_url));
					if($main === false){
						$options = array();
						if(isset($this->settings['fb_authed'])){
							$last_token = '';
							$last_user_name = '';
							if(!empty($this->settings['fb_authed'])){
								foreach($this->settings['fb_authed'] as $key => $val){
									if($token != '' ){
										break;
									}
									if($val['type'] == 'page'){
										continue;
									}

									if($val['time_added']+$val['expires'] < time() ){
										continue;
									}
									$options[] = '<a href="javascript:jig_add_fb_page('.$val['user_id'].');">'.$val['user_name'].'</a> ';
									$last_token = $val['access_token'];
									$last_user_name = $val['user_name'];
								}
							}
							$options_count = count($options);
							if($options_count == 1){
								$this->jig_add_fb_page($last_token,$last_user_name);
							}else if($options_count == 0){
								if($token != '' ){
									$output = array('error' => sprintf(__('Demographically or geographically blocked for %1$s, maybe try again with another user.', 'jig_td'), $user_name));
								}else{
									$output = array('error' => __('Demographically or geographically blocked. Add a user first then try again.', 'jig_td'));
								}
							}else{
								$output = array('error' => __('Choose a user to access this page with', 'jig_td').' '.implode(" ".__('or', 'jig_td')." ", $options));
							}
						}
					}else{
						if(!empty($main->first_name)){
							$output = array('error' => __('Private user profile, use authorize other user instead.', 'jig_td'));
						}else if(!empty($main->error->message)){
							$output = array('error' => __('Invalid access/expired, please re-authenticate! The error from Facebook', 'jig_td').': '.$main->error->message);
						}else{
							$output = array('error' => __('No albums at all.', 'jig_td'));
						}					
					}
				}
			}else{
				$output = array('error' => __('Invalid page/not recognized.', 'jig_td'));
			}
			echo json_encode($output);
			die();
		}

		// verifies the status of authed FB items
		function jig_verify_fb_authed(){
			check_ajax_referer('jig_verify_fb_authed', 'security');
			$output = array();
			$token = $_REQUEST['token'];
			$user_id = $_REQUEST['user_id'];
			$albums_url = "https://graph.facebook.com/".$user_id."/albums?fields=id,link,count,from&limit=1000".($token != 'public' ? '&access_token='.$token : '');
			$albums = json_decode(file_get_contents($albums_url));

			if(!empty($albums->data)){
				$found = 0;
				foreach ($albums->data as $key => $value) {
					if(!empty($value->count) && !empty($value->link)){
						$found++;
					}
				}
				if($found > 0){
					$output = array(	
						'user_name' => $albums->data[0]->from->name);	
					$output['info']['album_count'] = $found.' '._n('album', 'albums', $found, 'jig_td');
					if($token != 'public'){				
							foreach($this->settings['fb_authed'] as $key => $val){
								if($val['access_token'] === $token && $val['type'] != 'page'){
									$output['access_token'] = $token;
									$output['access_token_owner_name'] = $val['user_name'];
									$output['access_token_owner_id'] = $val['user_id'];
									$output['info']['expires'] = $val['expires'];
									$output['info']['time_added'] = $val['time_added'];
									$output['info']['time_remaining'] = $this->jig_time_left($val['time_added']+$val['expires']);
									$output['info']['owner_type'] = $val['type'];							
									break;
								}
							}
						} 
				
				}else{
					$output = array('error' => __('No pictures in any album.', 'jig_td'));
				}
			}else{ // find out the reason why album data is missing
				if(!isset($albums->error->message)){
					if($albums === false){
						$output = array('error' => __('Demographically or geographically blocked - please add this again and choose a user to authenticate with.', 'jig_td'));
					}else{
						$output = array('error' => __('No albums at all.', 'jig_td'));
					}
				}else{
					$output = array('error' => __('Invalid access/expired, please re-authenticate! The error from Facebook', 'jig_td').': '.$albums->error->message);
				}
			}
			echo json_encode($output);
			die();
		}

		// loads facebook albums for the shortcode editor
		function jig_get_fb_albums(){
			check_ajax_referer('jig_get_fb_albums', 'security');
			$token = $_REQUEST['token'];
			$user_id = $_REQUEST['user_id'];
			$output = array();
			$albums_url = "https://graph.facebook.com/".$user_id."/albums?fields=id,link,count,name&limit=1000".($token != 'public' ? '&access_token='.$token : '');
			$albums = json_decode(file_get_contents($albums_url));

			if(!empty($albums->data)){
				$albums_count = count($albums->data);
				$found = 0;
				$output['elements'] = '';
				foreach ($albums->data as $key => $value) {
					if(!empty($value->count) && !empty($value->link)){
						$album_for_cover_url = "https://graph.facebook.com/".$value->id."/photos?fields=images".($token != 'public' ? '&access_token='.$token : '');
						$invisible = false;
						$img = false;
						if($found < 10){

							$album_for_cover = json_decode(file_get_contents($album_for_cover_url));
							if(!empty($album_for_cover->data)){
								$img = '<div class="fbAlbumPhoto"><img src="'.($this->settings['timthumb_path'] ? $this->settings['timthumb_path'] : plugins_url('timthumb.php', __FILE__)).'?src='.$album_for_cover->data[0]->images[count($album_for_cover->data[0]->images)-4]->source.'&w=160&h=160&q=95" /></div>';
							}else{
								$invisible = true;
								$value->count = 0;
							}
						}
						if ($img != false){
							$output['elements'] .= '<div class="fbAlbum" id="'.$value->id.'"">';
							$output['elements'] .= '<div class="fbAlbumLoading">loading image</div>';
							$output['elements'] .= $img;
						} else if($invisible == false){
							$output['elements'] .= '<div class="fbAlbum fbSkipImg fbImgFade" id="'.$value->id.'" data-album-for-cover-url="'.$album_for_cover_url.'">';
							$output['elements'] .= '<div class="fbAlbumToLoad">mouse over to load image</div>';
						}else{
							$output['elements'] .= '<div class="fbAlbum fbNoImg" id="'.$value->id.'">';
							$output['elements'] .= '<div class="fbAlbumCantLoad">no photos in this album</div>';
						}
						$output['elements'] .= '<div class="fbAlbumTitle">'.$value->name.'</div>';
						$output['elements'] .= '<div class="fbAlbumCount">'.$value->count.'</div></div>';				
						$found++;
					}
				}
				if($found == 0){
					$output = array('error' => __('No pictures in any album.', 'jig_td'));
				}
			}else{ // find out the reason why album data is missing
				if(!isset($albums->error->message)){
					if($albums === false){
						$output = array('error' => __('Demographically or geographically blocked - please add this again and choose a user to authenticate with.', 'jig_td'));
					}else{
						$output = array('error' => __('No albums at all.', 'jig_td'));
					}
				}else{
					$output = array('error' => __('Invalid access/expired, please re-authenticate! The error from Facebook', 'jig_td').': '.$albums->error->message);
				}
			}
			echo json_encode($output);
			die();
		}

		// gets the first picture of a facebook album
		function jig_get_fb_album_cover_on_demand(){
			check_ajax_referer('jig_get_fb_album_cover_on_demand', 'security');
			$album_for_cover_url = $_REQUEST['album_for_cover_url'];
			$album_for_cover = json_decode(file_get_contents($album_for_cover_url));
			$output = array();
			$output['url'] = $album_for_cover_url;
			
			if(!empty($album_for_cover->data)){
				$output['img'] = '<div class="fbAlbumPhoto"><img src="'.($this->settings['timthumb_path'] ? $this->settings['timthumb_path'] : plugins_url('timthumb.php', __FILE__)).'?src='.$album_for_cover->data[0]->images[count($album_for_cover->data[0]->images)-4]->source.'&w=160&h=160&q=95" /></div>';
			}else{
				$output['error'] = 'empty';
			}
			echo json_encode($output);
			die();
		}

		// checks for expired FB auths
		function jig_fb_check_expired(){
			if(isset($this->settings['fb_authed'])){
				if($this->settings['fb_authed'] === ''){
					return;
				}
				global $jig_expired_notice;
				foreach($this->settings['fb_authed'] as $key => $val){
					if(!isset($val['time_added'])){
						continue;
					}
					$jig_fb_expires_time = $val['time_added']+$val['expires'];
					if($jig_fb_expires_time > time()+604800){//7776000
						continue;
					}else if($jig_fb_expires_time > time()){
						$jig_expired_notice .= "<div id='akismet-warning' class='updated fade'><p><strong>".__('Justified Image Grid', 'jig_td').":</strong> ".sprintf(__('Facebook authorization for %1$s expires in %2$s. <a href="%3$s">Please re-authorize soon!</a>', 'jig_td'), $val['user_name'], $this->jig_time_left($jig_fb_expires_time), "options-general.php?page=justified-image-grid#Facebook")."</p></div>";
					}else{
						$jig_expired_notice .= "<div id='akismet-warning' class='error fade'><p><strong>".__('Justified Image Grid', 'jig_td').":</strong> ".sprintf(__('Facebook authorization for %1$s has EXPIRED. <a href="%2$s">You have to re-authorize!</a>', 'jig_td'), $val['user_name'], "options-general.php?page=justified-image-grid#Facebook")."</p></div>";
					}
				}
				function print_expired_notice(){
					global $jig_expired_notice;
					echo $jig_expired_notice;
				}
				add_action('admin_notices', 'print_expired_notice');				
			}
		}

		// if the plugin was freshly activated, check writability of the thumbnails cache folder
		function jig_init_check_permissions(){
			if($this->settings['jig_activated'] == "hot"){
				$this->jig_install_check_permissions();
				$this->settings['jig_activated'] = "cold";
				update_option(self::SETTINGS_NAME,$this->settings);
			}
		}

		// calculate time left until FB Auth expiry for admin notice 
		function jig_time_left($endtime) { 
			$time_left = $endtime - time(); 
			if($time_left > 0) { 
				$days = floor($time_left / 86400); 
				$time_left = $time_left - $days * 86400; 
				$hours = floor($time_left / 3600); 
				$time_left = $time_left - $hours * 3600; 
				$minutes = floor($time_left / 60); 
			} else { 
				return 'expired'; 
			} 
			if($days > 0){
				return $days.' '._n('day', 'days', $days, 'jig_td').' '.$hours.' '._n('hour', 'hours', $hours, 'jig_td');
			}else{
				return $hours.' '._n('hour', 'hours', $hours, 'jig_td') .' '.$minutes.' '._n('minute', 'minutes', $minutes, 'jig_td') ;
			}
		}

		// help IE with rgba for caption backgrounds
		function jig_rgbaIE($color){
			if (preg_match("/(.*?)rgba\((\d+)[, ]{1,2}(\d+)[, ]{1,2}(\d+)[, ]{1,2}([.\d]{1,4})\)/i", $color, $e)){
				$e[5] = $e[5]*255;
				for($i = 2; $i<6; $i++){
					$e[$i] = dechex(($e[$i] <= 0)?0:(($e[$i] >= 255)?255:$e[$i]));
					$e[$i] = ((strlen($e[$i]) < 2)?'0':'').$e[$i];
				}
				$hex = $e[5].$e[2].$e[3].$e[4];
				return "<!--[if IE]>
						<style type='text/css'>
						#jig{$jig_id} .jig-caption { 
							background:transparent;
							filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=#{$hex},endColorstr=#{$hex});
							zoom: 1;
						} 
						</style>
						<![endif]-->";
			}
			return;
		}

		// attempts to fix chmod issues
		function jig_attempt_chmod(){
			check_ajax_referer('jig_attempt_chmod', 'security');
			$permission = $_REQUEST['permission'];
			$output = array();
			$output['message'] = '';
			if(chmod(dirname(__FILE__), ($permission == "0755" ? 0755 : 0777))){
				$output['message'] .= 'Plugin folder (<span style="color:#888">'.dirname(__FILE__).'</span>) chmod is <strong>successful</strong> to '.$permission.'.<br/>';
			}else{
				$output['message'] .= 'Plugin folder (<span style="color:#888">'.dirname(__FILE__).'</span>) chmod <strong>failed</strong>.<br/>';
			}
			if(chmod(dirname(__FILE__)."/cache", ($permission == "0755" ? 0755 : 0777))){
				$output['message'] .= 'Cache folder (<span style="color:#888">'.dirname(__FILE__)."/cache".'</span>) chmod is <strong>successful</strong> to '.$permission.'.<br/>';
			}else{
				$output['message'] .= 'Cache folder (<span style="color:#888">'.dirname(__FILE__)."/cache".'</span>) chmod <strong>failed</strong>.<br/>';
			}
			echo json_encode($output);
			die();
		}

		// checks if cache folder is writable for real
		function jig_cache_writable(){
			$file = dirname(__FILE__)."/cache/".time().'.txt';
			$stream = @fopen($file, 'w');
			if($stream){
				fclose($stream);
				unlink($file);
				return true;
			}else{
				return false;
			}	
		}

		// checks permissions on the cache folder and the plugin folder
		function jig_install_check_permissions(){
			$fixed = false;
			if(!$this->jig_cache_writable()){
				$plugin_chmod = chmod(dirname(__FILE__), 0755);
				$cache_chmod = chmod(dirname(__FILE__)."/cache", 0755);
				if($plugin_chmod && $cache_chmod){
					$fixed = true;
				};
			}
			if(!$this->jig_cache_writable()){
				if($fixed){
					function timthumb_big_problem(){
						echo "<div class='error fade'><p>".__('The thumbnails cache folder is not writable! <a href="options-general.php?page=justified-image-grid#TimThumb">Click here to go to the settings where you can fix this.</a> Unless you do so your images might not appear and the plugin could only generate whitespace!', 'jig_td')." ".__('The plugin was trying to fix it but the 0755 permission was not enough.', 'jig_td')."</p></div>";
					}
					add_action('admin_notices', 'timthumb_big_problem');
				}else{
					function timthumb_problem(){
						echo "<div class='error fade'><p> ".__('The thumbnails cache folder is not writable! <a href="options-general.php?page=justified-image-grid#TimThumb">Click here to go to the settings where you can fix this.</a> Unless you do so your images might not appear and the plugin could only generate whitespace!', 'jig_td')."</p></div>";
					}
					add_action('admin_notices', 'timthumb_problem');
				}
			}else{
				if($fixed){
					function timthumb_fixed(){
						echo "<div class='updated fade'><p> ".__('The thumbnails cache folder was not writable! This was automatically fixed for you.', 'jig_td')."</p></div>";
					}
					add_action('admin_notices', 'timthumb_fixed');
				}else{
					function timthumb_perfect(){
						echo "<div class='updated fade'><p> ".__('The thumbnails cache folder was tested and it is writable!', 'jig_td')."</p></div>";
					}
					add_action('admin_notices', 'timthumb_perfect');
				}
			}
		}

		// checks folder permissions on demand, returns nice output
		function jig_on_demand_check_permissions(){
			check_ajax_referer('jig_on_demand_check_permissions', 'security');
			$output = array();
			if($this->jig_cache_writable()){
				$output['writable'] = '<span style="font-weight:bold; color:green;">writable</span>';
			}else{
				$output['writable'] = '<span style="font-weight:bold; color:red;">not writable</span>';
			}
			$output['permission_plugin'] = substr(sprintf('%o', fileperms(dirname(__FILE__))), -4);
			$output['permission_cache'] = substr(sprintf('%o', fileperms(dirname(__FILE__)."/cache")), -4);
			echo json_encode($output);
			die();
		}

		// adds custom link functionality to gallery images
		function jig_image_attachment_fields_to_edit($form_fields, $post){
			$form_fields["jig_image_link"] = array(
				"label" => __('Custom link for Justified Image Grid', 'jig_td'),
				"input" => "text",
				"value" => get_post_meta($post->ID, "_jig_image_link", true),
				"helps" => __('Use this instead of Link URL when creating a gallery with JIG and you wish to point the image link to a custom URL', 'jig_td'),
			);
			return $form_fields;
		}

		// saves it
		function jig_image_attachment_fields_to_save($post, $attachment){
			if(isset($attachment['jig_image_link'])){
				update_post_meta($post['ID'], '_jig_image_link', $attachment['jig_image_link']);
			}
			return $post;
		}
	}
}

if (class_exists("JustifiedImageGrid")){
	if(!isset($justified_image_grid_instance)){
		$justified_image_grid_instance = 0;
		$justified_image_grid_js = '';
	}
	$justified_image_grid = new JustifiedImageGrid();
	function get_jig($atts = ''){
		$jig = new JustifiedImageGrid();
		echo $jig->add_from_template_tag($atts);
	}
}
register_activation_hook(__FILE__, array('JustifiedImageGrid', 'on_activate'));
?>