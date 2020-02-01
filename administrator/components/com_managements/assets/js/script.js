jQuery(document).ready(function ($) {

    $("#add").click(function () {
        var num = $("#positions .control-group:last-child label").text().split(" ");
        num = parseInt(num[0].slice(0, -1)) + 1;

        var position = $("#positions .control-group:last-child").clone();
        position.find('div.chzn-container').remove();
        position.find('select.positions').show();
        position.find('select.positions').removeAttr('id');
        position.find('select.positions').removeAttr('data-placeholder');
        position.find('select.positions').removeClass('chzn-done');
        position.find('select.positions').val(0);
        position.find('select.positions').chosen();
        position.find('div.chzn-container').width('220px');
        position.find('label').text(num +'ª Posição');

        $("#positions").append(position);


    });

    $(".remove").live('click', function () {
        $(this).parents('.control-group').remove();

        var divs = $("#positions .control-group").toArray();
        $.each(divs, function (index, value) {
            $(this).find('label').text((index+1)+'ª Posição');
        });
    });

});