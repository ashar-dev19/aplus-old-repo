
          $(document).ready(function(){
           
             $('.courses_slider').slick({
            infinite: true,
            slidesToShow: 3,
            slidesToScroll: 3,
            nav:true,
            dots:true,
            // autoplay: true,
            });

            $('.testimonial_slider').slick({
            infinite: true,
            slidesToShow: 3,
            slidesToScroll: 3,
            nav:true,
            dots:true,
            // autoplay: true,
            }); 

             

          });
   

      
     $('.count').each(function () {
     $(this).prop('Counter',0).animate({
     Counter: $(this).text()
     }, {
     duration: 4000,
     easing: 'swing',
     step: function (now) {
         $(this).text(Math.ceil(now));
     }
     });
     });
              
      