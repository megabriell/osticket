$('#frmAnswer').bootstrapValidator({
        message: 'Este valor no es válido.',
        autoFocus: true,
        feedbackIcons: {
        valid: 'fa fa-check',
        invalid: 'fa fa-times',
        validating: 'fa fa-refresh'
        },
        fields: {
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
                $('#btnsbm').btnload('reset');
				data;
            }
        });
    });