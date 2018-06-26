jQuery(document).ready(function($) {
	$('#menu_hamburger').click(function(event) {
		event.preventDefault();

		$('#menu_container').slideToggle();
	});

	$(window).resize(function(event) {
		if( $(this).width() > 992 ) {
			$('#menu_container').removeAttr('style');
		}
	});


	$(".fancybox").fancybox();
	

	if( $('body').hasClass('home') ) {
		var project_list = $('#project_list');

		$(project_list).imagesLoaded( function() {
			$(project_list).isotope({
				itemSelector: '.item',
				layoutMode: 'masonry',
			});
		});

		$('#project_filter').find('a').click(function(event) {
			event.preventDefault();

			var thisClass = $(this).attr('for');

			$(project_list).isotope({
				filter: thisClass,
			});

			var thisParent = $(this).parent('li');
			$(thisParent).addClass('active');
			$(thisParent).siblings('li').removeClass('active');
		});
	}

	if( $('body').hasClass('blog') || $('body').hasClass('category') ) {
		var news_list = $('#news_list');

		$(news_list).imagesLoaded( function() {
			$(news_list).isotope({
				itemSelector: '.item',
				layoutMode: 'masonry',
			});
		});
	}


	if( $('body').hasClass('page-template-supplier-registration') ) {
		$('.supplier-project-add').click(function(event) {
			event.preventDefault();

			var newProject = $(this).prev('.supplier-project').clone(true);
			$(newProject).insertBefore( this );
			$(newProject).find('input').val('');
			$(newProject).find('input').first().focus();

			updateSupplierProjectName();
		});


		$('#supplier_form').delegate('.supplier-project-remove', 'click', function(event) {
			event.preventDefault();
			var thisProject = $(this).parent('.supplier-project');

			if( $(thisProject).siblings('.supplier-project').length > 0  ) {
				$(thisProject).remove();
			}

			updateSupplierProjectName();
		});

		updateSupplierProjectName();

		function updateSupplierProjectName() {
			$('.supplier-project-group').each(function(index, el) {
				var this_group = $(this).attr('for');
				$(this).find('.supplier-project').each(function(this_index, el) {
					$(this).find('input, textarea').each(function() {
						var this_placeholer = $(this).attr('placeholder');
						$(this).attr('name', 'THÔNG TIN VỀ KINH NGHIỆM['+ this_group +']['+ this_index +']['+ this_placeholer +']');
					});
				});
			});
		}

		$( "#supplier_form" ).validate({
			ignore: []
		});
	}


	if( $('body').hasClass('page-template-recruitment-apply') ) {
		$( "#recruitment_form" ).validate({
			ignore: []
		});
	}
});