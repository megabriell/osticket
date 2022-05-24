$( document ).ready(function() {
    $('#frmticket').bootstrapValidator({
        message: 'Este valor no es válido.',
        autoFocus: true,
        feedbackIcons: {
        valid: 'fa fa-check',
        invalid: 'fa fa-times',
        validating: 'fa fa-refresh'
        },
        fields: {
			subjet: {
                validators: {
					notEmpty: {
                        message: 'Agregue el asunto'
                    },
                    stringLength: {
                        max: 85,
                        message: 'Ingrese un nombre menor a 85 caracteres'
                    }
                }
            },
            type: {
                validators: {
                    notEmpty: {
                        message: 'Seleccione una opcion'
                    }
                }
            },
            priority: {
                validators: {
                    notEmpty: {
                        message: 'Seleccione una opcion'
                    }
                }
            },
			service: {
                validators: {
                    notEmpty: {
                        message: 'Seleccione una opcion'
                    }
                }
            },
            comment: {
                validators: {
					notEmpty: {
                        message: 'Detalle el problema'
                    },
                    stringLength: {
                        max: 150,
                        message: 'Ingrese un valor menor a 150 caracteres'
                    }
                }
            }
        }
    }).on('success.form.bv', function(form){
        form.preventDefault();
        var $form = $(form.target),
        option = $form.find('[type="submit"]:visible'),
        form_data = new FormData($form[0]);
        form_data.append(option.attr('name'), '');
        $('#btnsbm').btnload('loading');
        $.ajax({
            url: "./controllers/ticket_controller",
            type: "POST",
            data: form_data,
            processData: false,
            contentType: false,
            success: function(data){
                data;
                $('#btnsbm').btnload('reset');
            }
        });
    });

    $('.select2').select2();


    var slct1 = $('#type'),
        slct2 = $('#priority'),
        slct3 = $('#service');
    $.post("./controllers/ticket_controller",{complent:''}).done(function(result){
        if (!$.isEmptyObject(result))
        {
            $.each(result.select1, function (i, item) {//options of select category
                slct1.append($('<option>', {
                    value: item.value,
                    text : item.caption
                }));
            });
            $.each(result.select2, function (i, item) {//options of select brand
                slct2.append($('<option>', { 
                    value: item.value,
                    text : item.caption
                }));
            });
            $.each(result.select3, function (i, item) {//options of select measure
                slct3.append($('<option>', { 
                    value: item.value,
                    text : item.caption
                }));
            });
        }else{
            $.alert({
                title: 'Error',
                type: 'red',
                content: 'El formulario no fue cargado correctamente. Debe refrescar la pagina, '
                +'si el error persiste debe notificarlo.',
            });
        }
    })
})