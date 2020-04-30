jQuery(document).ready(function ($) {

    $('#clients').select2();
    $('#consultants').select2();
    $(".cash").maskMoney({ symbol: 'R$ ', showSymbol: true, thousands: '.', decimal: ',', symbolStay: true });
    $('#date_in').mask('99/99/9999');
    $('#date_out').mask('99/99/9999');
    $('.date-expenses').mask('99/99/9999');
    $('#date_in').focusout(function () {
        if ($(this).val()) {
            if (validarData($(this).val()) == false) {
                alert('Data de ida inválida!');
                $(this).val('');
                $(this).focus();
                return false;
            }
        }
    });

    $('#date_out').focusout(function () {
        if ($(this).val()) {
            if (!$('#date_in').val()) {
                alert('Informe a data de ida!');
                $(this).val('');
                $('#date_in').focus();
                return false;
            }
            if (!validarDatas($('#date_in').val(), $('#date_out').val())) {
                alert('A data de volta deve ser maior ou igual a data de ida!');
                $(this).val('');
                $(this).focus();
                return false;
            }
            if (validarData($(this).val()) == false) {
                alert('Data de ida inválida!');
                $(this).val('');
                $(this).focus();
                return false;
            }
        }
    });

    $('.date-expenses').focusout(function () {
        if ($(this).val()) {
            if (validarData($(this).val()) == false) {
                alert('Data inválida!');
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
        if ($(".accounts").last().find('.remove').length == 0) {
            $(".accounts").last().append('<button type="button" class="btn btn-warning right remove"><i class="fas fa-trash"></i></button>');
        }

        //Limpando Campos
        $(".accounts:last :input").each(function () {
            $(this).val('');
        });

        $(".cash").maskMoney({ symbol: 'R$ ', showSymbol: true, thousands: '.', decimal: ',', symbolStay: true });

        //Removendo box
        $('.remove').click(function (event) {
            $(this).parents('.accounts').remove();
        });

        $('.date-expenses').mask('99/99/9999');
        $('.date-expenses').focusout(function () {
            if ($(this).val()) {
                if (validarData($(this).val()) == false) {
                    alert('Data inválida!');
                    $(this).val('');
                    $(this).focus();
                    return false;
                }
            }
        });
    });

    var value = window.location.href.substring(window.location.href.lastIndexOf('/') + 1);

    $("#submit").click(function (event) {

        var msg = '';
        var focus = '';
        
        if ($('#clients').length > 0) {
            if (!$('#clients').val()) {
                msg += '<p>Selecione pelo menos um cliente!</p>';
                if (focus == '') {
                    focus = $('#clients');
                }
            }
        }

        if ($('#date_in').length > 0) {
            if ($('#date_in')) {
                if (!$('#date_in').val()) {
                    msg += '<p>Informe a data e ida!</p>';
                    if (focus == '') {
                        focus = $('#date_in');
                    }
                }
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

    $('button.delete-account').click(function () {
        var thisValues = $(this).val().split('_');
        var id = thisValues[0];
        var id_expense = thisValues[1];

        var line = $(this).parents('.line');
        noty({
            text: 'Tem certeza que deseja remover a despesa?',
            layout: 'center',
            modal: true,
            buttons: [
                {
                    addClass: 'btn btn-primary',
                    text: 'Ok',
                    onClick: function ($noty) {
                        $noty.close();
                        $.ajax({
                            url: getBaseURL() + 'components/com_managements/controllers/delete_account.php',
                            data: {
                                'id': id,
                                'expense': id_expense
                            },
                            type: 'POST'
                        }).done(function (msg) {
                            if (msg) {
                                line.hide('slow', function () {
                                    line.remove();
                                    $('.sum-cash > b').html(msg);
                                    if ($('.line').length == 0) {
                                        $("#list-accounts").slideUp('slow');
                                    }
                                    noty({
                                        text: 'Despesa removida com sucesso.',
                                        layout: 'center',
                                        type: 'success',
                                        timeout: 1000,
                                        modal: true,
                                        onClick: function ($noty) {
                                            $noty.close();
                                        }
                                    });
                                });
                            } else {
                                noty({
                                    text: 'Erro ao excluir despesa.',
                                    layout: 'center',
                                    type: 'error',
                                    timeout: 2000,
                                    modal: true,
                                    onClick: function ($noty) {
                                        $noty.close();
                                    }
                                });
                            }
                        });
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

function validarDatas(datein, dateout) {
    var indate = datein.split("/");
    var dayIn = parseInt(parts[0], 10);
    var monthIn = parseInt(parts[1], 10);
    var yearIn = parseInt(parts[2], 10);
    var outdate = datein.split("/");
    var dayOut = parseInt(parts[0], 10);
    var monthOut = parseInt(parts[1], 10);
    var yearOut = parseInt(parts[2], 10);
    if (yearOut + monthOut + dayOut < yearIn + monthIn + dayIn) {
        return false;
    }
    return true;
}


