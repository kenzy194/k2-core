$(document).ready(function(){
    /* =================
     Carousel
     =================== */
    $(".cms-carousel").each(function() {

        // VC 4.4 adds an empty div .vc_row-full-width somehow, get rid of them
        $(this).find('> .vc_row-full-width').remove();

        $(this).owlCarousel({
            margin: parseInt($(this).attr('data-margin')),
            loop: $(this).attr('data-loop') === 'true' ? true : false,
            nav: $(this).attr('data-nav') === 'true' ? true : false,
            mouseDrag: $(this).attr('data-mousedrag') === 'true' ? true : false,
            navText:['<i class="fa fa-arrow-left"></i>','<i class="fa fa-arrow-right"></i>'],
            dots: $(this).attr('data-dots') === 'true' ? true : false,
            autoplay : $(this).attr('data-autoplay') === 'false' ? false : $(this).attr('data-autoplay'),
            responsive:{
                0:{
                    items:parseInt($(this).attr('data-xsmall-items'))
                },
                768:{
                    items:parseInt($(this).attr('data-small-items'))
                },
                992:{
                    items:parseInt($(this).attr('data-medium-items'))
                },
                1200:{
                    items:parseInt($(this).attr('data-large-items'))
                }
            }
        });
    });
});