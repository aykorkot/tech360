import './lib/slick.min';

$(document).ready(() => {

    //top slider
    const $slider = $('.slider-list');
    const slideHeight = $('.slider-list li').outerHeight();
    const slideCount = $('.slider-list li').length;
  
    // Clone de tous les éléments pour créer une boucle douce
    $('.slider-list li').each(function () {
      $slider.append($(this).clone());
    });
  
    let currentIndex = 0;
  
    setInterval(function () {
      currentIndex++;
      $slider.css('transform', 'translateY(' + (-slideHeight * currentIndex) + 'px)');
  
      if (currentIndex === slideCount) {
        setTimeout(function () {
          $slider.css('transition', 'none');
          $slider.css('transform', 'translateY(0)');
          currentIndex = 0;
  
          // Re-enable transition
          setTimeout(function () {
            $slider.css('transition', 'transform 0.5s ease-in-out');
          }, 50);
        }, 1500); // correspond à la durée de la transition
      }
    }, 2500);
    
    

    // Sliders
    $('.reviews__wrapper').not('.slick-initialized').slick({
        infinite: false,
        slidesToShow: 3,
        slidesToScroll: 1,
        speed: 500,
        dots: true,
        arrows: true,
        responsive: [
            {
                breakpoint: 992,
                settings: {
                    arrows: false,
                    slidesToShow: 2,
                }
            },
            {
                breakpoint: 640,
                settings: {
                    arrows: false,
                    slidesToShow: 1,
                }
            }
        ]
    });

    // $('.product-cover').not('.slick-initialized').slick({
    //     infinite: false,
    //     slidesToShow: 1,
    //     slidesToScroll: 1,
    //     variableWidth: true,
    //     speed: 500,
    //     dots: true,
    //     arrows: true,
    //     responsive: [
    //         {
    //             breakpoint: 640,
    //             settings: {
    //                 arrows: false,
    //             }
    //         }
    //     ]
    // });

    // $(document).ajaxSuccess(function(){
    //     $('.product-cover').not('.slick-initialized').slick({
    //         infinite: false,
    //         slidesToShow: 1,
    //         slidesToScroll: 1,
    //         variableWidth: true,
    //         speed: 500,
    //         dots: true,
    //         arrows: true,
    //         responsive: [
    //             {
    //                 breakpoint: 640,
    //                 settings: {
    //                     arrows: false,
    //                 }
    //             }
    //         ]
    //     });
    // });

    $('.page-home .featured-products .products').not('.slick-initialized').slick({
      infinite: false,
      slidesToShow: 5,
      slidesToScroll: 1,
      speed: 500,
      dots: false,
      arrows: true,
      responsive: [
        {
          breakpoint: 1200,
          settings: {
            slidesToShow: 4,
          }
        },
        {
          breakpoint: 890,
          settings: {
            slidesToShow: 3,
          }
        },
        {
          breakpoint: 691,
          settings: {
            slidesToShow: 2,
          }
        },
        {
          breakpoint: 480,
          settings: {
            slidesToShow: 2,
          }
        },
      ]
    });

    $('.popular-categories__wrapper').not('.slick-initialized').slick({
      infinite: true,
      slidesToShow: 4,
      slidesToScroll: 1,
      speed: 500,
      dots: false,
      arrows: true,
      responsive: [
        {
          breakpoint: 992,
          settings: {
            slidesToShow: 3,
          }
        },
        {
          breakpoint: 641,
          settings: {
            slidesToShow: 2,
            dots: true,
            arrows: false,
          }
        }
      ]
    });

    

    // $('.brands ul').not('.slick-initialized').slick({
    //   infinite: true,
    //   slidesToShow: 8,
    //   slidesToScroll: 1,
    //   speed: 500,
    //   dots: false,
    //   arrows: true,
    //   responsive: [
    //     {
    //       breakpoint: 1024,
    //       settings: {
    //         slidesToShow: 6,
    //       }
    //     },
    //     {
    //       breakpoint: 992,
    //       settings: {
    //         slidesToShow: 5,
    //         dots: true,
    //         arrows: false,
    //       }
    //     },
    //     {
    //       breakpoint: 640,
    //       settings: {
    //         slidesToShow: 4,
    //         dots: true,
    //         arrows: false,
    //       }
    //     },
    //     {
    //       breakpoint: 540,
    //       settings: {
    //         slidesToShow: 3,
    //         dots: true,
    //         arrows: false,
    //       }
    //     }
    //   ]
    // });
});
