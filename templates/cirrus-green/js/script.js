jQuery(document).ready(function ($) {

    var isMobile = false; //initiate as false
    // device detection
    if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|ipad|iris|kindle|Android|Silk|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(navigator.userAgent)
        || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(navigator.userAgent.substr(0,4))) {
        isMobile = true;
    }

    if(!isMobile) {
        $('#services .service').css('opacity', 0);
        $('.blog-home .items-row').css('opacity', 0);
        $(this).on('scroll', function () {
            var topo = $(this).scrollTop();

            //Redimensionando a Logo
            if (topo > 100) {
                $('#logo a img').width(220);
            } else {
                $('#logo a img').width(260);
            }

            //Adicionando efeito serviços
            if($(window).height() < ($('#breadcrumbs').height() * 1.7)) {
                if ((topo + ($(window).height() * 0.7)) > jQuery('#services').offset().top) {
                    $('#services .service').css('opacity', 1);
                    $('#services .service:nth-child(1)').addClass('animated fadeInLeft');
                    $('#services .service:nth-child(2)').addClass('animated fadeInDown');
                    $('#services .service:nth-child(3)').addClass('animated fadeInRight');
                    $('#services .service:nth-child(4)').addClass('animated fadeInUp');
                }
            }

            var bloghome = $('.blog-home');
            if (bloghome.length) {
                if ((topo + ($(window).height() * 0.7)) > jQuery('.blog-home').offset().top) {
                    $('.blog-home .items-row').css('opacity', 1);
                    $('.blog-home .row-0').addClass('animated fadeInLeft');
                    $('.blog-home .row-1').addClass('animated fadeInRight');
                    $('.blog-home .row-2').addClass('animated fadeInRight');
                }
            }
        });
    }

    var nextDiv = $('#header_wrap').next();

    $(window).on('resize', function () {

        if($(window).height() > ($('#breadcrumbs').height() * 1.7)) {
            $('#services .service').css('opacity', 1);
            $('#services .service:nth-child(1)').addClass('animated fadeInLeft');
            $('#services .service:nth-child(2)').addClass('animated fadeInDown');
            $('#services .service:nth-child(3)').addClass('animated fadeInRight');
            $('#services .service:nth-child(4)').addClass('animated fadeInUp');
        }

        if($('#header').height() < 74){
            nextDiv.css('padding-top', 73);
        }else{
            nextDiv.css('padding-top', $('#header').height() - 1);
        }

        $('.camera_wrap').height(($(window).height() * 0.6) - $('#header').height());
    }).trigger('resize');

    /*MENU RESPONSIVO*/

    $('.menuresp li.parent > a, .menuresp li.parent > span').append(' <button type="button"><i class="fas fa-chevron-down"></i></button>');

    $('.menuresp li.parent > span').click(function () {
        $(this).siblings('ul').slideToggle();
        $(this).find('i').toggleClass('fa-chevron-up', 'fa-chevron-down');
        // $('#nav-icon').removeClass('open');
        // $('.menuresp').slideUp();
    });

    $(window).on('resize', function () {
        var menuresp = $(".gotomenu");

        $('.menuresp').css('top', $('#topmenu_wrap').outerHeight());

        menuresp.each(function () {
            if (!$(this).is(':visible')) {
                $('.menuresp').hide();
                $('#nav-icon').removeClass('open');
            }
        });

        $('.blog-home .img-intro').height($('.blog-home .img-intro').width() * 0.6);

        //Serviços artigo
        $('.servico-img').height($('.servico-img').width() * 0.75);

    }).trigger('resize');

    $('.menuresp').hide();

    $("#gotomenu").click(function () {
        $('.menuresp').slideToggle();
    });

    textClass($('.blog-home h2 a'));

    //ADD CLASS
    function textClass(classe) {
        classe.each(function (index, element) {
            var heading = $(element);
            var word_array, last_word, first_part;

            word_array = heading.html().split(/\s+/); // split on spaces
            last_word = word_array.pop();             // pop the last word
            first_part = word_array.join(' ');        // rejoin the first words together

            heading.html([first_part, ' <span class="last-word">', last_word, '</span>'].join(''));
        });
    }

    // $('#topmenu li').mouseover(function () {
    //     $(this).children('ul').width($(this).width());
    // }).mouseout(function () {
    //     $(this).children('ul').width('auto');
    // });

    $('.moduletable-endereco iframe').height(parseInt($('.moduletable-endereco iframe').width() * 0.6));

    $('#leftmenu .moduletable-menuint h3').html('Menu <i class="fas fa-bars"></i>');

    $('#leftmenu .moduletable-menuint h3').click(function () {
        $(this).siblings('ul').slideToggle();
        $(this).find('i').toggleClass('fa-times', 'fa-bars');
    });

    $(window).on('resize', function () {
        var menuserv = $("#leftmenu .moduletable-menuint h3");

        menuserv.each(function () {
            if (!$(this).is(':visible')) {
                $("#leftmenu .moduletable-menuint ul").show();
            }else{
                $('#leftmenu .moduletable-menuint h3').html('Menu <i class="fas fa-bars"></i>');
                $("#leftmenu .moduletable-menuint ul").hide();
            }
        });

    }).trigger('resize');

    $('#btn-login').click(function () {
        $('#login-form').slideToggle();
    });

    $('#btn-logout').click(function () {
        $('#logout-form').slideToggle();
    });

});

jQuery(window).load(function () {

    jQuery(window).on('resize', function () {
        var outerHeight = 0;
        jQuery('#content-menu_wrap_bg').siblings('div').each(function () {
            outerHeight += parseInt(jQuery(this).height());
        });
        jQuery('#content-menu_wrap_bg').css('min-height', parseInt(jQuery(window).height() - outerHeight + 1));
    }).trigger('resize');

});