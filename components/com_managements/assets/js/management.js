jQuery(document).ready(function ($) {

    $('#clients').select2();
    $(".cash").maskMoney({ symbol: 'R$ ', showSymbol: true, thousands: '.', decimal: ',', symbolStay: true });
    $('#date_in').mask('99/99/9999');
    $('#date_out').mask('99/99/9999');
    $('#date_in').focusout(function() {
        if($(this).val()){
            if(validarData($(this).val()) == false){
                alert('Data de ida inválida!');
                $(this).val('');
                $(this).focus();
                return false;
            }
        }
    });

    $('#date_out').focusout(function() {
        if($(this).val()){
            if(!$('#date_in').val()){
                alert('Informe a data de ida!');
                $(this).val('');
                $('#date_in').focus();
                return false;
            }
            if($('#date_out').val() < $('#date_in').val()){
                alert('A data de volta deve ser maior ou igual a data de ida!');
                $(this).val('');
                $(this).focus();
                return false;
            }
            if(validarData($(this).val()) == false){
                alert('Data de ida inválida!');
                $(this).val('');
                $(this).focus();
                return false;
            }
        }
    });


    $("#add").click(function (event) {

        //Adicionando novo box
        $(".accounts").last().clone().insertAfter(".accounts:last");

        //Adicionando Remove 
        if($(".accounts").last().find('.remove').length == 0){
            $(".accounts").last().append('<button type="button" class="btn btn-warning right remove"><i class="fas fa-trash"></i></button>');
        }

        //Limpando Campos
        $(".accounts:last :input").each(function(){
            $(this).val('');
        });

        //Removendo box
        $('.remove').click(function (event) {
            $(this).parents('.accounts').remove();
        });
    });
    

    var value = window.location.href.substring(window.location.href.lastIndexOf('/') + 1);

    

    $("#submit").click(function (event) {

        var msg = '';
        var focus = '';

        //Dados Adicionais
        if (($('input[name="energy"]:checked').val() == 2 && $.trim($('#numero-energia').val()) == '') ||
            ($('input[name="energy"]:checked').val() == 3 && $.trim($('#numero-energia').val()) == '')) {
            msg += '<p>Informe o número da conta de energia</p>';
            if (focus == '') {
                focus = $('#numero-energia');
            }
        }

        if (($('input[name="energy"]:checked').val() == 2 && $.trim($('#relogio-energia').val()) == '') ||
            ($('input[name="energy"]:checked').val() == 3 && $.trim($('#relogio-energia').val()) == '')) {
            msg += '<p>Informe o número do relógio</p>';
            if (focus == '') {
                focus = $('#relogio-energia');
            }
        }

        if (($('input[name="water"]:checked').val() == 2 && $.trim($('#numero-agua').val()) == '') ||
            ($('input[name="water"]:checked').val() == 3 && $.trim($('#numero-agua').val()) == '')) {
            msg += '<p>Informe o número da conta de água</p>';
            if (focus == '') {
                focus = $('#numero-agua');
            }
        }

        if (($('input[name="water"]:checked').val() == 2 && $.trim($('#hidrometro-agua').val()) == '') ||
            ($('input[name="water"]:checked').val() == 3 && $.trim($('#hidrometro-agua').val()) == '')) {
            msg += '<p>Informe o número do hidrômetro de água</p>';
            if (focus == '') {
                focus = $('#hidrometro-agua');
            }
        }

        //Exibir mensagem de Erro
        if (msg != '') {
            $(".msgs").append(msg);
            $(".msgs").css('border-color', '#b52200');
            $(".msgs").css('color', '#b52200');
            $(".msgs").css('background-color', '#FFD5D5');
            $(".msgs").slideDown();

            focus.focus();

            event.preventDefault();
        }

        //Limpar campos de acordo com seleção antes de Salvar
        if ($('#vender').attr("checked") === undefined) {
            $('#valor-venda').val('');
        }

        if ($('#alugar').attr("checked") === undefined) {
            $('#valor-aluguel').val('');
        }

        if ($('#partilhar').attr("checked") === undefined) {
            $('#valor-partilha').val('');
        }

        if ($('input[name="rented"]:checked').val() == 0) {
            $('#data-locacao').val('');
        }

        if ($('input[name="energy"]:checked').val() != 2 && $('input[name="energy"]:checked').val() != 3) {
            $('#numero-energia').val('');
        }

        if ($('input[name="water"]:checked').val() != 2 && $('input[name="water"]:checked').val() != 3) {
            $('#numero-agua').val('');
        }

        if (msg != '') {
            var timeClose = setTimeout(function () {
                $(".msgs").slideUp();
                $(".msgs p").remove();
            }, 5000);

            $(".msgs").click(function () {
                clearTimeout(timeClose);
                $(".msgs").slideUp();
                $(".msgs p").remove();
            });
        }

        var idUrl = value.split('_');
        $('form').prepend('<input type="hidden" id="id" name="id" value="' + idUrl[0] + '" />');
        $('.select_state, .select_city').select2({
            disabled: false
        });

    });


    function msgClose(msg, focus) {

        if (msg != '') {

            $(".msgs").append(msg);
            $(".msgs").css('border-color', '#b52200');
            $(".msgs").css('color', '#b52200');
            $(".msgs").css('background-color', '#FFD5D5');
            $(".msgs span").show();
            $(".msgs").slideDown();

            focus.focus();

            var timeClose = setTimeout(function () {
                $(".msgs").slideUp();
                $(".msgs span").hide();
                $(".msgs p").remove();
            }, 5000);

            $(".msgs").click(function () {
                clearTimeout(timeClose);
                $(".msgs").slideUp();
                $(".msgs span").hide();
                $(".msgs p").remove();
            });

            return false;

        }

        return true;
    }

});

function getBaseURL() {
    var url = location.href;  // entire url including querystring - also: window.location.href;
    var baseURL = url.substring(0, url.indexOf('/', 14));


    if (baseURL.indexOf('http://localhost') != -1) {
        // Base Url for localhost
        var url = location.href;  // window.location.href;
        var pathname = location.pathname;  // window.location.pathname;
        var index1 = url.indexOf(pathname);
        var index2 = url.indexOf("/", index1 + 1);
        var baseLocalUrl = url.substr(0, index2);

        return baseLocalUrl + "/";
    }
    else {
        // Root Url for domain name
        return baseURL + "/";
    }

}

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

function validarData(dateString) {

    // First check for the pattern
    if (!/^\d{1,2}\/\d{1,2}\/\d{4}$/.test(dateString))
        return false;

    // Parse the date parts to integers
    var parts = dateString.split("/");
    var day = parseInt(parts[0], 10);
    var month = parseInt(parts[1], 10);
    var year = parseInt(parts[2], 10);

    // Check the ranges of month and year
    if (year < 1000 || year > 3000 || month == 0 || month > 12) {
        return false;
    }

    var monthLength = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];

    // Adjust for leap years
    if (year % 400 == 0 || (year % 100 != 0 && year % 4 == 0))
        monthLength[1] = 29;

    // Check the range of the day
    return day > 0 && day <= monthLength[month - 1];
};