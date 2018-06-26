jQuery(document).ready(function($){
	// Fixed menu co dinh tren top
    $(window).scroll(function(){
        if($(this).scrollTop()>80){
            $('.header').addClass("fixed");
        }else{
            $('.header').removeClass("fixed");
        }

        var mainvisual = $('.body').offset().top - $(window).scrollTop();
        if(mainvisual <= -100) {
            $('.btn-top').fadeIn();
        }
        else {
            $('.btn-top').fadeOut();
        }
    });

	$('.btn-top').click(function(){
        $('html, body').animate({scrollTop : 0},800);
        return false;
    });

    $('.btn-top').hide();

    $('.header-menu > i').click(function () {
        $('.main-menu').slideToggle();
    });

    // $('.main-menu > li > a').click(function(){
    // 	var current = $(this).parent().find('.sub-menu');
    // 	if (current.length &&!current.hasClass('open')) {
    // 		$('ul.sub-menu').removeClass('open');
		// 	current.addClass('open');
    // 	}
    // 	else {
    // 		$('ul.sub-menu').removeClass('open');
    // 	}
    // 	return false;
    // });

    jQuery(window).load(function() {
        if($(this).width() >= 768){
            $('.main-menu > li').hover(function(){
                var current = $(this).find('.sub-menu');
                if (current.length) {
                    $('ul.sub-menu').removeClass('open');
                    current.addClass('open');
                }
            }, function () {
                $('ul.sub-menu').removeClass('open');
            });
        }else{
            $('.main-menu > li > a').click(function(event) {
                var current = $(this).parent().find('.sub-menu');
                if(current.length > 0){
                    event.preventDefault();
                }
                if (current.length) {
                    $('ul.sub-menu').removeClass('open');
                    current.addClass('open');
                }else {
                    $('ul.sub-menu').removeClass('open');
                }
            });
        }
        var homeSlider = $(".home-slider");
        if(homeSlider.length !== 0){
            $('.home-slider > div').nivoSlider({
                directionNav: true,
                pauseOnHover: false,
                pauseTime: 5000,
                randomStart: false,
                effect: 'fade',
                prevText: '<i class="fa fa-angle-left" aria-hidden="true"></i>',
                nextText: '<i class="fa fa-angle-right" aria-hidden="true"></i>',
                afterLoad: function(){
                    $('.nivo-directionNav, .nivo-controlNav').hide();
                    $(".home-slider").removeClass('loading');
                }
            });
            $('.nivo-controlNav a').text(' ');
            homeSlider.hover(function () {
                $('.nivo-directionNav, .nivo-controlNav').stop(true).fadeIn();
            }, function () {
                $('.nivo-directionNav, .nivo-controlNav').stop(true).fadeOut();
            });
        }
    });

	// Feature project tabs switch
	$('.tabble span').click(function() {
		if ($(this).hasClass('active'))
			return ;
		$('.tabble span').removeClass('active');
		$(this).addClass('active');
		var tab = $(this).attr('id');
		$('.projects > div').hide();
		$('.projects div.' + tab).show();
	});

	var demo = $(".demo");
	if(demo.length !== 0){
        $(".demo").bootstrapNews({
            newsPerPage: 4,
            navigation: true,
            autoplay: true,
            direction:'up', // up or down
            animationSpeed: 'normal',
            newsTickerInterval: 4000, //4 secs
            pauseOnHover: true,
        });
	}

	$(".services-list").owlCarousel({
		navigation : false,
		slideSpeed : 300,
		paginationSpeed : 400,
		items : 3,
		itemsDesktop : [1170,3],
		itemsDesktopSmall : [768,2],
		itemsTablet: [480,1],
		itemsMobile : [320,1],
		autoPlay: true,
		marginRight: 20,
        pauseOnHover: false,
	});

	var typical = $(".typical-projects");
	typical.owlCarousel({
		navigation : false,
		slideSpeed : 300,
		paginationSpeed : 400,
		items : 5,
		itemsDesktop : [1170,5],
		itemsDesktopSmall : [768,2],
		itemsTablet: [480,1],
		itemsMobile : [320,1],
		autoPlay: false,
		marginRight: 15,
        pauseOnHover: false
    });

	$(".bottom > div > div").owlCarousel({
		navigation : false,
		slideSpeed : 300,
		paginationSpeed : 400,
		items : 3,
		itemsDesktop : [1170,3],
		itemsDesktopSmall : [768,3],
		itemsTablet: [480,2],
		itemsMobile : [320,2],
		autoPlay: true
	});

	$('.tabable .tabhead li').click(function(){
		if ($(this).hasClass('active'))
			return ;
		$(this).parent().find('li').removeClass('active');
		$(this).parent().parent().parent().find('.tabcontent > div').removeClass('active');
		$(this).addClass('active');
		var tab = $(this).attr('id');
		$(this).parent().parent().parent().find('.tabcontent > div.' + tab).addClass('active');
	});

	// Hide items sub menu
	var items = $(".sub-menu.group > li ul.sub-menu li:first-child, .projects_menu > ul > li > ul");
	items.each(function (index) {
		var countArrows = $(this).parent().find("li");
		if(countArrows.length > 4){
			$(this).parent().find("li:gt(3)").hide();
			var itemArrow = "<a href='' class='view-more-menu'>Xem thêm</a>";
			$(this).parent().append(itemArrow);
		}
    });
    $(".view-more-menu").click(function (e) {
    	$(this).parent().find("li:gt(3)").toggle();
        e.preventDefault();
        return false;
    });

 	// Resize image with scroll
	$(window).on("load resize click", function () {
		// Phần sidebar thông tin dự án
		ImagesRatio(".projects-info span.left", 78/129, 0);

		// Phần sidebar tin tức
		ImagesRatio(".news-info span.left", 450/753, 0);

		// Định dạng ảnh trang giới thiệu cán bộ chủ chốt
        ImagesRatio(".director-1", 265/349, 0);

		// Định dạng ảnh phần thư đánh giá
		ImagesRatio(".letter .msn-item", 1200/845, 0);

		// Định dạng ảnh phần giới thiệu khách hàng và đối tác
		ImagesRatio(".customer-item", 105/154, 0);

		// Định dạng ảnh phần Dự án
		ImagesRatio(".projects-item", 500/754, 0);

		// Định dạng ảnh phần Dự án
		ImagesRatio(".magazine-item", 728/1366, 0);

		// Định dạng ảnh cho phần tin tức
		ImagesRatio(".new-item:not(:first)", 450/753, 0);
		ImagesRatio(".new-item:first", 450/753, 0);

		// Phần tin tức nổi bật trang chủ
        ImagesRatio(".feature-news .left a span", 80/128, 0);

		// Định dạng map cho phần liên hệ
        $(".contact iframe").each(function () {
            var iWidth = $(this).width();
            var iHeight = iWidth * 403/682;
            $(this).height(iHeight);
        });

        // Anh Phan Our Office
		ImagesRatio(".our-office-item", 240/358, 0);

        // Anh Phan Our people
		ImagesRatio(".our-people-item", 314/614, 0);

		// Anh phan projects
		ImagesRatio(".ratio-project", 140/220, 0);
    });

    // di chuyển mượt chuột
    // var $window = $(window);
    // var scrollTime = 0.5;
    // var scrollDistance = 200;
    //
    // $window.on("mousewheel DOMMouseScroll", function(event){
    //
    //     event.preventDefault();
    //
    //     var delta = event.originalEvent.wheelDelta/120 || -event.originalEvent.detail/3;
    //     var scrollTop = $window.scrollTop();
    //     var finalScroll = scrollTop - parseInt(delta*scrollDistance);
    //
    //     TweenMax.to($window, scrollTime, {
    //         scrollTo : { y: finalScroll, autoKill:true },
    //         ease: Power1.easeOut,
    //         overwrite: 5
    //     });
    //
    // });
    // di chuyển mượt chuột

    // Tắt notification
    $('.alert-close').click(function(event) {
        $(this).parent().parent().fadeOut('slow', function() {
            $(this).remove();
        });;
    });
});

function ImagesRatio(el, ratio, bonus) {
	var items = $(el);
	if(items.length > 1){
        items.each(function (index) {
            var imageWidth = $(this).width();
            var imageHeight = imageWidth * ratio + bonus;
            $(this).height(imageHeight);
            $(this).css({
                "overflow": "hidden",
                "display": "block",
            });
        });
	}else{
        var imageWidth = $(el).width();
        var imageHeight = imageWidth * ratio + bonus;
        $(el).height(imageHeight);
        $(el).css({
            "overflow": "hidden",
            "display": "block",
        });
	}
}