(function($) {
	$.justifiedImageGrid = function(element, options){
		var plugin = this,
		s = options,
		$element = $(element),
		IE = $.browser.msie;
		// base setup of settings, mouse interaction, images load event handler 
		plugin.init = function(){
			s.minHeight = s.targetHeight-s.heightDeviation;
			s.maxHeight = s.targetHeight+s.heightDeviation;
			s.defaultHeightRatio = s.targetHeight/s.maxHeight;
			if(s.lightbox != "no" && s.lightbox != "links-off"){
				s.linkClass = (s.linkClass != '' ? ' class="'+s.linkClass+'" ' : "");
				switch(s.linkRel){
					case 'auto':
						switch(s.lightbox){
							case 'prettyphoto':
							s.linkRel = ' rel="prettyPhoto['+s.instance+']" ';
							break;
							case 'colorbox':
							s.linkRel = ' rel="colorBox['+s.instance+']" ';
							break;
							case 'custom':
							s.linkRel = ' rel="gallery['+s.instance+']" ';
							break;
						}
					break;
					case '':
						s.linkRel = "";
					break;
					default:
					s.linkRel = ' rel="'+s.linkRel+'" ';
				}
			}else{
				s.linkClass = "";
				s.linkRel = "";
			}
			s.allItems = s.items.slice();
			s.hiddenOpacity = !IE ? 0 : 0.01;
			s.errorChecked = false;
			s.loadSuccess = 0;
			s.loadError = 0;
			s.errorImages = [];

			plugin.createGallery() // calls the gallery creation function
			
			// mouseenter and mouseleave functions
			var emptyFunc = function(){return false;},
			overlayFxEnter = desaturateFxEnter = captionFxEnter = overlayFxLeave = desaturateFxLeave = captionFxLeave = emptyFunc,
			FxOpacityIn = function(el, findVal, eType){ el.find(findVal).hoverFlow(eType, {'opacity': 'show'}, s.animSpeed) },
			FxOpacityInIE = function(el, findVal, eType){ el.find(findVal).hoverFlow(eType, {'opacity': 1}, s.animSpeed) },
			FxOpacityOut = function(el, findVal, eType){ el.find(findVal).hoverFlow(eType, {'opacity': 'hide' }, s.animSpeed); },
			FxOpacityOutIE = function(el, findVal, eType){ el.find(findVal).hoverFlow(eType, {'opacity': 0.01 }, s.animSpeed); },
			FxHeightIn = function(el, findVal, eType){ el.find(findVal).hoverFlow(eType, {'height': 'show'}, s.animSpeed); },
			FxHeightOut = function(el, findVal, eType){ el.find(findVal).hoverFlow(eType, {'height': 'hide' }, s.animSpeed); };

			// overlay animation controls
			switch(s.overlay){
				case 'hovered':
				var overlayFxEnter = function(el){FxOpacityIn(el, "div.jig-overlay-wrapper", 'mouseenter')};
				var overlayFxLeave = function(el){FxOpacityOut(el, "div.jig-overlay-wrapper", 'mouseleave')};
				break;
				case 'others':
				var overlayFxEnter = function(el){FxOpacityOut(el, "div.jig-overlay-wrapper", 'mouseenter')};
				var overlayFxLeave = function(el){FxOpacityIn(el, "div.jig-overlay-wrapper", 'mouseleave')};
				break;
				default:
			}

			// desaturate animation controls
			if(!IE){
				switch(s.desaturate){
					case 'others':
					var desaturateFxEnter = function(el){FxOpacityOut(el, ".jig-desaturated", 'mouseenter')};
					var desaturateFxLeave = function(el){FxOpacityIn(el, ".jig-desaturated", 'mouseleave')};
					break;
					case 'hovered':
					var desaturateFxEnter = function(el){FxOpacityIn(el, ".jig-desaturated", 'mouseenter')};
					var desaturateFxLeave = function(el){FxOpacityOut(el, ".jig-desaturated", 'mouseleave')};
					break;
					default:
				}
			}else{ // for some reason old IE doesn't like the grayscale filter and opacity 0 at the same time
				switch(s.desaturate){
					case 'others':
					var desaturateFxEnter = function(el){FxOpacityOutIE(el, ".jig-desaturated", 'mouseenter')};
					var desaturateFxLeave = function(el){FxOpacityInIE(el, ".jig-desaturated", 'mouseleave')};
					break;
					case 'hovered':
					var desaturateFxEnter = function(el){FxOpacityInIE(el, ".jig-desaturated", 'mouseenter')};
					var desaturateFxLeave = function(el){FxOpacityOutIE(el, ".jig-desaturated", 'mouseleave')};
					break;
					default:
				}
			}

			// caption animation controls
			switch(s.caption){
				case 'fade':
				var captionFxEnter = function(el){FxOpacityIn(el, "div.jig-caption", 'mouseenter')};
				var captionFxLeave = function(el){FxOpacityOut(el, "div.jig-caption", 'mouseleave')};

				break;
				case 'slide':
				if(!IE){
					var captionFxEnter = function(el){FxHeightIn(el, "div.jig-caption", 'mouseenter')};
					var captionFxLeave = function(el){FxHeightOut(el, "div.jig-caption", 'mouseleave')};
				}else{
					var captionFxEnter = function(el){FxOpacityIn(el, "div.jig-caption", 'mouseenter')};
					var captionFxLeave = function(el){FxOpacityOut(el, "div.jig-caption", 'mouseleave')};
				}
				break;
				case 'mixed':
				var captionFxEnter = function(el){FxHeightIn(el, "div.jig-caption-description-wrapper", 'mouseenter')};
				var captionFxLeave = function(el){FxHeightOut(el, "div.jig-caption-description-wrapper", 'mouseleave')};
				break;
				default:
			}

			// calls the animation functions on mouse interaction, also removes and readds title to avoid ugly tooltips
			$element.on("mouseenter mouseleave", "a", function(event){
				var $this = $(this);
				if($this.css('display') != 'none'){
					event.stopImmediatePropagation();
					if(event.type === "mouseenter"){
						overlayFxEnter($this);
						desaturateFxEnter($this);
						captionFxEnter($this);
						$this.data('title',$this.attr('title'));
						$this.removeAttr('title');
					}else{
						overlayFxLeave($this);
						desaturateFxLeave($this);
						captionFxLeave($this);
						$this.attr('title',$this.data('title'));
					}
				}
			});

			// re-adds title upon mousedown (for lightbox scripts)
			$element.on("mousedown", "a", function(event){
				$(this).attr('title',$(this).data('title'));
			})
		}; // end of init

		// builds/rebuilds the gallery, calls functions that create the rows and the adds/updates all the image elements
		plugin.createGallery = function(mode){
			$element.css('width', "").css('width', $element.width());
			var newAreaWidth = $element.width() - 1;
			s.justResized = false;
			if(mode && mode == 'resize'){
				if(s.areaWidth && s.areaWidth == newAreaWidth){
					return;
				}else{
					s.justResized = true;
				}
			}
			$element.find('.jig-overflow a *:not(div)').off()
			s.areaWidth = newAreaWidth;
			s.row = [];
			s.fullWidth = s.extra = 0;
			s.rows = [];
			s.items = s.allItems.slice();
			if(s.errorChecked == true && s.justResized == false){
				for(var p in s.items){
					if($.inArray(s.items[p].url, s.errorImages) != -1){
						s.items.splice(p,1);
					}
				}
				s.allItems = s.items.slice();

				s.errorImages = [];
						s.errorChecked = false;

			}

			// calculates dimensions and everything else for all the image elements, builds the rows
			if(s.maxRows == '' || s.maxRows == 0){
				s.maxRows = 1000;
			}
			s.rowcount = 0;
			s.imagesShown = 0;
			// until the image source is depleted or the rows reach maximum set, whichever occurs first

			while(s.items.length > 0 && s.rowcount < s.maxRows){
				s.rows.push(buildImageRow());
				s.rowcount++
			}
			// keeps track of images that should be loaded
			s.imagesShown += s.allItems.length - s.items.length;

			// removes leftover images
			$element.find('.jig-imageContainer:gt('+(s.imagesShown-1)+')').remove()
			// keeps track of images that are actually added
			s.imagesAlreadyAdded = $element.find(".jig-imageContainer").length
			// goes through every image of every row
			var imageCount = 0;
			for(var r in s.rows){
				for(var i in s.rows[r]){
					imageCount++;
					var item = s.rows[r][i];			
					if(item.container && imageCount <= s.imagesAlreadyAdded){
						// updates image elements that already exist
						updateImageElement(item, s.rows[r].length, i);
					}else{
						// adds image elements not yet created
						createImageElement(item, s.rows[r].length, i);
					}
				}
			}

			$element.css('width', "").css('width', $element.width());
			// recalculates everything if the available width has been clipped due to the scrollbar that just appeared
			if(s.areaWidth != $element.width() - 1){
				//$(window).resize()
				plugin.createGallery('resize');
				if(s.instance > 1){
					$('#jig'+(s.instance-1)).data('justifiedImageGrid').createGallery('resize');
				}
			}

			$("img", $element).load(function(){
				var a = $(this).closest("a")
				if(a.length != 0 && a.hasClass('jig-loaded') !== true){
					a.addClass('jig-loaded')
					s.testTx = "nullx"	
					if(a.css('display') == 'none'){
							a.fadeIn(s.animSpeed); 
					}
					if(s.errorChecking == 'yes'){
						s.loadSuccess++
						checkLoadResults()
					}
					if(s.desaturate != "off"){
						var imgDesat = $(this).clone().addClass("jig-desaturated").insertAfter($(this));
						imgDesat.load(function(){	
						if($(this).hasClass("jig-desat-complete") !== true){
							var par = $(this).parent()
							$(this).stop().css("display","block").css("opacity",1);
							Pixastic.process(this, "desaturate", {average : false});
							if(s.desaturate == "hovered"){
								if(!IE){
									par.find(".jig-desaturated").css("display","none").css("opacity",1);
								}else{
									par.find(".jig-desaturated").css("opacity",s.hiddenOpacity);
								}
							}
							$(this).addClass("jig-desat-complete")
						}else{
							$(this).off("load")
						}
						}).each(function(){
							if(this.complete || (this.naturalWidth != undefined && this.naturalWidth != 0)){
								$(this).trigger("load");
							}
						});
					}
				}else{
					$(this).off("load")
				}
			}).error(function(){
				if(s.errorChecking == 'yes'){
					var match = /(?:\?src=)(.*)(?:&h=)/g.exec($(this).attr('src'))
					s.errorImages.push(match[1]);
					$(this).closest('.jig-imageContainer').addClass('jig-unloadable')
					s.loadError++;
					checkLoadResults()
				}
			}).each(function(){
				if(this.complete || (this.naturalWidth != undefined && this.naturalWidth != 0)){
					$(this).trigger("load");
				}
			});

			// just an IE fix to make the overlay cover the entire image
			if($.browser.msie && $.browser.version < 8 && s.overlay != "off"){
				$(".jig-overlay").css({"position": "absolute", "bottom": 0,	"left": 0, "right": 0, "top": 0});
			}

			// removes clickability and hand cursor when links are turned off
			// registers lightbox scripts
			switch(s.lightbox){
				case 'prettyphoto':
				case 'colorbox':
					s.lightboxInit()
				break;
				case 'links-off':
					$element.find("a").css("cursor","default");
					$element.on("click", "a", function(event){
						event.preventDefault();
						return;
					})
				break;
			}
		}; // end of createGallery

		// builds the rows of images
		// takes the overall average aspect ratio of the row into consideration,
		// to decide whether to shrink or enlarge the images when row height deviation is enabled
		// when it's not enabled (fixed row height), or it can't fit the images into the row
		// by enlarging or shrinking while maintaining aspect ratio,
		// then it'll just crop off left and right sides of the images, keeping them at the target height
		var buildImageRow = function(){
			s.row = [];
			s.fullWidth = 0;
			s.extra = 0;
			// builds a row to see how wide it would be when the last image pokes out of the row
			while(s.items.length > 0 && s.extra < s.areaWidth){
				var item = s.items.shift();
				item.newHeight = item.newWidth = item.containerHeight = item.containerWidth = item.marLeft = undefined;
				item.ratio = item.width/s.maxHeight;
				s.row.push(item)
				s.fullWidth += Math.round(item.width*s.defaultHeightRatio) + s.margins;
				s.extra = s.fullWidth - s.margins;
			}
			// s.extra is the extra pixels the last image uses after the available width
			s.extra -= s.areaWidth;
			// if the line is too long, make images smaller/larger(by popping one)
			if((s.row.length > 0 && s.extra > 0) || s.rows.length == 0){
				var orientation = "landscape";
				for(var i in s.row){
					if(s.row[i].ratio < 1){
						orientation = "portrait";
						break;
					}
				}
				if(orientation == "landscape"){ // if they are only landscape
					tryShrink(); // tries to shrink
				}else{ // if they have a portrait
					tryGrow(); // tries to enlarge 
				}
			}else{ // rare case when all images fit in (and/or under) the row with the default height (commonly the last row)
				if(s.items.length == 0){ // this is the last row because no more images left
					switch(s.incompleteLastRow){
						case 'hide':
							tryGrow('hide')
						break;
						case 'match':
							var prevRowHeight;
							var lastRowID = 0;
							for(var r in s.rows){						
								lastRowID = r;
							}
							prevRowHeight = s.rows[lastRowID][0].containerHeight ? s.rows[lastRowID][0].containerHeight : s.rows[lastRowID][0].newHeight; // this doesn't change over the previous row
							s.marginsTotal = (s.row.length-1)*s.margins;
							s.rowlen = 0;
							for(var i in s.row){

								if(s.rows[lastRowID][i] != undefined && s.row[i].width == s.rows[lastRowID][i].width){ // if the source picture is same kind, treat it the same way
									s.row[i].newHeight = s.rows[lastRowID][i].newHeight;	
									s.row[i].containerHeight = s.rows[lastRowID][i].containerHeight;
									s.row[i].newWidth = s.rows[lastRowID][i].newWidth;
									s.row[i].containerWidth = s.rows[lastRowID][i].containerWidth;
									s.row[i].marLeft = s.rows[lastRowID][i].marLeft;
								}else{
									s.row[i].newHeight = prevRowHeight;		
									s.row[i].newWidth = Math.round(s.row[i].newHeight*s.row[i].ratio);
								}
								s.rowlen += s.row[i].newWidth;
							}
							if(prevRowHeight > s.targetHeight){
								s.remaining = s.rowlen+s.marginsTotal-s.areaWidth;
								if(s.remaining > 0){
									finalize();
								}
							}
						break;
						case 'normal':
						default:
							tryGrow('lastRow')
						break;
					}
					return s.row;
				}else{
					for(var i in s.row){
						var item = s.row[i];
						item.marLeft = 0;
						item.newHeight = s.targetHeight;
						item.newWidth = Math.round(item.newHeight*item.ratio);
					}
				}
			}
			return s.row;
		}; // end of buildImageRow
		
		// tries to build the row by shrinking the images
		// failure happens when it can only do that by going below the minimum height
		// then it'll skip to the enlarge function
		var tryShrink = function(){
			var doFinalize = true;
			s.marginsTotal = (s.row.length-1)*s.margins;
			s.rowlen = 0;
			s.heights = [];
			for(var i in s.row){
				var targetWidth = Math.round(s.row[i].width*s.defaultHeightRatio),
				shrinkby = Math.round(((targetWidth+s.marginsTotal/s.row.length)/s.fullWidth)*s.extra);
				s.row[i].newWidth = (targetWidth-shrinkby);
				s.heights[i] = s.row[i].newWidth/s.row[i].ratio;
				if(s.heights[i] < s.minHeight){
					tryGrow();
					return;
				}
				if(s.heights[i] > s.maxHeight){
					s.row[i].newHeight = s.targetHeight;
					s.row[i].newWidth = Math.round(s.row[i].newHeight*s.row[i].ratio);
					doFinalize = false;
					continue;
				}
				s.row[i].newHeight = s.heights[i];
				s.rowlen += s.row[i].newWidth;
			}
			// there can be a few pixels that remain due to rounding, and they need to be taken care of later
			if(doFinalize){
				s.remaining = s.rowlen+s.marginsTotal-s.areaWidth;
				finalize();
			}
			return;
		}; // end of tryShrink

		// tries to build the row by enlarging the images (and moving the last one to the next row)
		// it fails when the images go above the maximum height
		// upon failure it'll give up enlarging or shrinking and will just crop (gets back the last image)
		var tryGrow = function(incompleteRow){
			var doFinalize = true;
			if(s.row.length != 1 && incompleteRow == undefined){
				var leftover = s.row.pop();
				s.fullWidth -= Math.round(leftover.width*s.defaultHeightRatio) + s.margins;
				s.items.unshift(leftover);
				s.extra = s.fullWidth - s.margins;
				s.extra -= s.areaWidth;
				var removed = true;
			}
			s.marginsTotal = (s.row.length-1)*s.margins;
			s.rowlen = 0;
			s.heights = [];
			for(var i in s.row){
				var targetWidth = Math.round(s.row[i].width*s.defaultHeightRatio);
				var growby = Math.round(((targetWidth+s.marginsTotal/s.row.length)/ s.fullWidth)*s.extra);
				s.row[i].newWidth = (targetWidth-growby);
				s.heights[i] = s.row[i].newWidth/s.row[i].ratio;
				if(s.heights[i] > s.maxHeight){
					if(incompleteRow == undefined){
						var item = s.items.shift();
						s.row.push(item);
						s.fullWidth += Math.round(item.width*s.defaultHeightRatio) + s.margins;
						s.extra = s.fullWidth - s.margins;
						s.extra -= s.areaWidth;
						doCrop();
						return;
					}else{
						if(incompleteRow == 'lastRow'){
							s.row[i].newHeight = s.targetHeight;
							s.row[i].newWidth = Math.round(s.row[i].newHeight*s.row[i].ratio);
							doFinalize = false;
							continue;
						}else{
							s.row[i].newWidth = undefined;
							s.imagesShown--;
							doFinalize = false;
						}				
					}				
				}else if(s.heights[i] < s.minHeight && incompleteRow == undefined){ // it'll need to default to cropping after all, if it's fixed height
				doCrop();
				return;
				}
				if(s.row[i].newWidth != undefined){
					s.row[i].newHeight = s.heights[i];
					s.rowlen += s.row[i].newWidth;
				}
			}
			if(doFinalize){
				s.remaining = s.rowlen+s.marginsTotal-s.areaWidth;
				finalize();
			}
			return;
		}; // end of tryGrow

		// this makes the rows perfect by cropping or adding pixels whenever needed and possible
		// it makes sure every row is truly justified even if it means cropping a few pixels off the bottom of some images
		// that is because it'll re-shrink or re-enlarge images to make the +- pixels happen, then they won't have the same height anymore
		// it'll distribute / take away pixels by taking into consideration the relative size of each image to the row
		var finalize = function(){
			if(s.remaining != 0){
				if(s.remaining > 0){ // if positive, then an excess of pixels need to be removed (shrink images)
					while(s.remaining > 0){
						for(var i in s.row){
							s.row[i].newWidth--;
							s.row[i].newHeight = s.heights[i] = s.row[i].newWidth/s.row[i].ratio;
							s.remaining--;
							if(s.remaining == 0) break;
						}
					}
				}else{ // if negative, the row needs more pixels (enlarge images)
					while(s.remaining < 0){
						for(var i in s.row){
							s.row[i].newWidth++;
							s.row[i].newHeight = s.heights[i] = s.row[i].newWidth/s.row[i].ratio;
							s.remaining++;
							if(s.remaining == 0) break;
						}
					}
				}
			}
			// finds the smallest (safe) height and matches all the other images to that height by cropping
			s.heights.sort(function(a,b){return a-b});
			var safeMinimumHeight = Math.floor(s.heights[0]);
			for(var i in s.row){
				s.row[i].containerHeight = safeMinimumHeight;
				s.row[i].newHeight = Math.round(s.row[i].newHeight);
			}
		} // end of finalize

		// does the croppnig by reducing the image container's width and by setting a left border
		// cropping happens often if the row height is fixed
		var doCrop = function(){
			var crop = getCrop();
			for(var i in s.row){
				var unWanted = crop[i];
				var item = s.row[i];
				item.marLeft = Math.round(unWanted/2);
				item.containerWidth = item.newWidth-unWanted;
			}
			return;
		}; // end of doCrop

		// calculates the actual pixels to crop by and distributes them over all the images
		// taking into consideration their relative size to the row		
		var getCrop = function(){
			var crop = [];
			var cropTotal = 0;
			s.marginsTotal = (s.row.length-1)*s.margins;
			for(var i in s.row){
				var item = s.row[i],
				targetWidth = Math.round(s.row[i].width*s.defaultHeightRatio);
				item.newHeight = s.targetHeight;
				item.newWidth = targetWidth;
				crop[i] = Math.round(((targetWidth+s.marginsTotal/s.row.length)/ s.fullWidth)* s.extra);
				cropTotal += crop[i];
			}

			// similar to finalize after shrink/grow, there can be a few  +- pixels that remain due to rounding
			var cropRemain = s.extra - cropTotal;
			if(cropRemain != 0){
				if(cropRemain > 0){
					while(cropRemain > 0){
						for(i in crop){
							// add pixels
							crop[i]++;
							cropRemain--;
							if(cropRemain == 0) break;
						}
					}
				}else{
					while(cropRemain < 0){
						for(i in crop){
							// remove pixels
							crop[i]--;
							cropRemain++;
							if(cropRemain == 0) break;
						}
					}		
				}
			}
			return crop;
		}; // end of getCrop

		// creates the actual container element that holds the link, image, captions, overlay, and all the wrapper divs
		// used by createGallery
		var createImageElement = function(item, rowlength, id){
			if(isNaN(item.newWidth)){
				return;
			}
			item['title'] = item['title'] ? item['title'] : '';
			item['caption'] = item['caption'] ? item['caption'] : '';
			item['description'] = item['description'] ? item['description'] : '';
			item['alternate'] = item['alternate'] ? item['alternate'] : '';
			item['link'] = item['link'] ? item['link'] : '';
			var imageContainer = $('<div class="jig-imageContainer"/>'),
			overflow = $('<div class="jig-overflow"/>'),
			href = item.url;
			linkClass = s.linkClass;
			linkRel = s.linkRel;
			if(item.link){
				href = item.link;
				linkClass = ' class="jig-customLink" ';
				linkRel = "";
			}
			link = $('<a' + linkClass + linkRel + ' title="'+item[s.linkTitleField]+'" href="' + (s.lightbox != "links-off" ? href : "#") + '"/>'),
			img = $("<img/>");
			link.hide();
			if(id==rowlength-1){
				imageContainer.css("margin-right", 0);
			}
			overflow.css("width", (item.containerWidth ? item.containerWidth : item.newWidth) + "px");
			overflow.css("height", (item.containerHeight ? item.containerHeight : item.newHeight) + "px");
			ext = '';

			var itemurl = item.url;
			if(itemurl.lastIndexOf(".") > 2){
				ext = "&f=" + itemurl.substring(itemurl.lastIndexOf("."));
			}
			img.attr("src", s.timthumb + "?src=" + itemurl + "&h=" + s.maxHeight + "&q=" + s.quality + ext );
			img.attr("alt", item[s.imgAltField]);
			img.css("width", item.newWidth + "px");
			img.css("height", item.newHeight + "px");
			if(item.marLeft){
				img.css("margin-left", -item.marLeft + "px");
			}
			img.css("margin-top", 0);
			link.append(img);
			if(s.overlay != "off"){
				link.append('<div class="jig-overlay-wrapper"><div class="jig-overlay"></div></div>');
			}
			if(s.caption != "off"){
				var captionContent = '';
				if(item[s.titleField] != ''){
					captionContent += '<div class="jig-caption-title">'+item[s.titleField]+'</div>';
				}
				if(item[s.captionField] != ''){
					captionContent += '<div class="jig-caption-description-wrapper"><div class="jig-caption-description'+(captionContent != '' ? '' : ' jig-alone')+'">'+item[s.captionField]+'</div></div>';
				}
				if(captionContent != ''){
					captionContent = '<div class="jig-caption-wrapper"><div class="jig-caption">'+captionContent+'</div></div>';
					link.append(captionContent);
				}
			}
			overflow.append(link);
			imageContainer.append(overflow);
			$element.find(".jig-clearfix").before(imageContainer);
			item.container = imageContainer;
			item.overflow = overflow;
			item.img = img;
			return imageContainer;
		}; // end of createImageElement
		
		// updates an existing image container element with the newly calculated dimensions and margin data
		// used by createGallery
		// checks for desaturated neighbour
		var updateImageElement = function(item, rowlength, id){
			if(id==rowlength-1){
				item.container.css("margin-right", 0);
			}else{
				item.container.css("margin-right", "");
			}
			var overflow = item.overflow;
			overflow.css("width", (item.containerWidth ? item.containerWidth : item.newWidth) + "px");
			overflow.css("height", (item.containerHeight ? item.containerHeight : item.newHeight) + "px");
			var img = item.img;
			img.css("width", item.newWidth + "px");
			img.css("height", item.newHeight + "px");
			if(item.marLeft){
				img.css("margin-left", -item.marLeft + "px");
			}else{
				img.css("margin-left","");
			}
			if(s.desaturate != "off"){
				checkForDesaturated(img.siblings('.jig-desaturated'), img);
			}

		}; // end of updateImageElement


		// recursive function that checks if the neighbour of an existing image has been desaturated or not
		// it's necessary because it could be queued at the time of check (when the original image isn't loaded yet)
		// as the window can be resized at any time, the desaturate processes will need to start over with the new dimensions
		// so it'll wait for it then get rid of the desaturated neighbour and will replace it with a new one
		var checkForDesaturated = function(neighbour, img){
			if(neighbour.length != 0){
				neighbour.addClass("jig-removeThis");
				var imgDesat = img.clone().addClass("jig-desaturated").insertAfter(img);
				
				imgDesat.load(function(){	
					if($(this).hasClass("jig-desat-complete") !== true){										
						$(this).stop().css("display","block").css("opacity",1)
						Pixastic.process(this, "desaturate", {average : false});
						if(s.desaturate == "hovered"){
							if(!IE){
								img.next().css("display","none").css("opacity",1);
							}else{
								img.next().css("opacity",s.hiddenOpacity);
							}
						}else{
							img.next().css("opacity",1);
						}
						$(this).addClass("jig-desat-complete")
						img.siblings('.jig-removeThis').remove()
					}else{
						$(this).off("load")
					}
				}).each(function(){
					if(this.complete || (this.naturalWidth != undefined && this.naturalWidth != 0)){
						$(this).trigger("load");
					}
				});

			}else{
				img.load(function(){
					checkForDesaturated(neighbour, img);
				})
			}
							/*if($.browser.opera){
					img.next().stop().css("display","block").css("opacity",1).pixastic("desaturate", {average : false});
					if(s.desaturate == "hovered"){
						img.next().css("display","none").css("opacity",1);
					}else{
						img.next().css("opacity",1);
					}
				}
				imgDesat.load(function(){
					$(this).stop().css("display","block").css("opacity",1)
					Pixastic.process(this, "desaturate", {average : false});
					if(s.desaturate == "hovered"){
						if(!IE){
							img.next().css("display","none").css("opacity",1);
						}else{
							img.next().css("opacity",s.hiddenOpacity);
						}
					}else{
						img.next().css("opacity",1);
					}
				})*/
		} // end of checkForDesaturated

		// checks if all images have been loaded, restarts when complete but error images encountered
		s.errorChecked = false;
		var checkLoadResults = function(){	
			if(($('.jig-loaded').length == s.imagesShown || $('.jig-loaded').length+s.loadError == s.imagesShown)){
				if(s.loadError != 0){
					s.loadSuccess = 0;
					s.loadError = 0;
					s.errorChecked = true;
					$element.find('.jig-unloadable').remove()
					plugin.createGallery('errorCheck');
				}else{
					s.loadSuccess = 0;
					s.loadError = 0;
				}
			}
		}

		plugin.init();

	}; // end of 'class'

	// sets up the plugin to be used conveniently and makes later access possible
	$.fn.justifiedImageGrid = function(options){
		return this.each(function(){
			if(undefined == $(this).data('justifiedImageGrid')){
				var plugin = new $.justifiedImageGrid(this, options);
				$(this).data('justifiedImageGrid', plugin);
			}
		});
	};

})(jQuery);
/*
* hoverFlow - A Solution to Animation Queue Buildup in jQuery
* Version 1.00
*
* Copyright (c) 2009 Ralf Stoltze, http://www.2meter3.de/code/hoverFlow/
* Dual-licensed under the MIT and GPL licenses.
* http://www.opensource.org/licenses/mit-license.php
* http://www.gnu.org/licenses/gpl.html
*/
(function($) {
	$.fn.hoverFlow = function(type, prop, speed, easing, callback) {
		// only allow hover events
		if ($.inArray(type, ['mouseover', 'mouseenter', 'mouseout', 'mouseleave']) == -1) {
			return this;
		}

		// build animation options object from arguments
		// based on internal speed function from jQuery core
		var opt = typeof speed === 'object' ? speed : {
			complete: callback || !callback && easing || $.isFunction(speed) && speed,
			duration: speed,
			easing: callback && easing || easing && !$.isFunction(easing) && easing
		};
		
		// run immediately
		opt.queue = false;

		// wrap original callback and add dequeue
		var origCallback = opt.complete;
		opt.complete = function() {
			// execute next function in queue
			$(this).dequeue();
			// execute original callback
			if ($.isFunction(origCallback)) {
				origCallback.call(this);
			}
		};
		
		// keep the chain intact
		return this.each(function() {
			var $this = $(this);

			// set flag when mouse is over element
			if (type == 'mouseover' || type == 'mouseenter') {
				$this.data('jQuery.hoverFlow', true);
			} else {
				$this.removeData('jQuery.hoverFlow');
			}
			
			// enqueue function
			$this.queue(function() {				
				// check mouse position at runtime
				var condition = (type == 'mouseover' || type == 'mouseenter') ?
					// read: true if mouse is over element
					$this.data('jQuery.hoverFlow') !== undefined :
					// read: true if mouse is _not_ over element
					$this.data('jQuery.hoverFlow') === undefined;
					
				// only execute animation if condition is met, which is:
				// - only run mouseover animation if mouse _is_ currently over the element
				// - only run mouseout animation if the mouse is currently _not_ over the element
				if(condition) {
					$this.animate(prop, opt);
				// else, clear queue, since there's nothing more to do
			} else {
				$this.queue([]);
			}
		});

		});
	};
})(jQuery);