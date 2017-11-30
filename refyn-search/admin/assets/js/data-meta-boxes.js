jQuery( function ( $ ) {

	// TABS
	$('ul.refyn-metabox-data-tabs').show();
	$('div.refyn-metabox-panel-wrap').each(function(){
		$(this).find('div.refyn-metabox-panel:not(:first)').hide();
	});
	$('ul.refyn-metabox-data-tabs a').click(function(){
		var panel_wrap =  $(this).closest('div.refyn-metabox-panel-wrap');
		$('ul.refyn-metabox-data-tabs li', panel_wrap).removeClass('active');
		$(this).parent().addClass('active');
		$('div.refyn-metabox-panel', panel_wrap).hide();
		$( $(this).attr('href') ).show();
		return false;
	});
	$('ul.refyn-metabox-data-tabs li:visible').eq(0).find('a').click();

	// META BOXES - Open/close
	$('.refyn-metabox-wrapper').on('click', '.refyn-metabox-item h3', function(event){
		// If the user clicks on some form input inside the h3, like a select list (for variations), the box should not be toggled
		if ($(event.target).filter(':input, option').length) return;
		$( this ).parent( '.refyn-metabox-item' ).toggleClass( 'closed' ).toggleClass( 'open' );
		$(this).next('.refyn-metabox-item-content').toggle();
	})
	.on('click', '.expand_all', function(event){
		$(this).closest('.refyn-metabox-wrapper').find('.refyn-metabox-item').removeClass( 'closed' ).addClass( 'open' );
		$(this).closest('.refyn-metabox-wrapper').find('.refyn-metabox-item > table').show();
		return false;
	})
	.on('click', '.close_all', function(event){
		$(this).closest('.refyn-metabox-wrapper').find('.refyn-metabox-item').removeClass( 'open' ).addClass( 'closed' );
		$(this).closest('.refyn-metabox-wrapper').find('.refyn-metabox-item > table').hide();
		return false;
	});
	$('.refyn-metabox-item.closed').each(function(){
		$(this).find('.refyn-metabox-item-content').hide();
	});

});
