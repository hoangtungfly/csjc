
$(document).on('ready', function(){
    new WOW().init();
    
    $('.owl-carousel').owlCarousel({
        loop: true,
        margin: 10,
        nav: false,
        autoplay: true,
        responsive: {
            0: {
                items: 1
            }
        }
    });
});

$(document).on("scroll", function() {
    if ($(document).scrollTop() > 400) {
        $("nav").addClass("smaller");
        $("nav.navbar .logo").addClass("smaller");
        $("#navbar ul").addClass("smaller");
    } else {
        $("nav").removeClass("smaller");
        $("nav.navbar .logo").removeClass("smaller");
        $("#navbar ul").removeClass("smaller");
    }
});
