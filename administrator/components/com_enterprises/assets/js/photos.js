jQuery(document).ready(function ($) {

    $("#add").click(function () {
        var item = $(".photo:last-child").clone(true);
       $(".photo").parents(".controls").append("<br>").append(item.val(""));
    });

    $(".deleteImg").click(function (e) {
        var id = $(this).attr('name');

        var data = new FormData();
        data.append( 'id', id );

        $.ajax({
            url: 'components/com_enterprises/controllers/delete_photos.php',
            data: data,
            processData: false,
            contentType: false,
            type: 'POST',
            success: function ( response ) {
                if(response == 'success'){
                    noty({
                        text: 'Imagem removida com sucesso!',
                        layout: 'top',
                        type: 'success',
                        modal: true,
                        timeout: 4000,
                        onClick: function ($noty) {
                            $noty.close();
                        },
                        callback: {
                            onClose: function() {
                                $.ajax({ url: window.location.reload() });
                            },
                        }
                    });
                    return false;
                }else{
                    noty({
                        text: 'Erro ao remover a imagem!',
                        layout: 'top',
                        type: 'error',
                        modal: true,
                        timeout: 4000
                    });
                    return false;
                }
            }
        });

        e.preventDefault();


    });

});
