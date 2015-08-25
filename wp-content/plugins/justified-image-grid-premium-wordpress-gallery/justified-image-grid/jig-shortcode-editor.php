<?php
	// this file is loaded in the modal window of tinyMCE
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>Justified Image Grid shortcode editor</title>
		<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js"></script>
		<script language="javascript" type="text/javascript" src="<?php echo includes_url( 'js/tinymce/tiny_mce_popup.js' ); ?>"></script>
		<style type="text/css">
			label, .normalname, .minihelp {
				display: block;
				float: left;
				margin: 3px 0 5px 8px;
			}
			label {
				color: #3B5A99;
				width: 125px;
				text-align: right;
			}
			.normalname {
				width: 185px;
			}
			.minihelp {
				color: #666;
				width: 406px;
			}
			#insert {
				float: right;
				font-size: 14px;
				margin: 10px 0;
				text-align: center;
				text-decoration: none;
				width: 100%;
			}
			#templateTagButton, .fbSourceBtn {
				color: #666;
				display: block;
				float: left;
				font-weight: normal;
				line-height: 24px;
				margin: 3px 0 5px 8px;
				padding: 0 6px;
				text-align: center;
				text-decoration: none;
				width: auto;
			}
			#fbRow{
				margin-bottom:4px;
			}
			#templateTagButton:hover {
				color:#000;
			}
			#insertButtonParent{
				background: none repeat scroll 0 0 #F1F1F1;
				border-top: 1px solid #D1D1D6;
				bottom: 0;
				height: 40px;
				position: fixed;
				width: 870px;
				z-index: 10;
			}
			#bottomSpacer{
				height: 50px;
			}
			#hint{
				color: green;
				margin-bottom: 15px;
			}
			select, input, #templateTag { 
				padding: 3px 5px;
				border: 1px solid #D1D1D6;
				border-radius: 3px 3px 3px 3px;
				width: 100px;
				margin: 0 0 0 8px;
				display: block;
				float: left;
			}
			select {
				width:112px;
			}
			#templateTag{
				width:233px;
				background:white;
			}
			#templateTagHelp, #templateTagContainer{
				display:none;
			}
			h3 {
				color: #000000;
				margin: 0;
				padding: 0 0 10px;
				font-size:13px;
			}
			.jig_settings_group,
			.jig_settings_group_facebook {
				background: none repeat scroll 0 0 #E7E7EB;
				border: 1px solid #D1D1D6;
				border-radius: 6px 6px 6px 6px;
				margin-bottom: 15px;
				padding-bottom: 4px;
			}
			.row {
				clear: both;
				padding: 5px 0;
				height: 20px;
			}
			.flexirow{
				clear: both;
				padding: 5px 0;
				position:relative;
			}
			#fbHelp{
				margin:3px 8px 0px;
			}
			#fbLoadingAJAX{
				background: rgba(231,231,235,0.8);
				height: 100%;
				position: absolute;
				width: 100%;
				text-shadow: 0 1px white;
				display:none;
				z-index: 5;
			}
			#fbLoadingInner{
				background: url("<?php echo plugins_url('images/ajax-loader.gif', __FILE__); ?>") no-repeat left 29px;
				font-size: 10px;
				font-weight: bold;
				margin-left: 350px;
				padding: 0 0 25px 6px;
				text-transform: uppercase;
				position: relative;
			}
			#fbLoadingInnerSmallText{
				color: #666666;
				font-size: 8px;
				letter-spacing: 0.1px;
			}
			#fbIcon{
				position: absolute;
				height: 48px;
				left: -49px;
				position: absolute;
				top: 3px;
				width: 48px;
				background: url("<?php echo plugins_url('images/facebook-icon.png', __FILE__); ?>") no-repeat ;
			}
			.fbBlue{
				color:#3B5A99;
			}
			#fbAlbums{
				padding: 10px 0 0 4px;
			}
			.fbAlbum{
				border: 1px solid black;
				float: left;
				height: 160px;
				margin: 5px;
				position: relative;
				width: 160px;
				background-color:#EEEEEE;				
				background-image: -ms-linear-gradient(bottom right, #DDDDDD 0%, #FFFFFF 100%);
				background-image: -moz-linear-gradient(bottom right, #DDDDDD 0%, #FFFFFF 100%);
				background-image: -o-linear-gradient(bottom right, #DDDDDD 0%, #FFFFFF 100%);
				background-image: -webkit-gradient(linear, right bottom, left top, color-stop(0, #DDDDDD), color-stop(1, #FFFFFF));
				background-image: -webkit-linear-gradient(bottom right, #DDDDDD 0%, #FFFFFF 100%);
				background-image: linear-gradient(to top left, #DDDDDD 0%, #FFFFFF 100%);
				cursor:pointer;
			}
			.fbAlbum.fbNoImg{
				cursor:default;
				opacity: 0.8;
				-moz-opacity: 0.8;
				filter:alpha(opacity=80);
			}
			.fbAlbumToLoad,
			.fbAlbumLoading,
			.fbAlbumCantLoad,
			.fbAlbumError{
				color: #999999;
				font-size: 10px;
				padding-top: 68px;
				padding-left: 5px;
				padding-right: 5px;
				position: absolute;
				text-align: center;
				width: 150px;
			}
			.fbAlbumError{
				padding-top: 60px;
			}
			.fbAlbumPhoto{
				position: absolute;
			}
			.fbImgFade img{
				display:none;
			}
			.fbAlbumTitle{
				background: rgba(0, 0, 0, 0.6);
				bottom: 0;
				color: white;
				left: 0;
				padding: 5px;
				position: absolute;
				right: 0;
				text-align: center;
				text-shadow:1px 1px rgba(0,0,0,0.6);
			}
			.fbAlbumCount,
			.fbMouseIndicator,
			.fbLoadingIndicator
			{
				background: rgba(0, 0, 0, 0.6);
				border-radius: 7px 7px 7px 7px;
				color: white;
				margin: 5px;
				padding: 5px 8px;
				position: absolute;
				right: 0;
				text-align: center;
				top: 0;
				text-shadow:1px 1px rgba(0,0,0,0.6);
			}
			.fbMouseIndicator,
			.fbLoadingIndicator{
				left:0;
				right: auto;
			}
			#fbSources{
				padding:0 5px;
			}
			.fbSourceBtn{
				padding: 3px 8px;
				margin: 5px;
				color:black;
			}
			.fbSelected,
			.fbSelected:hover{
				border: 2px solid #3B5A99;
				margin:4px;
			}
			.fbSelectedAlbum,
			.fbSelectedAlbum:hover{
				border: 3px solid #3B5A99;
				margin:3px;
			}
			#preset_select {
				width: 220px;
			}
			#preset_minihelp {
				width: 295px;
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
		<!--[if IE]> <style type='text/css'>
			.fbAlbumTitle, .fbAlbumCount { background:transparent; filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=#99000000,endColorstr=#99000000); zoom: 1; }
			.fbLoadingAJAX { background:transparent; filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=#CCE7E7EB,endColorstr=#CCE7E7EB); zoom: 1; }
		 </style> <![endif]-->
		<script type="text/javascript">
			var shortcodes = [	"preset",
								"thumbs_spacing",
								"row_height",
								"animation_speed",
								"height_deviation",
								"link_class",
								"link_rel",
								"link_title_field",
								"img_alt_field",
								"title_field",
								"caption_field",
								"caption",
								"caption_opacity",
								"caption_bg_color",
								"caption_text_color",
								"caption_text_shadow",
								"overlay",
								"overlay_color",
								"overlay_opacity",
								"desaturate",
								"lightbox",
								"lightbox_max_size",
								"min_height",
								"margin",
								"timthumb_path",
								"quality",
								"orderby",
								"limit",
								"max_rows",
								"last_row",
								"mouse_disable",
								"error_checking",
								"id",
								"exclude",
								"include",
								"facebook_id",
								"facebook_album",
								"facebook_caching"]
			var sc_length = shortcodes.length;
			var JigShortcodeEditor = {
				local_ed : 'ed',
				init : function(ed) {
					jQuery("#facebook").on("click", ".fbSourceBtn", function(event){
						if(!jQuery('#fbOffBtn').hasClass('fbSelected')){
							jQuery("#facebook input[name='facebook_album']").val('')
						}
						var btn = jQuery(this);
						var id = btn.attr('id');
						var token = btn.attr('data-access-token');
						jQuery(".fbSelected").removeClass("fbSelected");
						btn.addClass("fbSelected");
						jQuery("#facebook input[name='facebook_id']").val(id);
						if(btn.attr('id') != 'fbOffBtn'){
							jQuery("#fbLoadingAJAX").show()
							jQuery("#fbSources").css('visibility','hidden')
						}
						jQuery.post(
						"<?php echo admin_url('admin-ajax.php'); ?>",
						{
							'action': 'jig_get_fb_albums',
							'security': '<?php echo wp_create_nonce("jig_get_fb_albums") ?>',
							'token' : token,
							'user_id' : id

						},
						function(data) {
							if(data['elements']){
								jQuery("#fbAlbums").html(data['elements'])
								jQuery("#"+jQuery("#facebook input[name='facebook_album']").val()).click();
							}else if(data['error']){
								jQuery("#fbAlbums").html(data['error']);
							}
							jQuery("#fbLoadingAJAX").hide()
							jQuery("#fbSources").css('visibility','visible')

						},
						'json');
					});
					jQuery("#facebook").on("click", ".fbAlbum:not(.fbNoImg,.fbSelectedAlbum)", function(event){
						var btn = jQuery(this);
						var id = btn.attr('id');
						var token = btn.attr('data-access-token');
						jQuery(".fbSelectedAlbum").removeClass("fbSelectedAlbum");
						btn.addClass("fbSelectedAlbum");
						jQuery("#facebook input[name='facebook_album']").val(id);
						if(btn.find('.fbAlbumLoading').length < 1){
							JigShortcodeEditor.loadFacebookAlbumCover(btn);
						}
					});
					jQuery("#facebook").on("click", "#fbOffBtn", function(event){
						jQuery('#fbAlbums').empty()
						jQuery('#jig-sc-editor .jig_settings_group_facebook input[name="facebook_id"]').val('');
						jQuery('#jig-sc-editor .jig_settings_group_facebook input[name="facebook_album"]').val('');
					});


					jQuery("#facebook").on("mouseenter mouseleave", ".fbSkipImg", function(event){
						var $this = $(this);
						event.stopImmediatePropagation();
						if(event.type === "mouseenter"){
							if($this.find('.fbMouseIndicator').length < 1){
								$this.append('<div class="fbMouseIndicator fbStandby">!</div>')
								showImg = setTimeout(function(){
									if($this.find('.fbLoading').length < 1){
										JigShortcodeEditor.loadFacebookAlbumCover($this);
									}
								}, 500); 
							}
						}else{
							$this.find('.fbStandby').remove()
							if(showImg !== false){
								clearTimeout(showImg);
							}
						}
					});
					JigShortcodeEditor.local_ed = ed;
					tinyMCEPopup.resizeToInnerSize();
					var sc = JigShortcodeEditor.local_ed.selection.getContent();
					var matches = sc.match(/([a-z_]*?)=([\d\sa-zA-Z_'"(),.:\/#\-]*)(?= [a-z_]*?|])/g)
					if(matches){
						var matches_length = matches.length;
						var facebook = new Array();
						for(var i = 0; i<matches_length; i++){
							var attr = matches[i].split("=");
							
							jQuery('#jig-sc-editor input[name="'+attr[0]+'"]').val(attr[1])
							jQuery('#jig-sc-editor select[name="'+attr[0]+'"] option[value="'+attr[1]+'"]').attr('selected', true)
							if(attr[0] == "facebook_id" || attr[0] == "facebook_album"){
								facebook[attr[0]] = attr[1];
							}
						}
						if(!jQuery.isEmptyObject(facebook) ){
							JigShortcodeEditor.loadFacebookValues(facebook);
						}
					}
					if(sc.indexOf("[justified_image_grid")>-1){
						jQuery('#insert').text("<?php _e('Edit Shortcode', 'jig_td'); ?>")
						jQuery('#hint').remove()	
					}
					
				},
				insert : function insertButton(ed) {
					tinyMCEPopup.execCommand('mceRemoveNode', false, null);
					var output = '[justified_image_grid';
					for(var i = 0; i<sc_length; i++){
						var val = jQuery('#jig-sc-editor .jig_settings_group input[name="'+shortcodes[i]+'"]').val();
						if (val == undefined){
							val = jQuery('#jig-sc-editor select[name="'+shortcodes[i]+'"] option:selected').val(); 
						}
						if(val != '' && val != 'default' && val != undefined){
							output += ' '+shortcodes[i]+'='+val;
						}
					}
					var facebook_id = jQuery('#jig-sc-editor .jig_settings_group_facebook input[name="facebook_id"]').val();
					var facebook_album = jQuery('#jig-sc-editor .jig_settings_group_facebook input[name="facebook_album"]').val();
					var facebook_caching = jQuery('#jig-sc-editor .jig_settings_group_facebook input[name="facebook_caching"]').val();
					if(facebook_id != "" && facebook_album != ""){
						output += ' facebook_id='+facebook_id+' facebook_album='+facebook_album;
						if(facebook_caching != '' && facebook_caching != 'default' && facebook_caching != undefined){
							output += ' facebook_caching='+facebook_caching;
						}
					}
					output += ']';
					JigShortcodeEditor.local_ed.selection.setContent(output);
					tinyMCEPopup.close();
				},
				loadFacebookValues : function(settings){
					jQuery("#"+settings['facebook_id']).click();
				},
				loadFacebookAlbumCover : function(element){
					element.find('.fbMouseIndicator').removeClass('fbStandby').addClass('fbLoading').text("<?php _e('loading', 'jig_td'); ?>")
					jQuery.post(
								"<?php echo admin_url('admin-ajax.php'); ?>",						
								{
									'action': 'jig_get_fb_album_cover_on_demand',
									'security': '<?php echo wp_create_nonce("jig_get_fb_album_cover_on_demand") ?>',
									'album_for_cover_url' : element.attr('data-album-for-cover-url')
								},
								function(data) {
									if(data['img']){
										element.removeClass('fbSkipImg').find('.fbAlbumToLoad').after(data['img']);
										element.find('img').load(function(){									
														$(this).fadeIn(300);
														element.find('.fbMouseIndicator').fadeOut(300,function(){$(this).remove()})
										}).error(function(){
											element.find('.fbMouseIndicator').fadeOut(300,function(){$(this).remove()})
											element.addClass('fbNoImg')
											.find('.fbAlbumToLoad').removeClass('fbAlbumToLoad').addClass('fbAlbumError').text("<?php _e('error loading cover photo, album is now disabled to prevent further errors', 'jig_td'); ?>").siblings('.fbAlbumCount').text("0");
											if(element.hasClass('fbSelectedAlbum')){
												element.removeClass("fbSelectedAlbum");
												jQuery("#facebook input[name='facebook_album']").val('');
											}
										})
									}else if(data['error'] == 'empty'){
										element.removeClass('fbSkipImg').addClass('fbNoImg')
										.find('.fbAlbumToLoad').removeClass('fbAlbumToLoad').addClass('fbAlbumCantLoad').text("<?php _e('no photos in this album', 'jig_td'); ?>").siblings('.fbAlbumCount').text("0");
										if(element.hasClass('fbSelectedAlbum')){
											element.removeClass("fbSelectedAlbum");
											jQuery("#facebook input[name='facebook_album']").val('');
										}
										element.find('.fbMouseIndicator').remove()
									}							
								},
								'json');
				},
				templateTag : function (){
					if(jQuery('#jig-sc-editor input[name="id"]').val() != '' || (jQuery('#jig-sc-editor .jig_settings_group_facebook input[name="facebook_id"]').val() != '' &&	jQuery('#jig-sc-editor .jig_settings_group_facebook input[name="facebook_album"]').val() != '')){
						var output = 'get_jig(array(';
						for(var i = 0; i<sc_length; i++){
							var val = jQuery('#jig-sc-editor input[name="'+shortcodes[i]+'"]').val();
							if (val == undefined){
								val = jQuery('#jig-sc-editor select[name="'+shortcodes[i]+'"] option:selected').val(); 
							}
							if(val != '' && val != 'default' && val != undefined){
								output += "'"+shortcodes[i]+"' => '"+val+"', ";
							}
						}
						output = output.substring(0,output.length-2);
						output += '));'

						var quotes = {"'\"":"'", "\"'":"'", "''":"'"};
						for (var val in quotes)
						    output = output.replace(new RegExp(val, "g"), quotes[val]);

						jQuery("#templateTagContainer").show().find("#templateTag").text('<'+'?php '+output+' ?'+'>').next().show()
					}else{
						jQuery("#templateTagContainer").show().find("#templateTag").text("<?php _e('Please set an ID in the General settings, otherwise the template tag will not work. You can also use a Facebook album instead.', 'jig_td'); ?>").next().hide()
					}

				}
			};
			tinyMCEPopup.onInit.add(JigShortcodeEditor.init, JigShortcodeEditor);
		</script>
	</head>
	<body>
		<div id="jig-sc-editor">
			<div id="hint"><?php _e('Hint: If you already have a shortcode in your post and you wish to edit its attributes instead of starting over:<br />Please close this popup, select the shortcode, then open this again. Your settings will be loaded.',  'jig_td'); ?></div>
			<form action="/" method="get" accept-charset="utf-8">
				<h3>Presets</h3>
				<div class="jig_settings_group clearfix">
					<div class="row">
						<div class="normalname"><?php _e('Preset',  'jig_td'); ?></div>
						<label>preset</label>
						<select name="preset" id="preset_select">
							<option value="default" selected="selected"><?php _e('default', 'jig_td'); ?></option>
							<option value="1"><?php _e('Preset 1: Out of the box', 'jig_td'); ?></option>
							<option value="2"><?php _e("Preset 2: Author's favorite", 'jig_td'); ?></option>
							<option value="3"><?php _e('Preset 3: Flickr style', 'jig_td'); ?></option>
							<option value="4"><?php _e('Preset 4: Google+ style', 'jig_td'); ?></option>
							<option value="5"><?php _e('Preset 5: Fixed height, no fancy', 'jig_td'); ?></option>
							<option value="6"><?php _e('Preset 6: Artistic-zen', 'jig_td'); ?></option>
							<option value="7"><?php _e('Preset 7: Color magic fancy style', 'jig_td'); ?></option>
							<option value="8"><?php _e('Preset 8: Big images no click', 'jig_td'); ?></option>
							<option value="9"><?php _e('Preset 9: Focus on the text', 'jig_td'); ?></option>
						</select>
						<div class="minihelp" id="preset_minihelp"><?php _e("choose one of the 9 presets", 'jig_td'); ?></div>
					</div>
				</div>
				<h3>General settings</h3>
				<div class="jig_settings_group clearfix">
					<div class="row">
						<div class="normalname"><?php _e('Height of the rows',  'jig_td'); ?></div>
						<label>row_height</label>
						<input type="text" name="row_height" value='' />
						<div class="minihelp"><?php _e('target height in pixels: 200 without px', 'jig_td'); ?></div>
					</div>
					<div class="row">
						<div class="normalname"><?php _e('Row height max deviation (+-)', 'jig_td'); ?></div>
						<label>height_deviation</label>
						<input type="text" name="height_deviation" value='' />
						<div class="minihelp"><?php _e('height +/- this value: 50 without px', 'jig_td'); ?></div>
					</div>
					<div class="row">
						<div class="normalname"><?php _e('Spacing between the thumbnails',  'jig_td'); ?></div>
						<label>thumbs_spacing</label>
						<input type="text" name="thumbs_spacing" value='' />
						<div class="minihelp"><?php _e('0 or 4 or 10 etc... without px', 'jig_td'); ?></div>
					</div>
					<div class="row">
						<div class="normalname"><?php _e('Margin around gallery',  'jig_td'); ?></div>
						<label>margin</label>
						<input type="text" name="margin" value='' />
						<div class="minihelp"><?php _e('CSS margin value: 10px or "0px 10px" (wrap shorthand with "")', 'jig_td'); ?></div>
					</div>
					<div class="row">
						<div class="normalname"><?php _e('Animation speed',  'jig_td'); ?></div>
						<label>animation_speed</label>
						<input type="text" name="animation_speed" value='' />
						<div class="minihelp"><?php _e('as milliseconds: 200 is fast, 600 is slow', 'jig_td'); ?></div>
					</div>
					<div class="row">
						<div class="normalname"><?php _e('Min height to avoid "jumping"',  'jig_td'); ?></div>
						<label>min_height</label>
						<input type="text" name="min_height" value='' />
						<div class="minihelp"><?php _e('to avoid jumping footer if you have no sidebar: 800 without px<br />don\'t set it higher than the gallery itself', 'jig_td'); ?></div>
					</div>
					<div class="row">
						<div class="normalname"><?php _e('Limit image count', 'jig_td'); ?></div>
						<label>limit</label>
						<input type="text" name="limit" value='' />
						<div class="minihelp"><?php _e('only load this amount of images maximum - set to 0 to reset a global setting to unlimited', 'jig_td'); ?></div>
					</div>
					<div class="row">
						<div class="normalname"><?php _e('Max rows', 'jig_td'); ?></div>
						<label>max_rows</label>
						<input type="text" name="max_rows" value='' />
						<div class="minihelp"><?php _e('only load x rows - set to 0 to reset a global setting to unlimited<br />combine with fixed row height (0 deviation) to create a banner', 'jig_td'); ?></div>
					</div>
					<div class="row">
						<div class="normalname"><?php _e('Incomplete last row', 'jig_td'); ?></div>
						<label>last_row</label>
						<select name="last_row">
							<option value="default" selected="selected"><?php _e('default', 'jig_td'); ?></option>
							<option value="normal"><?php _e('Normal - try to fill width OR fall back to target height (visibly incomplete)', 'jig_td'); ?></option>
							<option value="hide"><?php _e("Hide it", 'jig_td'); ?></option>
							<option value="match"><?php _e("Match previous row's height", 'jig_td'); ?></option>
						</select>
						<div class="minihelp"><?php _e("the last row is not always full - choose how to handle it", 'jig_td'); ?></div>
					</div>
					<div class="row">
						<div class="normalname"><?php _e('Order by',  'jig_td'); ?></div>
						<label>orderby</label>
						<select name="orderby">
							<option value="default" selected="selected"><?php _e('default', 'jig_td'); ?></option>
							<option value="menu_order"><?php _e('Menu order', 'jig_td'); ?></option>
							<option value="rand"><?php _e("Random", 'jig_td'); ?></option>
							<option value="title_asc"><?php _e('Title ascending', 'jig_td'); ?></option>
							<option value="title_desc"><?php _e('Title descending', 'jig_td'); ?></option>
							<option value="date_asc"><?php _e('Date ascending', 'jig_td'); ?></option>
							<option value="date_desc"><?php _e('Date descending', 'jig_td'); ?></option>
						</select>
						<div class="minihelp"><?php _e("the order of the images (only for images from WP)", 'jig_td'); ?></div>
					</div>
					<div class="row">
						<div class="normalname"><?php _e('Disable right mouse menu',  'jig_td'); ?></div>
						<label>mouse_disable</label>
						<select name="mouse_disable">
							<option value="default" selected="selected"><?php _e('default', 'jig_td'); ?></option>
							<option value="no"><?php _e('No', 'jig_td'); ?></option>
							<option value="yes"><?php _e('Yes', 'jig_td'); ?></option>
						</select>
						<div class="minihelp"><?php _e('choose yes if you wish to disable right click menu', 'jig_td'); ?></div>
					</div>
					<div class="row">
						<div class="normalname"><?php _e('Other post/page id',  'jig_td'); ?></div>
						<label>ID</label>
						<input type="text" name="id" value='' />
						<div class="minihelp"><?php _e('to pull a gallery of other page/post: postID (number)', 'jig_td'); ?></div>
					</div>
					<div class="row">
						<div class="normalname"><?php _e('Exclude these images',  'jig_td'); ?></div>
						<label>exclude</label>
						<input type="text" name="exclude" value='' />
						<div class="minihelp"><?php _e('to get the numbers: go to the Media Library, mouse over the names in the rows and note the attachment_id number in the status bar<br />then add them here (separated with a comma)', 'jig_td'); ?></div>
					</div>
					<div class="row">
						<div class="normalname"><?php _e('Include these images',  'jig_td'); ?></div>
						<label>include</label>
						<input type="text" name="include" value='' />
						<div class="minihelp"><?php _e('similar to exclude but only use ONE of these two', 'jig_td'); ?></div>
					</div>
					<div class="row">
						<div class="normalname"><?php _e("Error checking", 'jig_td'); ?></div>
						<label>error_checking</label>
						<select name="error_checking">
							<option value="default" selected="selected"><?php _e('default', 'jig_td'); ?></option>
							<option value="yes"><?php _e('Yes', 'jig_td'); ?></option>
							<option value="no"><?php _e('No', 'jig_td'); ?></option>					
						</select>
						<div class="minihelp"><?php _e('yes to hide unloadable images from the grid / no to show all', 'jig_td'); ?></div>
					</div>
				</div>
				<h3>Lightboxes</h3>
				<div class="jig_settings_group clearfix">
					<div class="row">
						<div class="normalname"><?php _e('Lightbox type',  'jig_td'); ?></div>
						<label>lightbox</label>
						<select name="lightbox">
							<option value="default" selected="selected"><?php _e('default', 'jig_td'); ?></option>
							<option value="prettyphoto"><?php _e('prettyPhoto', 'jig_td'); ?></option>
							<option value="colorbox"><?php _e('ColorBox', 'jig_td'); ?></option>
							<option value="custom"><?php _e('Custom', 'jig_td'); ?></option>
							<option value="no"><?php _e('No', 'jig_td'); ?></option>
							<option value="links-off"><?php _e('Links-off', 'jig_td'); ?></option>
						</select>
						<div class="minihelp"><?php _e('decide what happens when an image is clicked', 'jig_td'); ?></div>
					</div>
					<div class="row">
						<div class="normalname"><?php _e('Link class(es)',  'jig_td'); ?></div>
						<label>link_class</label>
						<input type="text" name="link_class" value='' />
						<div class="minihelp"><?php _e("class of the image's anchor tag", 'jig_td'); ?></div>
					</div>
					<div class="row">
						<div class="normalname"><?php _e('Link rel',  'jig_td'); ?></div>
						<label>link_rel</label>
						<input type="text" name="link_rel" value='' />
						<div class="minihelp"><?php _e('no [], so format it like this: gallery(modal)<br />can also be set to auto for proper prettyPhoto deeplinking', 'jig_td'); ?></div>
					</div>
					<div class="row">
						<div class="normalname"><?php _e('Maximum size for lightbox',  'jig_td'); ?></div>
						<label>lightbox_max_size</label>
						<select name="lightbox_max_size">
							<option value="default" selected="selected"><?php _e('default', 'jig_td'); ?></option>
							<option value="large"><?php _e('Large', 'jig_td'); ?></option>
							<option value="full"><?php _e('Full', 'jig_td'); ?></option>
							<option value="medium"><?php _e('Medium', 'jig_td'); ?></option>
						</select>
						<div class="minihelp"><?php _e('max size of the image that loads in the lightbox', 'jig_td'); ?></div>
					</div>
					<div class="row">
						<div class="normalname"><?php _e('WP field for link title',  'jig_td'); ?></div>
						<label>link_title_field</label>
						<select name="link_title_field">
							<option value="default" selected="selected"><?php _e('default', 'jig_td'); ?></option>
							<option value="description"><?php _e('Description', 'jig_td'); ?></option>
							<option value="title"><?php _e('Title', 'jig_td'); ?></option>
							<option value="caption"><?php _e('Caption', 'jig_td'); ?></option>
							<option value="alternate"><?php _e('Alternate', 'jig_td'); ?></option>
						</select>
						<div class="minihelp"><?php _e('choose a WP field as link title from the image details', 'jig_td'); ?></div>
					</div>
					<div class="row">
						<div class="normalname"><?php _e('WP field for img alt ',  'jig_td'); ?></div>
						<label>img_alt_field</label>
						<select name="img_alt_field">
								<option value="default" selected="selected"><?php _e('default', 'jig_td'); ?></option>
								<option value="title"><?php _e('Title', 'jig_td'); ?></option>
								<option value="description"><?php _e('Description', 'jig_td'); ?></option>
								<option value="caption"><?php _e('Caption', 'jig_td'); ?></option>
								<option value="alternate"><?php _e('Alternate', 'jig_td'); ?></option>
						</select>
						<div class="minihelp"><?php _e('choose a WP field as img alt from the image details', 'jig_td'); ?></div>
					</div>
				</div>
				<h3>Captions</h3>
				<div class="jig_settings_group clearfix">
					<div class="row">
						<div class="normalname"><?php _e('Caption style',  'jig_td'); ?></div>
						<label>caption</label>
						<select name="caption">
								<option value="default" selected="selected"><?php _e('default', 'jig_td'); ?></option>
								<option value="fade"><?php _e('Fade', 'jig_td'); ?></option>
								<option value="slide"><?php _e('Slide', 'jig_td'); ?></option>
								<option value="mixed"><?php _e('Mixed', 'jig_td'); ?></option>
								<option value="fixed"><?php _e('Fixed', 'jig_td'); ?></option>
								<option value="off"><?php _e('Off', 'jig_td'); ?></option>
						</select>
						<div class="minihelp"><?php _e('choose how would you like the caption to appear', 'jig_td'); ?></div>
					</div>
					<div class="row">
						<div class="normalname"><?php _e('Caption opacity',  'jig_td'); ?></div>
						<label>caption_opacity</label>
						<input type="text" name="caption_opacity" value='' />
						<div class="minihelp"><?php _e('affects entire caption, a number between 0 and 1', 'jig_td'); ?></div>
					</div>
					<div class="row">
						<div class="normalname"><?php _e('Caption background color',  'jig_td'); ?></div>
						<label>caption_bg_color</label>
						<input type="text" name="caption_bg_color" value='' />
						<div class="minihelp"><?php _e('any CSS color,<br />for opacity use rgba(0,0,0,0.3) only when the caption_opacity is 1', 'jig_td'); ?></div>
					</div>
					<div class="row">
						<div class="normalname"><?php _e('Caption text color',  'jig_td'); ?></div>
						<label>caption_text_color</label>
						<input type="text" name="caption_text_color" value='' />
						<div class="minihelp"><?php _e('any CSS color except rgba', 'jig_td'); ?></div>
					</div>
					<div class="row">
						<div class="normalname"><?php _e('WP field to use for title (main)',  'jig_td'); ?></div>
						<label>title_field</label>
						<select name="title_field">
								<option value="default" selected="selected"><?php _e('default', 'jig_td'); ?></option>
								<option value="title"><?php _e('Title', 'jig_td'); ?></option>
								<option value="description"><?php _e('Description', 'jig_td'); ?></option>
								<option value="caption"><?php _e('Caption', 'jig_td'); ?></option>
								<option value="alternate"><?php _e('Alternate', 'jig_td'); ?></option>
						</select>
						<div class="minihelp"><?php _e('choose a WP field as title from the image details', 'jig_td'); ?></div>
					</div>
					<div class="row">
						<div class="normalname"><?php _e('WP field to use for caption',  'jig_td'); ?></div>
						<label>caption_field</label>
						<select name="caption_field">
								<option value="default" selected="selected"><?php _e('default', 'jig_td'); ?></option>
								<option value="title"><?php _e('Title', 'jig_td'); ?></option>
								<option value="description"><?php _e('Description', 'jig_td'); ?></option>
								<option value="caption"><?php _e('Caption', 'jig_td'); ?></option>
								<option value="alternate"><?php _e('Alternate', 'jig_td'); ?></option>
						</select>
						<div class="minihelp"><?php _e('choose a WP field as caption description from the image details', 'jig_td'); ?></div>
					</div>
					<div class="row">
						<div class="normalname"><?php _e('Text shadow',  'jig_td'); ?></div>
						<label>caption_text_shadow</label>
						<input type="text" name="caption_text_shadow" value='' />
						<div class="minihelp"><?php _e('"1px 1px 0px black" ("x, y, blur, color" respectively - wrap it within "")<br />it\'s only applied when caption_opacity is set to 1', 'jig_td'); ?></div>
					</div>
				</div>
				<h3>Color overlay</h3>
				<div class="jig_settings_group clearfix">
					<div class="row">
						<div class="normalname"><?php _e('Overlay type',  'jig_td'); ?></div>
						<label>overlay</label>
						<select name="overlay">
								<option value="default" selected="selected"><?php _e('default', 'jig_td'); ?></option>
								<option value="others"><?php _e('Others', 'jig_td'); ?></option>
								<option value="hovered"><?php _e('Hovered', 'jig_td'); ?></option>
								<option value="off"><?php _e('Off', 'jig_td'); ?></option>
						</select>
						<div class="minihelp"><?php _e('choose a behavior for the overlay', 'jig_td'); ?></div>
					</div>
					<div class="row">
						<div class="normalname"><?php _e('Overlay opacity',  'jig_td'); ?></div>
						<label>overlay_opacity</label>
						<input type="text" name="overlay_opacity" value='' />
						<div class="minihelp"><?php _e('a number between 0 and 1', 'jig_td'); ?></div>
					</div>
					<div class="row">
						<div class="normalname"><?php _e('Overlay color',  'jig_td'); ?></div>
						<label>overlay_color</label>
						<input type="text" name="overlay_color" value='' />
						<div class="minihelp"><?php _e('any CSS color except rgba', 'jig_td'); ?></div>
					</div>
				</div>
				<h3>Desaturate</h3>
				<div class="jig_settings_group clearfix">
					<div class="row">
						<div class="normalname"><?php _e('Desaturate method',  'jig_td'); ?></div>
						<label>desaturate</label>
						<select name="desaturate">
								<option value="default" selected="selected"><?php _e('default', 'jig_td'); ?></option>
								<option value="off"><?php _e('Off', 'jig_td'); ?></option>
								<option value="others"><?php _e('Others', 'jig_td'); ?></option>
								<option value="hovered"><?php _e('Hovered', 'jig_td'); ?></option>
								<option value="everything"><?php _e('Everything', 'jig_td'); ?></option>
						</select>
						<div class="minihelp"><?php _e('choose a behavior for the on the fly grayscale effect', 'jig_td'); ?></div>
					</div>
				</div>
				<h3>Facebook</h3>
				<div class="jig_settings_group_facebook clearfix" id="facebook">
					<div class="flexirow">
						<div id="fbHelp"><?php _e("If you don't have any Profiles or Pages below, please go to the settings and authorize/add some. After that you can select from those, view the album list then choose an album to display. If you don't select an album, the Facebook settings will not be added to the shortcode. Please don't attempt to edit the Facebook ID's (<span class=\"fbBlue\">facebook_id</span> and <span class=\"fbBlue\">facebook_album</span>) manually. If this is active, settings related to the WordPress galleries will be omitted (such as <span class=\"fbBlue\">order, exclude, ID</span>). If there is a caption for a photo on Facebook, the plugin will recognize it in place of the WP Title field. No other fields will be used. You might also want to limit how many images to load (e.g. for Wall Photos), using the <span class=\"fbBlue\">limit</span> in the general settings above.", 'jig_td'); ?></div>	
					</div>
					<div id="fbRow" class="flexirow">
						<div id="fbLoadingAJAX">
							<div id="fbLoadingInner">
								<?php _e('loading albums from Facebook', 'jig_td'); ?><br />
								<span id="fbLoadingInnerSmallText"><?php _e('please be patient, this can take a while', 'jig_td'); ?></span>
								<div id="fbIcon"></div>
							</div>
						</div>
						<div id="fbSources" class="clearfix">
							<div class="updateButton fbSourceBtn fbSelected" id="fbOffBtn"><?php _e('Do not use Facebook', 'jig_td'); ?></div>
							<?php 
								if(isset($this->settings['fb_authed'])){
									foreach($this->settings['fb_authed'] as $key => $val){
										echo '<div class="updateButton fbSourceBtn" data-access-token="'.$val['access_token'].'" id="'.$val['user_id'].'">'.$val['user_name'].'</div>';
									}
								}
							?>
						</div>
						<div id="fbAlbums" class="clearfix"></div>
						<input type="hidden" name="facebook_id" value='' />
						<input type="hidden" name="facebook_album" value='' />
					</div>	
					<div class="row">
						<div class="normalname"><?php _e('Facebook caching time',  'jig_td'); ?></div>
						<label>facebook_caching</label>
						<input type="text" name="facebook_caching" value='' />
						<div class="minihelp"><?php  _e('in minutes: 4 hours is 240, a day is 1440, a week is 10080<br/>speeds up loading as the photos list for each album is cached<br />this is the time it takes to see the FB album change on the site', 'jig_td'); ?></div>
					</div>		
				</div>
				<h3>TimThumb</h3>
				<div class="jig_settings_group clearfix">
					<div class="row">
						<div class="normalname"><?php _e('TimThumb quality',  'jig_td'); ?></div>
						<label>quality</label>
						<input type="text" name="quality" value='' />
						<div class="minihelp"><?php _e('a number between 0 and 100, 90 is good quality', 'jig_td'); ?></div>
					</div>
					<div class="row">
						<div class="normalname"><?php _e('Custom TimThumb path',  'jig_td'); ?></div>
						<label>timthumb_path</label>
						<input type="text" name="timthumb_path" value='' />
						<div class="minihelp"><?php _e('absolute path (full URL)', 'jig_td'); ?></div>
					</div>
				</div>
				
				<h3>Template Tag</h3>
				<div class="jig_settings_group clearfix">
					<div class="row">
						<a href="javascript:JigShortcodeEditor.templateTag()" id="templateTagButton" class="updateButton"><?php _e('Generate template tag (optional / advanced users)',  'jig_td'); ?></a>
					</div>
					<div class="row" id="templateTagContainer">
						<div class="normalname"><?php _e('Template tag',  'jig_td'); ?>:</div>
						<div id="templateTag"></div>
						<div id="templateTagHelp" class="minihelp"><?php _e('add this to a PHP file of your template', 'jig_td'); ?></div>
						
					</div>
				</div>
				<div id="bottomSpacer"></div>
				<div id="insertButtonParent">	
					<a href="javascript:JigShortcodeEditor.insert(JigShortcodeEditor.local_ed)" id="insert" style="display: block; line-height: 24px"><?php _e('Insert Shortcode', 'jig_td'); ?></a>
				</div>
			</form>
		</div>
	</body>
</html>
<?php
	// end of file
?>