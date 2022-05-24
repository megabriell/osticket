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
			asigned: {
                validators: {
                    notEmpty: {
                        message: 'Seleccione una opcion'
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