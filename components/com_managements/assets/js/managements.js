jQuery(document).ready(function ($) {

    $(window).on('resize', function () {
        if ($('#managements .pesquisar').css('display') != 'none'){
            $("#formulario").addClass('show_form');
        }else{
            $("#formulario").removeClass('show_form');
        }
    }).trigger('resize');

    $("#managements .pesquisar").click(function () {
        $("#formulario").slideToggle("slow");
        $('.fa-angle-down, .fa-angle-up').toggleClass("fa-angle-up fa-angle-down");
    });

    $('.select_type_input, ' +
        '.select_business_input, ' +
        '.select_city_input,' +
        '.select_district_input,' +
        '.select_created_input').select2();

    $('.delManagement').click(function () {

        var deleteManagement = $(this).val();
        noty({
            text: 'Tem certeza que deseja remover o imóvel?',
            layout: 'center',
            modal: true,
            buttons: [
                {
                    addClass: 'btn btn-primary',
                    text: 'Ok',
                    onClick: function ($noty) {
                        $noty.close();
                        window.location.href = deleteManagement;
                    }
                },
                {
                    addClass: 'btn btn-danger',
                    text: 'Cancel',
                    onClick: function ($noty) {
                        $noty.close();
                    }
                }
            ]
        });
    });

});

function getUrlVars() {
    var vars = [], hash;
    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
    for (var i = 0; i < hashes.length; i++) {
        hash = hashes[i].split('=');
        vars.push(hash[0]);
        vars[hash[0]] = hash[1];
    }
    return vars;
}