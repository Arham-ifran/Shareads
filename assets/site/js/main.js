jQuery(function($) {'use strict',

	/*#main-slider*/
	$(function(){
		$('#main-slider.carousel').carousel({
			interval: 8000
		});
	});


	/*accordian*/
	$('.accordion-toggle').on('click', function(){
		$(this).closest('.panel-group').children().each(function(){
		$(this).find('>.panel-heading').removeClass('active');
		 });

	 	$(this).closest('.panel-heading').toggleClass('active');
	});

	/*Initiat WOW JS*/
	new WOW().init();


});

    $(function(){


      /*Keep track of last scroll*/
      var lastScroll = 0;
	  var lastScroll1 = 0;
      $(window).scroll(function(event){
          /*Sets the current scroll position*/
          var st = $(this).scrollTop();

		  /*Determines up-or-down scrolling*/
          if (st > lastScroll){
             /*Replace this with your function call for downward-scrolling*/
              $('.menu123').addClass('sticky');
			  $('.sticky').removeClass('menu123');
          }
          else {
             /*Replace this with your function call for upward-scrolling*/

				if(st==0){
			 $('.sticky').addClass('menu123');
			  $('.menu123').removeClass('sticky');
				}
          }

          /*Updates scroll position*/
          lastScroll = st;
      });
    });




