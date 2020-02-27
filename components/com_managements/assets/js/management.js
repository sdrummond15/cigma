jQuery(document).ready(function ($) {

    //Adicionando FOCUS em campos tipo RADIO
    $('input[type="radio"]').focusin(function () {
        $(this).parents('ul').addClass('focus');
    });
    $('input[type="radio"]').focusout(function () {
        $(this).parents('ul').removeClass('focus');
    });

    //UPPERCASE
    $("input[type='text']").blur(function () {
        if ($(this)[0].id != 'video') {
            $(this).val($(this).val().toUpperCase());
        }
    });
    $("textarea").blur(function () {
        $(this).val($(this).val().toUpperCase());
    });

    var value = window.location.href.substring(window.location.href.lastIndexOf('/') + 1);

    var formSize = $(".page-slide").outerHeight();
    var formSizeUl = $("#progressbar").outerHeight();
    $('#insert-management').height(formSize + formSizeUl);

    //RESIZE TELA
    $(window).on('resize', function () {
        var formSizeResize = $(".page-slide").outerHeight();
        $('#insert-management').animate({height: formSizeResize + formSizeUl}, 100);
    }).trigger('resize');

    //DADOS PRINCIPAIS

    $("#valor-venda").maskMoney({symbol: 'R$ ', showSymbol: true, thousands: '.', decimal: ',', symbolStay: true});
    $("#valor-aluguel").maskMoney({symbol: 'R$ ', showSymbol: true, thousands: '.', decimal: ',', symbolStay: true});
    $("#valor-partilha").maskMoney({symbol: 'R$ ', showSymbol: true, thousands: '.', decimal: ',', symbolStay: true});
    $("#valor-iptu").maskMoney({symbol: 'R$ ', showSymbol: true, thousands: '.', decimal: ',', symbolStay: true});
    $("#condominio").maskMoney({symbol: 'R$ ', showSymbol: true, thousands: '.', decimal: ',', symbolStay: true});
    $("#seguro-fianca").maskMoney({symbol: 'R$ ', showSymbol: true, thousands: '.', decimal: ',', symbolStay: true});
    $("#seguro-incendio").maskMoney({symbol: 'R$ ', showSymbol: true, thousands: '.', decimal: ',', symbolStay: true});
    $("#valor-venal").maskMoney({symbol: 'R$ ', showSymbol: true, thousands: '.', decimal: ',', symbolStay: true});
    $("#data-locacao").mask("99/99/9999");

    //Valida vender
    if ($('#vender').is(":checked") === false) {
        $('#valor-venda').val('');
        $('#valor-venda').hide();
        $('#valor-venda').siblings('label').hide();
        $('#valor-venda').prop('required', false);
    }

    $('#vender').change(function () {
        if ($(this).is(":checked") === true) {
            $('#valor-venda').show('slow');
            $('#valor-venda').siblings('label').show('slow');
            $('#valor-venda').prop('required', true);
        } else {
            $('#valor-venda').hide('slow');
            $('#valor-venda').siblings('label').hide('slow');
            $('#valor-venda').prop('required', false);
        }
    });

    //Valida alugar
    if ($('#alugar').is(":checked") === false) {
        $('#valor-aluguel').val('');
        $('#valor-aluguel').hide();
        $('#valor-aluguel').siblings('label').hide();
        $('#valor-aluguel').prop('required', false);
    }

    $('#alugar').change(function () {
        if ($(this).is(":checked") === true) {
            $('#valor-aluguel').show('slow');
            $('#valor-aluguel').siblings('label').show('slow');
            $('#valor-aluguel').prop('required', true);
        } else {
            $('#valor-aluguel').hide('slow');
            $('#valor-aluguel').siblings('label').hide('slow');
            $('#valor-aluguel').prop('required', false);
        }
    });

    //Valida partilhar
    if ($('#partilhar').is(":checked") === false) {
        $('#valor-partilha').val('');
        $('#valor-partilha').hide();
        $('#valor-partilha').siblings('label').hide();
        $('#valor-partilha').prop('required', false);
    }

    $('#partilhar').change(function () {
        if ($(this).is(":checked") === true) {
            $('#valor-partilha').show('slow');
            $('#valor-partilha').siblings('label').show('slow');
            $('#valor-partilha').prop('required', true);
        } else {
            $('#valor-partilha').hide('slow');
            $('#valor-partilha').siblings('label').hide('slow');
            $('#valor-partilha').prop('required', false);
        }
    });

    //Mostrar campo de Data da locação se Alugado estiver SIM
    if ($('input[name="rented"]:checked').val() != 1) {
        $('#locacao label').hide();
        $('#locacao input').hide();
        $('#data-locacao').prop('required', false);
    }

    $('input[name="rented"]').change(function () {
        if ($('input[name="rented"]:checked').val() == 1) {
            $('#locacao label').slideDown("slow");
            $('#locacao input').slideDown("slow");
            $('#data-locacao').prop('required', true);
            $('#data-locacao').focus();
        } else {
            $('#locacao label').slideUp("slow");
            $('#locacao input').slideUp("slow");
            $('#data-locacao').prop('required', false);
        }
    });

    //Validar Data Locação
    $('#data-locacao').focusout(function () {
        if ($("#data-locacao").val().length > 0) {
            if (validarData($("#data-locacao").val()) === false) {
                noty({
                    text: 'Data de Locação Inválida!',
                    layout: 'top',
                    type: 'warning',
                    modal: true,
                    timeout: 2000,
                    onClick: function ($noty) {
                        $noty.close();
                    },
                    callback: {
                        onClose: function () {
                            $("#data-locacao").focus();
                            $("#data-locacao").val('');
                        },
                    }
                });
                return false;
            }
        }
    });

    //Valida Campos de Dados Principais para poder avançar para a próxima etapa
    $("#dados-principais .next").click(function () {

        var msg = '';
        var focus = '';

        //Dados Principais
        if ($('#vender').attr("checked") === undefined && $('#alugar').attr("checked") === undefined && $('#partilhar').attr("checked") === undefined) {
            msg += '<p>Selecione pelo menos um tipo de negócio para o imóvel</p>';
            if (focus == '') {
                focus = $('#vender');
            }
        }

        if ($('#vender').attr("checked") == 'checked' && $.trim($('#valor-venda').val()) == '') {
            msg += '<p>Informe o Valor de Venda</p>';
            if (focus == '') {
                focus = $('#valor-venda');
            }
        }

        if ($('#alugar').attr("checked") == 'checked' && $.trim($('#valor-aluguel').val()) == '') {
            msg += '<p>Informe o Valor de Aluguel</p>';
            if (focus == '') {
                focus = $('#valor-aluguel');
            }
        }

        if ($('#partilhar').attr("checked") == 'checked' && $.trim($('#valor-partilha').val()) == '') {
            msg += '<p>Informe o Valor de Partilhamento</p>';
            if (focus == '') {
                focus = $('#valor-partilha');
            }
        }

        if ($.trim($('#valor-venal').val()) == '') {
            msg += '<p>Informe o Valor Venal</p>';
            if (focus == '') {
                focus = $('#valor-venal');
            }
        }

        if ($('input[name="rented"]:checked').val() == 1) {
            if (validarData($("#data-locacao").val()) === false) {
                msg += '<p>Informe a data de locação</p>';
                if (focus == '') {
                    focus = $('#data-locacao');
                }
            }
        }

        var owners = $('input[name="owner_id[]"]:checked').length;
        if (!owners) {
            msg += '<p>Vincule pelo menos um cliente ao imóvel!</p>';
            if (focus == '') {
                focus = $('#proprietarios-usufrutarios .control-owner');
            }
        }

        if (msgClose(msg, focus) === false) {
            return false;
        }

        Next($(this));

    });

    //DADOS ENDEREÇO

    $('#cep').mask('99.999-999');
    $('.select_state, .select_city').select2({
        disabled: true
    });
    $("#logradouro").prop("readonly",true);
    $("#bairro").prop("readonly",true);

    //Filtro Cidades por Estado
    $("#select_state").on('select2:select', function () {
        var id = $(this).val();
        if(id){
            $.ajax({
                url: getBaseURL() + 'components/com_managements/controllers/cities.php',
                data: {'id': id},
                type: 'POST',
                beforeSend: function () {
                    $("#select_city").hide();
                    $("#select_city").next(".select2-container").hide();
                    $("#select_city").parents('.control-group').append('<div class="loading"><i class="fa fa-spinner fa-pulse"></i> Carregando...</div>');
                }

            }).done(function (msg) {
                $(".loading").remove();
                $("#select_city").html(msg);
                $("#select_city").val("your_value_from_ajax_response").trigger("liszt:updated");
                $("#select_city").show();
                $("#select_city").next(".select2-container").show();
                $('.select_city').select2({
                    disabled: false
                });
            });
        }else{
            $("#select_city").hide();
            $("#select_city").next(".select2-container").hide();
            $("#select_city").parents('.control-group').append('<div class="loading"><i class="fa fa-spinner fa-pulse"></i> Carregando...</div>');
            $(".loading").remove();
            $("#select_city").html("<option value='' >Selecione a cidade</option>");
            $("#select_city").val("").trigger("liszt:updated");
            $("#select_city").show();
            $("#select_city").next(".select2-container").show();
            $('.select_city').select2({
                disabled: true
            });
        }

    });

    //Valida Campos de Dados Endereço para poder avançar para a próxima etapa
    $("#dados-endereco .next").click(function () {

        var msg = '';
        var focus = '';

        //Dados Endereço
        if ($.trim($('#logradouro').val()) == '') {
            msg += '<p>Informe o Logradouro</p>';
            if (focus == '') {
                focus = $('#logradouro');
            }
        }
        if ($.trim($('#numero').val()) == '') {
            msg += '<p>Informe o número, caso não possua informe "S/N"</p>';
            if (focus == '') {
                focus = $('#numero');
            }
        }
        if ($.trim($('#bairro').val()) == '') {
            msg += '<p>Informe o bairro</p>';
            if (focus == '') {
                focus = $('#bairro');
            }
        }
        if ($.trim($('#select_state').val()) == '') {
            msg += '<p>Informe o estado</p>';
            if (focus == '') {
                focus = $('#select_state');
            }
        }
        if ($.trim($('#select_city').val()) == '') {
            msg += '<p>Informe a cidade</p>';
            if (focus == '') {
                focus = $('#select_city');
            }
        }
        if ($.trim($('#cep').val()) == '') {
            msg += '<p>Informe o CEP</p>';
            if (focus == '') {
                focus = $('#cep');
            }
        }

        if (msgClose(msg, focus) === false) {
            return false;
        }

        Next($(this));

    });

    //CARACTERISTICAS

    $('#suites').mask('99');
    $('#quartos').mask('99');
    $('#banheiros').mask('99');
    $('#salas').mask('99');
    $('#vagas-garagem').mask('99');
    $('#area-total').maskMoney({symbol: '', showSymbol: true, thousands: '.', decimal: ',', symbolStay: true});
    $('#area-construida').maskMoney({symbol: '', showSymbol: true, thousands: '.', decimal: ',', symbolStay: true});

    //Valida Campos de Características para poder avançar para a próxima etapa
    $("#caracteristicas .next").click(function () {

        Next($(this));

    });

    //DADOS MIDIAS

    $('#cover-image').change(function () {

        //Get reference of FileUpload.
        var fileUpload = $(this)[0];

        if (this.value) {
            //Check whether HTML5 is supported.
            if (typeof (fileUpload.files) != "undefined") {

                //Initiate the FileReader object.
                var reader = new FileReader();
                //Read the contents of Image File.
                reader.readAsDataURL(fileUpload.files[0]);
                reader.onload = function (e) {
                    //Initiate the JavaScript Image object.
                    var image = new Image();
                    //Set the Base64 string return from FileReader as source.
                    image.src = e.target.result;

                    image.onload = function () {
                        return true;
                    };

                    image.onerror = function () {
                        noty({
                            text: 'Imagem Inválida!',
                            layout: 'top',
                            type: 'warning',
                            modal: true,
                            timeout: 2000,
                            onClick: function ($noty) {
                                $noty.close();
                            },
                            callback: {
                                onClose: function () {
                                    $("#cover-image").focus();
                                    $("#cover-image").val('');
                                    $("#capa").text('');
                                },
                            }
                        });
                        return false;
                    }
                }
            }
        }

    });

    $("#add").click(function () {
        var item = $(".control-photo:last-child").clone(true);
        $(".control-photo").parents(".control-group").append(item);
        $(".control-photo:last-child .photo").val('');
        $(".control-photo:last-child .desc-photo").text('');
        $("#dados-midias").each(function () {
            $('#insert-management').animate({height: $(this).height()}, 500);
        });
    });

    $('.photo').change(function () {
        var nameImage = '';
        if ($(this)[0].files.length > 1) {
            nameImage = $(this)[0].files.length + ' Fotos Selecionadas';
        } else {
            nameImage = $(this).val().split('\\').pop();
        }
        $(this).siblings('.desc-photo').text(nameImage);

    });

    $('.btn-add').live('click', function () {
        $(this).siblings('.photo').click();
    });

    //Excluir foto
    $('input.deleteImg').click(function () {

        var image = $(this).parents('.thumbimg').children('img').attr('src');
        var divThumb = $(this).parents('.thumbimg');

        noty({
            text: 'Tem certeza que deseja remover a foto?',
            layout: 'center',
            modal: true,
            buttons: [
                {
                    addClass: 'btn btn-primary',
                    text: 'Ok',
                    onClick: function ($noty) {
                        $noty.close();

                        var id = value.split('_');

                        $.ajax({
                            url: getBaseURL() + 'components/com_managements/controllers/delete_photo.php',
                            data: {
                                'id': id[0],
                                'image': image
                            },
                            type: 'POST'
                        }).done(function (msg) {
                            if (msg == true) {
                                divThumb.hide('slow', function () {
                                    divThumb.remove();
                                    if ($('.thumbimg').length == 0) {
                                        $("#lista-fotos h3").slideUp('slow');
                                    }

                                    noty({
                                        text: 'Foto removida com sucesso.',
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
                                    text: 'Erro ao excluir foto.',
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

        var id = $(this).val();

    });


    //Valida Campos de Dados de Midias para poder avançar para a próxima etapa
    $("#dados-midias .next").click(function () {

        var msg = '';
        var focus = '';

        //Dados Midias

        if ($('#cover-image').val() == '' && $('#thumb_cover_image').length == 0) {
            msg += '<p>Insira a imagem de capa</p>';
            if (focus == '') {
                focus = $('#capa');
            }
        }

        Next($(this));

    });

    //DADOS ADICIONAIS

    //Valida energia
    if ($('input[name="energy"]:checked').val() == 1) {
        $('#number_energy').hide();
        $('#numero-energia').prop('required', false);
        $('#clock_energy').hide();
        $('#relogio-energia').prop('required', false);
    }

    $('input[name="energy"]').change(function () {
        if ($('input[name="energy"]:checked').val() == 2 || $('input[name="energy"]:checked').val() == 3) {
            $('#number_energy').slideDown("slow", function () {
                formSize = $(this).parents(".page-slide").height();
                $('#insert-management').animate({height: formSize + formSizeUl}, 500);
            });
            $('#numero-energia').prop('required', true);
            $('#clock_energy').slideDown("slow", function () {
                formSize = $(this).parents(".page-slide").height();
                $('#insert-management').animate({height: formSize + formSizeUl}, 500);
            });
            $('#relogio-energia').prop('required', true);
        } else {
            $('#number_energy').slideUp("slow", function () {
                formSize = $(this).parents(".page-slide").height();
                $('#insert-management').animate({height: formSize + formSizeUl}, 500);
            });
            $('#numero-energia').prop('required', false);
            $('#clock_energy').slideUp("slow", function () {
                formSize = $(this).parents(".page-slide").height();
                $('#insert-management').animate({height: formSize + formSizeUl}, 500);
            });
            $('#relogio-energia').prop('required', false);
        }

    });

    //Valida agua
    if ($('input[name="water"]:checked').val() == 1) {
        $('#number_water').hide();
        $('#numero-agua').prop('required', false);
        $('#clock_water').hide();
        $('#hidrometro-agua').prop('required', false);
    }

    $('input[name="water"]').change(function () {
        if ($('input[name="water"]:checked').val() == 2 || $('input[name="water"]:checked').val() == 3) {
            $('#number_water').slideDown("slow", function () {
                formSize = $(this).parents(".page-slide").height();
                $('#insert-management').animate({height: formSize + formSizeUl}, 500);
            });
            $('#numero-agua').prop('required', true);
            $('#clock_water').slideDown("slow", function () {
                formSize = $(this).parents(".page-slide").height();
                $('#insert-management').animate({height: formSize + formSizeUl}, 500);
            });
            $('#hidrometro-agua').prop('required', true);
        } else {
            $('#number_water').slideUp("slow", function () {
                formSize = $(this).parents(".page-slide").height();
                $('#insert-management').animate({height: formSize + formSizeUl}, 500);
            });
            $('#numero-agua').prop('required', false);
            $('#clock_water').slideUp("slow", function () {
                formSize = $(this).parents(".page-slide").height();
                $('#insert-management').animate({height: formSize + formSizeUl}, 500);
            });
            $('#hidrometro-agua').prop('required', false);
        }
    });

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

    //Inicializando variaveis para eventos dos botoes NEXT e PREVIOUS
    var current_fs, next_fs, previous_fs; //fieldsets
    var left, opacity, scale; //fieldset properties which we will animate
    var animating; //flag to prevent quick multi-click glitches


    function Next(divThis) {
        if (animating) return false;
        animating = true;

        current_fs = divThis.parent();
        next_fs = divThis.parent().next();

        //activate next step on progressbar using the index of next_fs
        $("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");

        //show the next fieldset
        next_fs.show();
        //hide the current fieldset with style
        current_fs.animate({opacity: 0}, {
            step: function (now, mx) {
                //as the opacity of current_fs reduces to 0 - stored in "now"
                //1. scale current_fs down to 80%
                scale = 1 - (1 - now) * 0.2;
                //2. bring next_fs from the right(50%)
                left = (now * 50) + "%";
                //3. increase opacity of next_fs to 1 as it moves in
                opacity = 1 - now;
                current_fs.css({'transform': 'scale(' + scale + ')'});
                next_fs.css({'left': left, 'opacity': opacity});
            },
            duration: 800,
            complete: function () {
                current_fs.hide();
                animating = false;
            },
            //this comes from the custom easing plugin
            easing: 'easeInOutBack'
        });

        formSize = divThis.parents(".page-slide").next(".page-slide").outerHeight();
        $('#insert-management').animate({height: formSize + formSizeUl}, 500);
    }

    $(".previous").click(function () {
        if (animating) return false;
        animating = true;

        current_fs = $(this).parent();
        previous_fs = $(this).parent().prev();

        //de-activate current step on progressbar
        $("#progressbar li").eq($("fieldset").index(current_fs)).removeClass("active");

        //show the previous fieldset
        previous_fs.show();
        //hide the current fieldset with style
        current_fs.animate({opacity: 0}, {
            step: function (now, mx) {
                //as the opacity of current_fs reduces to 0 - stored in "now"
                //1. scale previous_fs from 80% to 100%
                scale = 0.8 + (1 - now) * 0.2;
                //2. take current_fs to the right(50%) - from 0%
                left = ((1 - now) * 50) + "%";
                //3. increase opacity of previous_fs to 1 as it moves in
                opacity = 1 - now;
                current_fs.css({'left': left});
                previous_fs.css({'transform': 'scale(' + scale + ')', 'opacity': opacity});
            },
            duration: 800,
            complete: function () {
                current_fs.hide();
                animating = false;
            },
            //this comes from the custom easing plugin
            easing: 'easeInOutBack'
        });

        formSize = $(this).parents(".page-slide").prev(".page-slide").outerHeight();
        $('#insert-management').animate({height: formSize + formSizeUl}, 500);

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

    function limpa_formulario_cep() {
        // Limpa valores do formulário de cep.
        $("#logradouro").val("");
        $("#bairro").val("");
        $("#select_state").siblings(".loading").remove();
        $("#select_state").val("").trigger('change');
        $("#select_state").show();
        $("#select_state").next(".select2-container").show();

        $("#select_city").siblings(".loading").remove();
        $("#select_city").val("").trigger('change');
        $("#select_city").show();
        $("#select_city").next(".select2-container").show();
    }

    //Quando o campo cep é alterado.
    $("#cep").change(function () {

        $('.select_state, .select_city').select2({
            disabled: true
        });
        $("#logradouro").prop("readonly",true);
        $("#bairro").prop("readonly",true);

        //Nova variável "cep" somente com dígitos.
        var cep = $(this).val().replace(/\D/g, '');

        //Verifica se campo cep possui valor informado.
        if (cep != "") {

            //Expressão regular para validar o CEP.
            var validacep = /^[0-9]{8}$/;

            //Valida o formato do CEP.
            if (validacep.test(cep)) {

                //Preenche os campos com "..." enquanto consulta webservice.
                $("#logradouro").val('...');
                $("#bairro").val('...');
                //Cidade
                $("#select_city").hide();
                $("#select_city").next(".select2-container").hide();
                $("#select_city").parents('.control-group').append('<div class="loading"><i class="fa fa-spinner fa-pulse"></i> Carregando...</div>');
                $("#select_state").hide();
                $("#select_state").next(".select2-container").hide();
                $("#select_state").parents('.control-group').append('<div class="loading"><i class="fa fa-spinner fa-pulse"></i> Carregando...</div>');

                //Consulta o webservice viacep.com.br/
                $.getJSON("https://viacep.com.br/ws/" + cep + "/json/?callback=?", function (dados) {

                    if (!("erro" in dados)) {
                        //Atualiza os campos com os valores da consulta.
                        $("#logradouro").val(dados.logradouro.toUpperCase());
                        $("#bairro").val(dados.bairro.toUpperCase());
                        $("#select_state").siblings(".loading").remove();
                        $("#select_state").val(dados.uf.toUpperCase()).trigger('change');
                        $("#select_state").show();
                        $("#select_state").next(".select2-container").show();

                        $("#select_city").siblings(".loading").remove();
                        $("#select_city").html("<option value='" + dados.localidade.toUpperCase() + "' >"  + dados.localidade.toUpperCase() +  "</option>");
                        $("#select_city").val("").trigger("liszt:updated");
                        $("#select_city").show();
                        $("#select_city").next(".select2-container").show();
                        $("#select_city").val(dados.localidade.toUpperCase());


                        $("#numero").focus();

                        if(!(dados.bairro)){
                            $("#bairro").prop("readonly",false);
                            $("#bairro").focus();
                        }

                        if(!(dados.logradouro)){
                            $("#logradouro").prop("readonly",false);
                            $("#logradouro").focus();
                        }

                    } //end if.
                    else {
                        //CEP pesquisado não foi encontrado.
                        limpa_formulario_cep();

                        noty({
                            text: 'CEP não encontrado.',
                            layout: 'center',
                            modal: true,
                            buttons: [
                                {
                                    addClass: 'btn btn-primary',
                                    text: 'Alterar CEP',

                                    onClick: function ($noty) {
                                        $noty.close();
                                        $('#cep').focus();
                                    }
                                },
                                {
                                    addClass: 'btn btn-danger',
                                    text: 'Informar Endereço',
                                    onClick: function ($noty) {

                                        $("#select_city").siblings(".loading").remove();
                                        $("#select_city").html("<option value='' >Selecione a cidade</option>");
                                        $("#select_city").val("").trigger("liszt:updated");
                                        $("#select_city").show();
                                        $("#select_city").next(".select2-container").show();

                                        $noty.close();
                                        $('.select_state').select2({
                                            disabled: false
                                        });
                                        $("#logradouro").prop("readonly",false);
                                        $("#bairro").prop("readonly",false);

                                        $(".select_state").select2('focus');

                                    }
                                }
                            ]
                        });
                    }
                });
            } //end if.
            else {
                //cep é inválido.
                limpa_formulario_cep();
                noty({
                    text: 'CEP inválido!',
                    layout: 'top',
                    type: 'warning',
                    modal: true,
                    timeout: 2000,
                    onClick: function ($noty) {
                        $noty.close();
                    },
                    callback: {
                        onClose: function () {
                            $("#cep").focus();
                        },
                    }
                });
                return false;
            }
        } //end if.
        else {
            //cep sem valor, limpa formulário.
            limpa_formulario_cep();
        }
    });


    if($("#cep").val() != '' ){

        //Nova variável "cep" somente com dígitos.
        var cep = $("#cep").val().replace(/\D/g, '');
        //Verifica se campo cep possui valor informado.
        if (cep != "") {

            //Expressão regular para validar o CEP.
            var validacep = /^[0-9]{8}$/;

            //Valida o formato do CEP.
            if (validacep.test(cep)) {

                //Consulta o webservice viacep.com.br/
                $.getJSON("https://viacep.com.br/ws/" + cep + "/json/?callback=?", function (dados) {

                    if (("erro" in dados)) {
                        $('.select_state, .select_city').select2({
                            disabled: false
                        });
                        $("#logradouro").prop("readonly",false);
                        $("#bairro").prop("readonly",false);
                    }
                });
            }
        }
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