$( document ).ready(function() {
    $.post("./controllers/ticket_controller").done(function(result){
        $('#subContent').html(result);
    })

	ajaxTable(
        '#dataTable0',
        [
            './controllers/ticket_controller',
            [
				{'data':'col8'},
                {'data':'col0'},
				{'data':'col1'},
                {'data':'col2'},
                {'data':'col3'},
                {'data':'col4'},
                {'data':'col5'},
				{'data':'col6'},
				{'data':'col7'},
                {'data':'btns','orderable': false}
            ],
            {getTable:''},
        ],
        [true,true,true,25]
    );
});

	
var add = function () {
	$.get("./views/ticket/new", function(content){
		$('#contentBody').html(content);
	});
},

update = function (id) {
    $.when(
        $.get("./views/ticket/edit", function(content){
            $('#contentBody').html(content);
			var slct4 = $('#asigned'),
				slct1 = $('#type'),
				slct2 = $('#priority'),
				slct3 = $('#service');
			$.post("./controllers/ticket_controller",{complent:''}).done(function(result){
				if (!$.isEmptyObject(result))
				{
					$.each(result.select1, function (i, item) {//options of select type
						slct1.append($('<option>', {
							value: item.value,
							text : item.caption
						}));
					});
					$.each(result.select2, function (i, item) {//options of select priority
						slct2.append($('<option>', { 
							value: item.value,
							text : item.caption
						}));
					});
					$.each(result.select3, function (i, item) {//options of select service
						slct3.append($('<option>', { 
							value: item.value,
							text : item.caption
						}));
					});
					$.each(result.select4, function (i, item) {//options of select asigned
						slct4.append($('<option>', {
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
    ).done(function() {
            $.post("./controllers/ticket_controller",{getField:'',id:id}).done(function(result){
                $('#id').val(id);
				$('#subjet').val(result.data1);
                $('#type').val(result.data2).trigger('change');
                $('#priority').val(result.data3).trigger('change');
                $('#service').val(result.data4).trigger('change');
                $('#asigned').val(result.data5).trigger('change');
				$('.select2').select2();
				$.getScript("./views/ticket/js/script2.js");//Script for validate fields
            })
    })
},

take = function (id) {
	$.post("./controllers/ticket_controller",{take:'',id:id}).done(function(result){
		result;
	});
},

answer = function (id) {
    $.when(
        $.get("./views/ticket/answer", function(content){
            $('#contentBody').html(content);
        })
    ).done(function() {
            $.post("./controllers/ticket_controller",{getdetails:'',id:id}).done(function(result){
                $('#id').val(id);
				$('#subjet').text(result.data3);
                $('#type').text(result.data6);
				$('#priority').text(result.data4);
				$('#service').text(result.data5);
				$('#asigned').text(result.data7);
				if (!$.isEmptyObject(result.data8))
				{
					$.each(result.data8, function (i, item) {//options of select type
						var attach = '';
						if(!$.isEmptyObject(item.adjunto)){
							attach = '<a href="#" class="link-black text-sm"><i class="fas fa-link mr-1"></i> Adjunto</a>';
						}
						$('#CommentsDetail').append('<div class="post"><div class="user-block">'+
							'<img class="img-circle img-bordered-sm" src="./misc/img/user/avatar.png" alt="user image"><span class="username">'+
							'<a href="#">'+item.user+'</a></span><span class="description">'+item.date+'</span></div>'+
							'<p>'+item.comment+'</p><p>'+ attach +'</p></div>'
						);
					});
				}
				if(result.data9 == 3){
					$('#T_close').hide();
					$('#T_ropen').show();
					$('#btnsbm').hide();
				}
				$.getScript("./views/ticket/js/script3.js");//Script for validate fields
				$('#T_close').click(function() {
					$.post("./controllers/ticket_controller",{tClosed:'',id:id}).done(function(result){
						result;
					});
				});
				$('#T_ropen').click(function() {
					$.post("./controllers/ticket_controller",{tROpen:'',id:id}).done(function(result){
						result;
					});
				});
            })
    })
};