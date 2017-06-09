/*! ImagePress - dev - Copyright 2014 */
(function($){
    jQuery.fn.jConfirmAction = function(options){
        var theOptions = jQuery.extend({
            question: 'Are you sure you want to delete this image? This action is irreversible!',
            yesAnswer: 'Yes',
            cancelAnswer: 'No'
        }, options);

        return this.each(function(){
            $(this).bind('click', function(e){
                e.preventDefault();
                thisHref = $(this).attr('href');
                if($(this).next('.question').length <= 0)
                    $(this).after('<div class="question"><i class="fa fa-exclamation-triangle"></i> ' + theOptions.question + '<br><span class="yes button noir-secondary">' + theOptions.yesAnswer + '</span><span class="cancel button noir-default">' + theOptions.cancelAnswer + '</span></div>');

                $(this).next('.question').animate({opacity: 1}, 300);
                $('.yes').bind('click', function(){
                    window.location = thisHref;
                });

                $('.cancel').bind('click', function(){
                    $(this).parents('.question').fadeOut(300, function() {
                        $(this).remove();
                    });
                });
            });
        });
    }
})(jQuery);



jQuery.fn.extend({
    greedyScroll: function(sensitivity) {
        return this.each(function() {
            jQuery(this).bind('mousewheel DOMMouseScroll', function(evt) {
               var delta;
               if (evt.originalEvent) {
                  delta = -evt.originalEvent.wheelDelta || evt.originalEvent.detail;
               }
               if (delta !== null) {
                  evt.preventDefault();
                  if (evt.type === 'DOMMouseScroll') {
                     delta = delta * (sensitivity ? sensitivity : 20);
                  }
                  return jQuery(this).scrollTop(delta + jQuery(this).scrollTop());
               }
            });
        });
    }
});

function bytesToSize(bytes) {
	var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
	if (bytes == 0) return 'n/a';
	var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
	if (i == 0) return bytes + ' ' + sizes[i]; 
	return (bytes / Math.pow(1024, i)).toFixed(1) + ' ' + sizes[i];
};

