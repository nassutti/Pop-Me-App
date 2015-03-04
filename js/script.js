$(document).ready(function(){
	       
    $('#menu-expand').on( "click", function(){
       	$('#nav').toggle(100);
     
    });

    $('#genres-expand').on( "click", function(){
        $('#nav-genres').toggle(100);
     
    });

    $('.friend-birthday').mouseover( function(){
        $(this).children('.friend-birthday-icon').show();
        $(this).children('.friend-name').hide();

     
    });

     $('.friend-birthday').mouseout( function(){
        $(this).children('.friend-birthday-icon').hide();
         $(this).children('.friend-name').show();
     
    });

  

    var textarea_max = 200;
    $('#compose-form-characters').html(textarea_max + '/200');

    $('#textarea').keyup(function() {
        var text_length = $('#textarea').val().length;
        var text_remaining = textarea_max - text_length;

        $('#compose-form-characters').html(text_remaining + '/200');
    });



    $('#slider1').tinycarousel();

     $('.overview > li').on( "click", function(){
        $('.overview > li').css('opacity', '0.5');
        $(this).css('opacity', '1');
        var image = $(this).attr("data-image");
        var id = $(this).attr("data-id");
        var html = "<img src='images/background/"+image+"' alt='Background Image'>";
        $('#background').html(html);
         $(document).load('friends/request/'+image+'/'+id);

    });



});

