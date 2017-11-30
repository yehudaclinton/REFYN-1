(function ($) {

refynUploader = {
	removeFile: function () {
		$(document).on( 'click', '.refyn_uploader_remove', function(event) { 
			$(this).hide();
			$(this).parents().parents().children( '.refyn_upload').attr( 'value', '' );
			$(this).parents( '.refyn_screenshot').slideUp();
			
			return false;
		});
	},
	
	mediaUpload: function () {
		jQuery.noConflict();
		
		var formfield, formID, upload_title, btnContent = true;
	
		$(document).on( 'click', 'input.refyn_upload_button', function () {
			formfield = $(this).prev( 'input').attr( 'id' );
			formID = $(this).attr( 'rel' );
			upload_title =  $(this).prev( 'input').attr( 'rel' );
								   
			tb_show( upload_title, 'media-upload.php?post_id='+formID+'&amp;title=' + upload_title + '&amp;refyn_uploader=yes&amp;TB_iframe=1' );
			return false;
		});
				
		window.original_send_to_editor = window.send_to_editor;
		window.send_to_editor = function(html) {
			if (formfield) {
				if ( $(html).html(html).find( 'img').length > 0 ) {
					itemurl = $(html).html(html).find( 'img').attr( 'src' );
				} else {
					var htmlBits = html.split( "'" );
					itemurl = htmlBits[1]; 
					var itemtitle = htmlBits[2];
					itemtitle = itemtitle.replace( '>', '' );
					itemtitle = itemtitle.replace( '</a>', '' );
				}
				var image = /(^.*\.jpg|jpeg|png|gif|ico*)/gi;
				var document = /(^.*\.pdf|doc|docx|ppt|pptx|odt*)/gi;
				var audio = /(^.*\.mp3|m4a|ogg|wav*)/gi;
				var video = /(^.*\.mp4|m4v|mov|wmv|avi|mpg|ogv|3gp|3g2*)/gi;
			  
				if (itemurl.match(image)) {
					btnContent = '<img class="refyn_uploader_image" src="'+itemurl+'" alt="" /><a href="#" class="refyn_uploader_remove refyn-plugin-ui-delete-icon">&nbsp;</a>';
				} else {
					html = '<a href="'+itemurl+'" target="_blank" rel="refyn_external">View File</a>';
					btnContent = '<div class="refyn_no_image"><span class="refyn_file_link">'+html+'</span><a href="#" class="refyn_uploader_remove refyn-plugin-ui-delete-icon">&nbsp;</a></div>';
				}
				$( '#' + formfield).val(itemurl);
				$( '#' + formfield).siblings( '.refyn_screenshot').slideDown().html(btnContent);
				tb_remove();
			} else {
				window.original_send_to_editor(html);
			}
			formfield = '';
		}
	}
};
	
	$(document).ready(function () {

		refynUploader.removeFile();
		refynUploader.mediaUpload();
	
	});

})(jQuery);
