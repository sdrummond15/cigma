jQuery(document).ready(function ($) {
    $("#jform_suites").mask("99");
    $("#jform_bedrooms").mask("99");
    $("#jform_bathrooms").mask("99");
    $("#jform_rooms").mask("99");
    $("#jform_area").mask("999999");
    $("#jform_garage").mask("99");
    $("#jform_gate_keys").mask("99");
    $("#jform_gate_controls").mask("99");
    $("#jform_total_area").mask('999999');
    $("#jform_building_area").mask('999999');
    $("#jform_sale_price").maskMoney({symbol: 'R$ ', showSymbol: true, thousands: '.', decimal: ',', symbolStay: true});
    $("#jform_rent_price").maskMoney({symbol: 'R$ ', showSymbol: true, thousands: '.', decimal: ',', symbolStay: true});
    $("#jform_sharing_price").maskMoney({symbol: 'R$ ', showSymbol: true, thousands: '.', decimal: ',', symbolStay: true});
    $("#jform_condominium").maskMoney({symbol: 'R$ ', showSymbol: true, thousands: '.', decimal: ',', symbolStay: true});
    $("#jform_iptu").maskMoney({symbol: 'R$ ', showSymbol: true, thousands: '.', decimal: ',', symbolStay: true});
    $("#jform_safe").maskMoney({symbol: 'R$ ', showSymbol: true, thousands: '.', decimal: ',', symbolStay: true});
    $("#jform_market_value").maskMoney({symbol: 'R$ ', showSymbol: true, thousands: '.', decimal: ',', symbolStay: true});
    $("#jform_number_energy").mask("99999999999999999999");
    $("#jform_number_water").mask("99999999999999999999");
    $("#jform_registry").mask("99999999999999999999");
    $("#jform_registry_office").mask("99");

    var idParameter = getUrlParameters("id", window.location.href, true);

    //Filtro Cidades por Estado
    $(".state").change(function(){
        var id = $("#" + this.id).val();
        $.ajax({
            url: 'components/com_enterprises/controllers/cities.php',
            data: {'id': id},
            type: 'POST'
        }).done(function(msg) {
            $(".city").html(msg);
            $(".city").val("your_value_from_ajax_response").trigger( "liszt:updated" );
        });
    });

    $(".state").load(function(){
        var id = $("#" + this.id).val();
        $.ajax({
            url: 'components/com_enterprises/controllers/cities.php',
            data: {'id': id},
            type: 'POST'
        }).done(function(msg) {
            $(".city").html(msg);
            $(".city").val("your_value_from_ajax_response").trigger( "liszt:updated" );
            $(".city").val("your_value_from_ajax_response").trigger( "liszt:updated" );
        });
    });
    ////

    //Filtro Para Proprietário por Usuário
    $("#jform_created_by_id").change(function(){
        var idUser = $(this).val();
        var name = $('#jform_owner_id input').attr('name');
        $.ajax({
            url:'components/com_enterprises/controllers/owner.php',
            type: "POST",
            data: {
                'id_user': idUser,
                'name': name,
                'id': idParameter,
            }
        }).done(function(msg) {
            $("#jform_owner_id").html(msg);
        })
    });

    //Campo obrigatório para valor Vender checked
    if($("#jform_business0").attr('checked')){
        $("#jform_sale_price").parents(".control-group").slideDown();
        $("#jform_sale_price").attr("required", true);
        $("#jform_sale_price").addClass('required');
        $("#jform_sale_price-lbl").append('<span class="star">&nbsp;*</span>');
    }else{
        $("#jform_sale_price").parents(".control-group").slideUp();
        $("#jform_sale_price").removeAttr("required", true);
        $("#jform_sale_price").removeClass('required');
        $("#jform_sale_price").val('');
        $("#jform_sale_price-lbl span").remove();
    }
    $("#jform_business0").change(function(){
        if($(this).attr('checked')){
            $("#jform_sale_price").parents(".control-group").slideDown();
            $("#jform_sale_price").attr("required", true);
            $("#jform_sale_price").addClass('required');
            $("#jform_sale_price").focus();
            $("#jform_sale_price-lbl").append('<span class="star">&nbsp;*</span>');
        }else{
            $("#jform_sale_price").parents(".control-group").slideUp();
            $("#jform_sale_price").removeAttr("required", true);
            $("#jform_sale_price").removeClass('required');
            $("#jform_sale_price").val('');
            $("#jform_sale_price-lbl span").remove();
        }
    });

    //Campo obrigatório para valor Alugar checked
    if($("#jform_business1").attr('checked')){
        $("#jform_rent_price").parents(".control-group").slideDown();
        $("#jform_rent_price").attr("required", true);
        $("#jform_rent_price").addClass('required');
        $("#jform_rent_price-lbl").append('<span class="star">&nbsp;*</span>');
    }else{
        $("#jform_rent_price").parents(".control-group").slideUp();
        $("#jform_rent_price").removeAttr("required", true);
        $("#jform_rent_price").removeClass('required');
        $("#jform_rent_price").val('');
        $("#jform_rent_price-lbl span").remove();
    }
    $("#jform_business1").change(function(){
        if($(this).attr('checked')){
            $("#jform_rent_price").parents(".control-group").slideDown();
            $("#jform_rent_price").attr("required", true);
            $("#jform_rent_price").addClass('required');
            $("#jform_rent_price").focus();
            $("#jform_rent_price-lbl").append('<span class="star">&nbsp;*</span>');
        }else{
            $("#jform_rent_price").parents(".control-group").slideUp();
            $("#jform_rent_price").removeAttr("required", true);
            $("#jform_rent_price").removeClass('required');
            $("#jform_rent_price").val('');
            $("#jform_rent_price-lbl span").remove();
        }
    });

    //Campo obrigatório para valor Partilhamento checked
    if($("#jform_business2").attr('checked')){
        $("#jform_sharing_price").parents(".control-group").slideDown();
        $("#jform_sharing_price").attr("required", true);
        $("#jform_sharing_price").addClass('required');
        $("#jform_sharing_price-lbl").append('<span class="star">&nbsp;*</span>');
    }else{
        $("#jform_sharing_price").parents(".control-group").slideUp();
        $("#jform_sharing_price").removeAttr("required", true);
        $("#jform_sharing_price").removeClass('required');
        $("#jform_sharing_price").val('');
        $("#jform_sharing_price-lbl span").remove();
    }
    $("#jform_business2").change(function(){
        if($(this).attr('checked')){
            $("#jform_sharing_price").parents(".control-group").slideDown();
            $("#jform_sharing_price").attr("required", true);
            $("#jform_sharing_price").addClass('required');
            $("#jform_sharing_price").focus();
            $("#jform_sharing_price-lbl").append('<span class="star">&nbsp;*</span>');
        }else{
            $("#jform_sharing_price").parents(".control-group").slideUp();
            $("#jform_sharing_price").removeAttr("required", true);
            $("#jform_sharing_price").removeClass('required');
            $("#jform_sharing_price").val('');
            $("#jform_sharing_price-lbl span").remove();
        }
    });

});

function getUrlParameters(parameter, staticURL, decode){

    var currLocation = (staticURL.length)? staticURL : window.location.search,
        parArr = currLocation.split("?")[1].split("&"),
        returnBool = true;

    for(var i = 0; i < parArr.length; i++){
        parr = parArr[i].split("=");
        if(parr[0] == parameter){
            return (decode) ? decodeURIComponent(parr[1]) : parr[1];
            returnBool = true;
        }else{
            returnBool = false;
        }
    }

    if(!returnBool) return false;
}