jQuery(document).ready(function() {
	// inView
	loadVisibleImages();

	jQuery('.poster-container img').click(function(e){
        jQuery(this).toggleClass('ip-more-target');
    });

    // like action
    jQuery('body').on('click', '.imagepress-like', function(e){
        e.preventDefault();
        e.stopImmediatePropagation();
        like = jQuery(this);
        pid = like.data('post_id');
        like.html('<i class="fa fa-heart"></i> <i class="fa fa-spinner fa-spin"></i>');
        jQuery.ajax({
            type: 'post',
            url: ajax_var.ajaxurl,
            data: 'action=imagepress-like&nonce=' + ajax_var.nonce + '&imagepress_like=&post_id=' + pid,
            success: function(count){
                if(count.indexOf('already') !== -1) {
                    var lecount = count.replace('already', '');
                    if(lecount === '0') {
                        lecount = ajax_var.likelabel;
                    }
                    like.removeClass('liked');
                    like.html('<i class="fa fa-heart"></i> ' + lecount);
                }
                else {
                    count = ajax_var.unlikelabel;
                    like.addClass('liked');
                    like.html('<i class="fa fa-heart-o"></i> ' + count);
                }
            }
        });
        return false;
    });

	// begin upload
	var fileInput = jQuery('#imagepress_image_file');
	var maxSize = fileInput.data('max-size');
	var maxWidth = fileInput.data('max-width');
	jQuery('#imagepress_image_file').change(function(e){
		if(fileInput.get(0).files.length){
			var fileSize = fileInput.get(0).files[0].size; // in bytes
			if(fileSize>maxSize) {
				jQuery('#imagepress-errors').append('<p>Warning: File size is too big (' + bytesToSize(fileSize) + ')!</p>');
				jQuery('#imagepress_submit').attr('disabled', true);
				return false;
			}
			else {
				jQuery('#imagepress-errors').html('');
				jQuery('#imagepress_submit').removeAttr('disabled');
			}
		}
		else {
			//alert('choose file, please');
			return false;
		}
	});
	// end upload

    jQuery('#ip_taxonomy').change(function(){ this.form.submit(); });
    jQuery('#user').change(function(){ this.form.submit(); });
    jQuery('#ip_filter').change(function(){ this.form.submit(); });

    jQuery('.ip-editor-display').click(function(e){
        jQuery('.ip-editor').slideToggle('fast');
        e.preventDefault();
    });

    jQuery('.ask').jConfirmAction();

    // ip_editor() related actions
    jQuery('.delete-post').click(function(e){
        if(confirm('Delete this image?')) {
            jQuery(this).parent().parent().fadeOut();

            var id = jQuery(this).data('id');
            var nonce = jQuery(this).data('nonce');
            var post = jQuery(this).parents('.post:first');
            jQuery.ajax({
                type: 'post',
                url: ajax_var.ajaxurl,
                data: {
                    action: 'my_delete_post',
                    nonce: nonce,
                    id: id
                },
                success: function(result) {
                    if(result == 'success') {
                        post.fadeOut(function(){
                            post.remove();
                        });
                    }
                }
            });
        }
        e.preventDefault();
        return false;
    });
    jQuery('.featured-post').click(function(e){
        if(confirm('Set this image as main image?')) {
            jQuery(this).parent().parent().css('border', '3px solid #ffffff');

            var pid = jQuery(this).data('pid');
            var id = jQuery(this).data('id');
            var nonce = jQuery(this).data('nonce');
            var post = jQuery(this).parents('.post:first');
            jQuery.ajax({
                type: 'post',
                url: ajax_var.ajaxurl,
                data: {
                    action: 'my_featured_post',
                    nonce: nonce,
                    pid: pid,
                    id: id
                },
                success: function(result) {
                    if(result == 'success') {
                        /*
                        post.fadeOut(function(){
                            post.remove();
                        });
                        */
                    }
                }
            });
        }
        e.preventDefault();
        return false;
    });

    // notifications
	jQuery('.notifications-container .notification-item.unread').click(function(){
		var id = jQuery(this).data('id');
		jQuery.ajax({
			type: 'post',
			url: ajax_var.ajaxurl,
			data: {
				action: 'notification_read',
				id: id
			}
		});
	});

	// mark all as read
	jQuery('.ip_notification_mark').click(function(e){
		e.preventDefault();
		var userid = jQuery(this).data('userid');
		jQuery.ajax({
			type: 'post',
			url: ajax_var.ajaxurl,
			data: {
				action: 'notification_read_all',
				userid: userid
			}
		});

		//jQuery('.notifications-bell sup').hide();
		jQuery('.notifications-bell').html('<i class="fa fa-bell-o"></i><sup>0</sup>');
	});

	jQuery('.notifications-container .notifications-inner').greedyScroll(25);
    jQuery('.notifications-container').hide();
    jQuery('.notifications-bell').click(function(e){
        jQuery('.notifications-bell').toggleClass('on');
        jQuery('.notifications-container').toggle();
        e.preventDefault();
    });
    jQuery('.notifications-container').mouseleave(function(e){
        jQuery('.notifications-bell').removeClass('on');
        jQuery('.notifications-container').fadeOut('fast');
        e.preventDefault();
    });
    //










    // profile specific functions
	(function($) {
		$('.tab ul.tabs').addClass('active').find('> li:eq(0)').addClass('current');
        $('.tab ul.tabs li a:not(.imagepress-button)').click(function(g) { 
            var tab = $(this).closest('.tab'), 
                index = $(this).closest('li').index();

            tab.find('ul.tabs > li').removeClass('current');
            $(this).closest('li').addClass('current');

            tab.find('.tab_content').find('div.tabs_item').not('div.tabs_item:eq(' + index + ')').slideUp();
            tab.find('.tab_content').find('div.tabs_item:eq(' + index + ')').slideDown();

            g.preventDefault();
        });
    })(jQuery);

    // portfolio specific functions
    jQuery("#cinnamon-feature").hide();
    jQuery("#cinnamon-index").hide();
    jQuery(".cinnamon-grid-blank a").click(function(e) {
        e.preventDefault();
        var image = jQuery(this).attr("rel");
        jQuery("#cinnamon-feature").html('<img src="' + image + '" alt="">');
        jQuery("#cinnamon-feature").show();
        jQuery("#cinnamon-index").fadeIn();
    });
    jQuery("#cinnamon-index a").click(function(e) {
        e.preventDefault();
        jQuery("#cinnamon-feature").hide();
        jQuery("#cinnamon-index").hide();
    });
    jQuery(".c-index").click(function() {
        jQuery("#cinnamon-feature").hide();
        jQuery("#cinnamon-index").hide();
    });

    jQuery('#tab li:first').addClass('active');
    jQuery('.tab_icerik').hide();
    jQuery('.tab_icerik:first').show();
    jQuery('#tab li').click(function(e) {
        var index = jQuery(this).index();
        jQuery('#tab li').removeClass('active');
        jQuery(this).addClass('active');
        jQuery('.tab_icerik').hide();
        jQuery('.tab_icerik:eq(' + index + ')').show();
        return false
    });

    jQuery("#cinnamon_sort").change(function(){ this.form.submit(); });

	jQuery('.follow-links a').on('click', function(e) {
        e.preventDefault();
        var $this = jQuery(this);
        if(ajax_var.logged_in != 'undefined' && ajax_var.logged_in != 'true') {
            alert(ajax_var.login_required);
            return;
        }

        var data = {
            action: $this.hasClass('follow') ? 'follow' : 'unfollow',
			user_id: $this.data('user-id'),
			follow_id: $this.data('follow-id'),
			nonce: ajax_var.nonce
		};

        jQuery('img.pwuf-ajax').show();

        jQuery.post(ajax_var.ajaxurl, data, function(response) {
			if(response == 'success')
				jQuery('.follow-links a').toggle();
			else
				alert(ajax_var.processing_error);
			jQuery('img.pwuf-ajax').hide();
        });
	});

    jQuery('#imagepress_upload_image_form').submit(function(){
        jQuery('#imagepress_submit').prop('disabled', true);
        jQuery('#imagepress_submit').css('opacity', '0.5');
        jQuery('#ipload').html('<i class="fa fa-cog fa-spin"></i> Uploading...');
    });

	jQuery('.view').hide();
	jQuery('.slide').click(function() {
		jQuery('.view').slideToggle(100);
		return false;
	});



	jQuery('.initial i').addClass('teal');
	jQuery('.sort').click(function() {
		jQuery('.sort i').removeClass('teal');
		jQuery('i', this).addClass('teal');
	});


	// begin pagination
	if(jQuery('.pagination').length) {
		var paginationOptions = {
			outerWindow: 1
		};
		var monkeyList = new List('cinnamon-cards', {
			valueNames: ['imagetitle', 'name', 'location', 'followers', 'uploads', 'imageviews', 'imagecomments', 'imagelikes', 'imagecategory'],
			page: ajax_var.imagesperpage,
			indexAsync: true,
			plugins: [ ListPagination(paginationOptions) ]
		});
	}
	// end pagination

	jQuery('.imagecategory').click(function(){
		var tag = jQuery(this).data('tag')
		monkeyList.search(tag);
		jQuery('.search').val(tag);
	});

	// portfolio editor // color picker
	jQuery(".color_portfolio_bg").spectrum({
		color: "#ffffff",
		showInput: true,
		className: "full-spectrum",
		showInitial: true,
		showPalette: true,
		showSelectionPalette: true,
		maxPaletteSize: 10,
		preferredFormat: "hex",
		change: function(color) {
			jQuery("#hub_portfolio_bg").val(color.toHexString());        
			jQuery(".color_portfolio_bg").css("background-color", color.toHexString());        
		},
		palette: [
			["rgb(0, 0, 0)", "rgb(67, 67, 67)", "rgb(102, 102, 102)", "rgb(204, 204, 204)", "rgb(217, 217, 217)","rgb(255, 255, 255)"],
			["rgb(152, 0, 0)", "rgb(255, 0, 0)", "rgb(255, 153, 0)", "rgb(255, 255, 0)", "rgb(0, 255, 0)", "rgb(0, 255, 255)", "rgb(74, 134, 232)", "rgb(0, 0, 255)", "rgb(153, 0, 255)", "rgb(255, 0, 255)"],
			["rgb(230, 184, 175)", "rgb(244, 204, 204)", "rgb(252, 229, 205)", "rgb(255, 242, 204)", "rgb(217, 234, 211)", "rgb(208, 224, 227)", "rgb(201, 218, 248)", "rgb(207, 226, 243)", "rgb(217, 210, 233)", "rgb(234, 209, 220)", "rgb(221, 126, 107)", "rgb(234, 153, 153)", "rgb(249, 203, 156)", "rgb(255, 229, 153)", "rgb(182, 215, 168)", "rgb(162, 196, 201)", "rgb(164, 194, 244)", "rgb(159, 197, 232)", "rgb(180, 167, 214)", "rgb(213, 166, 189)", "rgb(204, 65, 37)", "rgb(224, 102, 102)", "rgb(246, 178, 107)", "rgb(255, 217, 102)", "rgb(147, 196, 125)", "rgb(118, 165, 175)", "rgb(109, 158, 235)", "rgb(111, 168, 220)", "rgb(142, 124, 195)", "rgb(194, 123, 160)", "rgb(166, 28, 0)", "rgb(204, 0, 0)", "rgb(230, 145, 56)", "rgb(241, 194, 50)", "rgb(106, 168, 79)", "rgb(69, 129, 142)", "rgb(60, 120, 216)", "rgb(61, 133, 198)", "rgb(103, 78, 167)", "rgb(166, 77, 121)", "rgb(91, 15, 0)", "rgb(102, 0, 0)", "rgb(120, 63, 4)", "rgb(127, 96, 0)", "rgb(39, 78, 19)", "rgb(12, 52, 61)", "rgb(28, 69, 135)", "rgb(7, 55, 99)", "rgb(32, 18, 77)", "rgb(76, 17, 48)"]
		]
	});
	jQuery(".color_portfolio_text").spectrum({
		color: "#000000",
		showInput: true,
		className: "full-spectrum",
		showInitial: true,
		showPalette: true,
		showSelectionPalette: true,
		maxPaletteSize: 10,
		preferredFormat: "hex",
		change: function(color) {
			jQuery("#hub_portfolio_text").val(color.toHexString());        
			jQuery(".color_portfolio_text").css("background-color", color.toHexString());        
		},
		palette: [
			["rgb(0, 0, 0)", "rgb(67, 67, 67)", "rgb(102, 102, 102)", "rgb(204, 204, 204)", "rgb(217, 217, 217)","rgb(255, 255, 255)"],
			["rgb(152, 0, 0)", "rgb(255, 0, 0)", "rgb(255, 153, 0)", "rgb(255, 255, 0)", "rgb(0, 255, 0)", "rgb(0, 255, 255)", "rgb(74, 134, 232)", "rgb(0, 0, 255)", "rgb(153, 0, 255)", "rgb(255, 0, 255)"],
			["rgb(230, 184, 175)", "rgb(244, 204, 204)", "rgb(252, 229, 205)", "rgb(255, 242, 204)", "rgb(217, 234, 211)", "rgb(208, 224, 227)", "rgb(201, 218, 248)", "rgb(207, 226, 243)", "rgb(217, 210, 233)", "rgb(234, 209, 220)", "rgb(221, 126, 107)", "rgb(234, 153, 153)", "rgb(249, 203, 156)", "rgb(255, 229, 153)", "rgb(182, 215, 168)", "rgb(162, 196, 201)", "rgb(164, 194, 244)", "rgb(159, 197, 232)", "rgb(180, 167, 214)", "rgb(213, 166, 189)", "rgb(204, 65, 37)", "rgb(224, 102, 102)", "rgb(246, 178, 107)", "rgb(255, 217, 102)", "rgb(147, 196, 125)", "rgb(118, 165, 175)", "rgb(109, 158, 235)", "rgb(111, 168, 220)", "rgb(142, 124, 195)", "rgb(194, 123, 160)", "rgb(166, 28, 0)", "rgb(204, 0, 0)", "rgb(230, 145, 56)", "rgb(241, 194, 50)", "rgb(106, 168, 79)", "rgb(69, 129, 142)", "rgb(60, 120, 216)", "rgb(61, 133, 198)", "rgb(103, 78, 167)", "rgb(166, 77, 121)", "rgb(91, 15, 0)", "rgb(102, 0, 0)", "rgb(120, 63, 4)", "rgb(127, 96, 0)", "rgb(39, 78, 19)", "rgb(12, 52, 61)", "rgb(28, 69, 135)", "rgb(7, 55, 99)", "rgb(32, 18, 77)", "rgb(76, 17, 48)"]
		]
	});
	jQuery(".color_portfolio_link").spectrum({
		color: "#0000ff",
		showInput: true,
		className: "full-spectrum",
		showInitial: true,
		showPalette: true,
		showSelectionPalette: true,
		maxPaletteSize: 10,
		preferredFormat: "hex",
		change: function(color) {
			jQuery("#hub_portfolio_link").val(color.toHexString());        
			jQuery(".color_portfolio_link").css("background-color", color.toHexString());        
		},
		palette: [
			["rgb(0, 0, 0)", "rgb(67, 67, 67)", "rgb(102, 102, 102)", "rgb(204, 204, 204)", "rgb(217, 217, 217)","rgb(255, 255, 255)"],
			["rgb(152, 0, 0)", "rgb(255, 0, 0)", "rgb(255, 153, 0)", "rgb(255, 255, 0)", "rgb(0, 255, 0)", "rgb(0, 255, 255)", "rgb(74, 134, 232)", "rgb(0, 0, 255)", "rgb(153, 0, 255)", "rgb(255, 0, 255)"],
			["rgb(230, 184, 175)", "rgb(244, 204, 204)", "rgb(252, 229, 205)", "rgb(255, 242, 204)", "rgb(217, 234, 211)", "rgb(208, 224, 227)", "rgb(201, 218, 248)", "rgb(207, 226, 243)", "rgb(217, 210, 233)", "rgb(234, 209, 220)", "rgb(221, 126, 107)", "rgb(234, 153, 153)", "rgb(249, 203, 156)", "rgb(255, 229, 153)", "rgb(182, 215, 168)", "rgb(162, 196, 201)", "rgb(164, 194, 244)", "rgb(159, 197, 232)", "rgb(180, 167, 214)", "rgb(213, 166, 189)", "rgb(204, 65, 37)", "rgb(224, 102, 102)", "rgb(246, 178, 107)", "rgb(255, 217, 102)", "rgb(147, 196, 125)", "rgb(118, 165, 175)", "rgb(109, 158, 235)", "rgb(111, 168, 220)", "rgb(142, 124, 195)", "rgb(194, 123, 160)", "rgb(166, 28, 0)", "rgb(204, 0, 0)", "rgb(230, 145, 56)", "rgb(241, 194, 50)", "rgb(106, 168, 79)", "rgb(69, 129, 142)", "rgb(60, 120, 216)", "rgb(61, 133, 198)", "rgb(103, 78, 167)", "rgb(166, 77, 121)", "rgb(91, 15, 0)", "rgb(102, 0, 0)", "rgb(120, 63, 4)", "rgb(127, 96, 0)", "rgb(39, 78, 19)", "rgb(12, 52, 61)", "rgb(28, 69, 135)", "rgb(7, 55, 99)", "rgb(32, 18, 77)", "rgb(76, 17, 48)"]
		]
	});
	//



	// collections
	jQuery('.toggleModal').on('click', function(e){
		jQuery('.modal').toggleClass('active');
		e.preventDefault();
	});
	jQuery('.toggleFrontEndModal').on('click', function(e){
		jQuery('.frontEndModal').toggleClass('active');
		e.preventDefault();
	});
	jQuery('.toggleFrontEndModal .close').on('click', function(e){
		jQuery('.frontEndModal').toggleClass('active');
		e.preventDefault();
	});

    jQuery('.addCollection').click(function(e){
		jQuery('.addCollection').val('Creating...');
		jQuery('.collection-progress').fadeIn();
		jQuery.ajax({
			method: 'post',
			url: ajax_var.ajaxurl,
			data: {
				action: 'addCollection',
				collection_author_id: jQuery('#collection_author_id').val(),
				collection_title: jQuery('#collection_title').val(),
				collection_status: jQuery('#collection_status').val()
			}
		}).done(function(msg) {
			jQuery('.addCollection').val('Create another collection');
			jQuery('.collection-progress').hide();
			jQuery('.showme').fadeIn();
		});

		e.preventDefault();
	});

    jQuery(document).on('click', '.deleteCollection', function(e){
        jQuery('body').find('deleteCollection').hide();
        var ipc = jQuery(this).data('collection-id');
		jQuery.ajax({
			method: 'post',
			url: ajax_var.ajaxurl,
			data: {
				action: 'deleteCollection',
				collection_id: ipc,
			}
		}).done(function(msg) {
			jQuery('.ipc' + ipc).fadeOut();
			jQuery('.ip-loadingCollections').fadeOut();
		});

		e.preventDefault();
	});
    jQuery(document).on('click', '.deleteCollectionImage', function(e){
        var ipc = jQuery(this).data('image-id');
		jQuery.ajax({
			method: 'post',
			url: ajax_var.ajaxurl,
			data: {
				action: 'deleteCollectionImage',
				image_id: ipc,
			}
		}).done(function(msg) {
			jQuery('.ip_box_' + ipc).fadeOut();
			jQuery('.ip-loadingCollections').fadeOut();
		});

		e.preventDefault();
	});

	jQuery(document).on('click', '.collection-title', function(e){
		this.contentEditable = 'true';
	});
    jQuery(document).on('keyup', '.collection-title', function(e){
        var ipc = jQuery(this).data('collection-id');
		jQuery.ajax({
			method: 'post',
			url: ajax_var.ajaxurl,
			data: {
				action: 'editCollectionTitle',
				collection_id: ipc,
				collection_title: jQuery(this).text(),
			}
		}).done(function(msg) {
			jQuery('.ipc' + ipc).fadeIn('fast');
		});

		e.preventDefault();
	});
    jQuery(document).on('change', '.collection-status', function(e){
        var ipc = jQuery(this).data('collection-id');

		var option = this.options[this.selectedIndex];

		jQuery.ajax({
			method: 'post',
			url: ajax_var.ajaxurl,
			data: {
				action: 'editCollectionStatus',
				collection_id: ipc,
				collection_status: jQuery(option).val()
			}
		}).done(function(msg) {
			jQuery('.ipc' + ipc).fadeIn('fast');
		});

		e.preventDefault();
	});

	jQuery('.modal .close').click(function(e){
		jQuery.ajax({
			method: 'post',
			url: ajax_var.ajaxurl,
			data: {
				action: 'ip_collections_display',
			}
		}).done(function(msg) {
			jQuery('.collections-display').html(msg);
		});

		e.preventDefault();
	});
	jQuery('.imagepress-collections').click(function(e){
		jQuery('.ip-loadingCollections').show();
		jQuery.ajax({
			method: 'post',
			url: ajax_var.ajaxurl,
			data: {
				action: 'ip_collections_display',
			}
		}).done(function(msg) {
			jQuery('.collections-display').html(msg);
			jQuery('.ip-loadingCollections').fadeOut();
		});

		e.preventDefault();
	});

	jQuery(document).on('click', '.editCollection', function(e){
		var ipc = jQuery(this).data('collection-id');
		jQuery('.ip-loadingCollectionImages').show();

		jQuery.ajax({
			method: 'post',
			url: ajax_var.ajaxurl,
			data: {
				collection_id: ipc,
				action: 'ip_collection_display',
			}
		}).done(function(msg) {
			jQuery('.collections-display').html(msg);
			jQuery('.ip-loadingCollectionImages').fadeOut();
		});

		e.preventDefault();
	});
	// end collections
})

