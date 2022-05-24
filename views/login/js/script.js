$.post("../../controllers/login_controller",{startpage:''}).done(function(data){//Load information for page form the DB
    $(document).attr('title', data.company);
    $('#favicon').attr('href','../../misc/img/system/'+data.favicon);
    $('#imglogin').attr('src','../../misc/img/system/'+data.img);
    $('#imglogin').attr('title', data.company);
    //$('#tltlogin').html('<b>'+data.company+'</b>');
});

$("#frmlog").bootstrapValidator({
    group: '.input-group',
    selector: '.form-control',
    feedbackIcons: {
        valid: 'fas fa-check',
        invalid: 'fas fa-times',
        validating: 'fas fa-refresh'
    },
    fields: {
        user: {
            validators: {
                notEmpty: {
                    message: 'Complete este campo'
                },
                stringLength: {
                    min: 4
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
    $.ajax({
        url: "../../controllers/login_controller",
        type: "POST",
        data:  form_data,
        processData: false,
        contentType: false,
        success: function(data){
            data;
        }
    });
});
