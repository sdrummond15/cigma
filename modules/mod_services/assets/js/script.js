jQuery(document).ready(function ($) {

    $(window).on('resize', function () {

        $('#services .service:nth-child(1)').height($('#services .service:nth-child(1)').width() * 1.2);
        $('#services .service:nth-child(2)').height($('#services .service:nth-child(2)').width() * 1.15);
        $('#services .service:nth-child(3)').height($('#services .service:nth-child(3)').width() * 0.75);
        $('#services .service:nth-child(4)').height($('#services .service:nth-child(4)').width() * 1.15);

    }).trigger('resize');

});