jQuery(window).load(function(){
    jQuery('#hub-loading').fadeOut(100);
});



// inView
// 0.2
// 
// loadVisibleImages() injected into list.pagination.js
//
function getViewportHeight() {
	if(window.innerHeight) {
		return window.innerHeight;
	}
	else if(document.body && document.body.offsetHeight) {
		return document.body.offsetHeight;
	}
	else {
		return 0;
	}
}
function inView(elem, nearThreshold) {
	var viewportHeight = getViewportHeight();
	var scrollTop = (document.documentElement.scrollTop ? document.documentElement.scrollTop : document.body.scrollTop);
	var elemTop = elem.offset().top;
	var elemHeight = elem.height();
	nearThreshold = nearThreshold || 0;
	if((scrollTop + viewportHeight + nearThreshold) > (elemTop + elemHeight)) {
		return true;
	}
	return false;
}

function loadVisibleImages() {
	jQuery('img[data-src]').each(function() {
		if(jQuery(this).is(':visible')) {
			if(inView(jQuery(this), 300)) {
				this.src = jQuery(this).attr('data-src');
			}
		}
	});
}

jQuery(window).scroll(function() {
	loadVisibleImages();
});









jQuery(function(){
	jQuery("#new_collection").hide();
	jQuery('#imagepress_collection').change(function() {
		if(jQuery(this).find('option:selected').val() == "other") {
			jQuery("#new_collection").show();
		} else {
			jQuery("#new_collection").hide();
		}
	});
	/**
	jQuery("#new_collection").keyup(function(ev){
		var othersOption = jQuery('#imagepress_collection').find('option:selected');
		if(othersOption.val() == "other") {
			ev.preventDefault();
			jQuery(othersOption).html(jQuery("#new_collection").val()); 
		} 
	});
	/**/
});
