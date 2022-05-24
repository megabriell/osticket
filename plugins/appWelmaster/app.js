/*!
 * Ing Manuel Gabriel (WelMaster)
 * Implementacion de funciones adicionales
 *
 * Requiere jQuery JavaScript Library
 *
 * @author Manuel Gabriel <ingmanuelgabriel@gmail.com|ingmanuelgabriel@hotmail.com>
 * @copyright Copyright (c) 2020, Manuel Gabriel | WELMASTER
 *
 * Date: 2020-10-20
*/

$('.main-sidebar, body').overlayScrollbars({normalizeRTL:true});

$(document).ready(function() {
    $("body").tooltip({ selector: '[data-toggle=tooltip]' });
});

//conflict two modal boostrapt
$(document).on("hidden.bs.modal", ".modal", function () {
    $(".modal:visible").length && $(document.body).addClass("modal-open");
});

//Funcion de animacion "cargndo.." a boton
//USE: $(select).btnload(option); loading/reset
$.fn.btnload = function(option) {
    var btn = $(this),
    message = (btn.attr('data-message'))?btn.attr('data-message'):'',//get data attribute
    text = $.trim(btn.text());//removes all newlines, spaces (including non-breaking spaces), and tabs
    if(btn.is( "button" )){//Check the current matched set of elements against a selector
        if (option == 'loading') {
            btn.prop("disabled", true);//add atribute disable
            if(message)btn.text(message),btn.attr('data-message',text);
            btn.append(' <span class="spinner-border spinner-border-sm"></span>');//Bootstrap spinners V4
        }else if (option == 'reset'){
            btn.find('span').remove();//find tags span and remove all tags
            if(message)btn.text(message),btn.attr('data-message',text);
            btn.prop("disabled", false);//remove atribute disable
        }else{
            console.log('Parametro nulo en funcion btnload(value)');
            return;
        }
    }else{
        console.log('Funcion btnload(value) aplica solo <button>');
        return;
    }
};

//Funcion que convierte serializeArray a Json
//USE: $(form).cnvtJson()
$.fn.cnvtJson = function() {
    var $form = $(this).serializeArray(), data = {};
    $($form ).each(function(index, obj){
        data[obj.name] = obj.value;
    });

    return data;
};


//get view of  the option selected of menu by Ajax
$(document).on('click', '.nav-opt', function(event) {
    event.preventDefault();
    var optM = $(this).attr("href"),
    optR = $(this).data("ref");
    $.post("./controllers/view_controller",{vw:optM,sbm:optR}).done(function(data){
        $('#contentBody').html(data)
    })
});

//get view of  the option selected of menu by Ajax
$(document).on('click', '.nav-rdrct', function(event) {
    event.preventDefault();
    var optM = $(this).attr("href");
    if( optM.indexOf('/') != -1 ){
       $.post("./views/"+optM).done(function(data){$('#contentBody').html(data)});
    }else{
       $.post("./views/"+optM+"/"+optM).done(function(data){$('#contentBody').html(data)});
    }
});

//Add style to active menu
$('.nav-opt').click(function(){
    $('.nav-link.active').removeClass('active');
    $(this).addClass('active');
    $(this).closest('li.nav-parent').children( ".nav-link" ).addClass('active')
});


//Add function Sum() to DataTable
$.fn.dataTable.Api.register( 'sum()', function ( ) {
    return this.flatten().reduce( function ( a, b ) {
        if ( typeof a === 'string' ) {
            a = a.replace(/[^\d.-]/g, '') * 1;
        }
        if ( typeof b === 'string' ) {
            b = b.replace(/[^\d.-]/g, '') * 1;
        }
        return a + b;
    }, 0 );
});

//Funcion para administrar datos de tabla usando DataTable
/******* Table with call ajax
PARAMETROS:
element(String): { id de la tabla para aplicar la funcion DataTable }
option[array](False|True): {
        [0]paginacion (boolean),
        [1]info total de registos en pie de tabla (boolean),
        [2]busqueda de DataTable (boolean),
        [3]numero filas a mostrar tabla (int)
}
order[array]: {
        [0]numero de columna a ordenar (int)
        [1] desc/asc (String) 
}
sum[array]: {
        [0]Numero de columna a sumar (int)
        [1]id de elemento para mostrar el total sumado (String)
        [2]simbolo de moneda (String)
}
********/
function simpleTable(element, option, order=[0,'desc'], sum=null)
{
    $(element).css({width:'100%'});
    var tabla = $(element).DataTable({
        responsive: true,
        //select:true,
        lengthMenu: [[10, 25, 50, 100, 500], [10, 25, 50, 100, 500]],
        paging: option[0],
        info: option[1],
        searching: option[2],
        pageLength: option[3],
        order: order,
        dom: "<'row'<'col-sm-6'l><'col-sm-6'f>>rtip",//Datatable dom
        drawCallback: function(){
            //verifica si el parametro sum no esta vacio para realizar la suma
            if (sum != null && sum.length >= 2) {
                var api = this.api(),
                sumCol = api.column(sum[0], {page:'current'}).data().sum(),
                //la funcion $.number pertenece a la libreria jQuery.number.min 
                format = $.number(sumCol , 2 , ".", "," );
                $(sum[1]).html(sum[2] + format);
            }
        }
    }).on( 'draw.dt', function () {
        tabla.rows(':not(.parent)').nodes().to$().find('td:first-child').trigger('click');
    }).on( 'responsive-resize', function ( e, datatable, columns ) {
        tabla.rows(':not(.parent)').nodes().to$().find('td:first-child').trigger('click');
    });
};

/******* Table with call ajax
PARAMETROS:
element(String): { id(jquery) de la tabla para aplicar la funcion DataTable }
ajax: {
        [0]:url del servidor donde se obtendra la informacion (String)
        [1]:Nombre de las columnas recibidas en formato Json [{'data':'Col0','data':'Col1'}] (array)
        [2]:envio de dato adicional, ejemplo: {id:15,email:'as@dd'} (array)
}
option[array](False|True): {
        [0]paginacion (boolean),
        [1]info total de registos en pie de tabla (boolean),
        [2]busqueda de DataTable (boolean),
        [3]numero filas a mostrar tabla (int)
}
order[array]: {
        [0]numero de columna a ordenar (int)
        [1] desc/asc (String) 
}
sum[array]: {
        [0]Numero de columna a sumar (int)
        [1]id de elemento para mostrar el total sumado (String)
        [2]simbolo de moneda (String)
}
report[array]: {
        [0](False|True) habilita/desabilitar botones de exportar. por defecto desctivado
        [1]Titulo de documento exportado (String)
}
********/
function ajaxTable(element, ajax, option, order=[0,'desc'], sum=null, report=[false,''])
{
    $(element).css({opacity:0,width:'100%'});
    $(element).parent()
    .prepend('<div class="d-flex justify-content-center">'
        +'<div class="spinner-border text-dark" style="width: 3rem; height: 3rem;" role="status">'
        +'<span class="sr-only">Loading...</span></div></div>');
    
    var table = $(element).DataTable({
        responsive: true,
        //select:true,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, 'Todo']],
        paging: option[0],
        info: option[1],
        searching: option[2],
        pageLength: option[3],
        ajax:{
            method:'POST',
            url:ajax[0],
            data: ( ajax[2] ) ? ajax[2] : {}
        },
        columns:ajax[1],
        order: order,
        dom: "<'#btnReport'><'row'<'col-sm-6'l><'col-sm-6'<'#filterAd'>f> >rtip",//Datatable dom
        drawCallback: function(){
            //verifica si el parametro sum no esta vacio para realizar la suma
            if (sum != null && sum.length >= 2) {
                var api = this.api(),
                sumCol = api.column(sum[0], {page:'current'}).data().sum(),
                //la funcion $.number pertenece a la libreria jQuery.number.min 
                format = $.number(sumCol , 2 , ".", "," );
                $(sum[1]).html(sum[2] + format);
            }
        }
    }).on( 'draw.dt', function () {
        $("div.spinner-border").remove();
        $(this).css({ opacity: 1});
        table.rows(':not(.parent)').nodes().to$().find('td:first-child').trigger('click');
    }).on( 'responsive-resize', function ( e, datatable, columns ) {
        table.rows(':not(.parent)').nodes().to$().find('td:first-child').trigger('click');
    });

    if (report[0]) {
        var titleReport = 'Report',
        date = new Date(),
        dateTime = "_"+date.getDate()+(date.getMonth()+1)+date.getFullYear()
            +"_"+date.getHours()+date.getMinutes()+date.getSeconds();
        
        if (report[1]) {
            titleReport = report[1];
        }
        //Constructor Buttons
        new $.fn.dataTable.Buttons( table, {
            buttons: [
                {
                    extend: 'print',
                    text: 'Imprimir',
                    title:titleReport+dateTime,
                    exportOptions: {
                        columns: ':not(.notexport)',//Ignora la columna de class notExport al Exportar
                        format: {
                            header:  function (data, columnIdx) {
                                return data.split('<')[0];
                            }
                        }
                    }
                },
                {
                    extend: 'excel',
                    title:titleReport+dateTime,
                    text: 'Excel',
                    exportOptions: {
                        columns: ':not(.notexport)',//Ignora la columna de class notExport al Exportar
                        format: {
                            header:  function (data, columnIdx) {
                                return data.split('<')[0];
                            }
                        }
                    }
                },
                {
                    extend: 'pdf',
                    text: 'PDF',
                    title:titleReport+dateTime,
                    exportOptions: {
                        columns: ':not(.notexport)',//Ignora la columna de class notExport al Exportar
                        format: {
                            header:  function (data, columnIdx) {
                                return data.split('<')[0];
                            }
                        }
                    }
                },{
                    extend: 'copy',
                    text: 'Copiar',
                    title:titleReport+dateTime,
                    exportOptions: {
                        columns: ':not(.notexport)',//Ignora la columna de class notExport al Exportar
                        format: {
                            header:  function (data, columnIdx) {
                                return data.split('<')[0];
                            }
                        }
                    }
                }
            ]
            
        });
        table.buttons().containers().appendTo( $('#btnReport') );//Agrega los botones de exportar, al div Creado en propiedad dom
        $('#btnReport').css({"padding": "8px"});
    }
};

/******* Table with call ajax for more than 100 records 
PARAMETROS:
element(String): { id de la tabla para aplicar la funcion DataTable }
ajax: {
        [0]:url del servidor donde se obtendra la informacion (String)
        [1]:Nombre de las columnas recibidas en formato Json [{'data':'Col0','data':'Col1'}] (array)
        [2]:envio de dato adicional, ejemplo: {id:15,email:'as@dd'} (array)
}
option[array](False|True): {
        [0]paginacion (boolean),
        [1]info total de registos en pie de tabla (boolean),
        [2]busqueda de DataTable (boolean),
        [3]numero filas a mostrar tabla (int)
}
order[array]: {
        [0]numero de columna a ordenar (int)
        [1] desc/asc (String) 
}
sum[array]: {
        [0]Numero de columna a sumar (int)
        [1]id de elemento para mostrar el total sumado (String)
        [2]simbolo de moneda (String)
}
report[array]: {
        [0](False|True) habilita/desabilitar botones de exportar. por defecto desctivado
        [1]Titulo de documento exportado (String)
}
********/
function ajaxTAdvanced(element, ajax, option, order=[0,'desc'], sum=null, report=[false,''])
{
    $(element).css({opacity:0,width:'100%'});
    $(element).parent()
    .prepend('<div class="d-flex justify-content-center">'
        +'<div class="spinner-border text-dark" style="width: 3rem; height: 3rem;" role="status">'
        +'<span class="sr-only">Loading...</span></div></div>');
    
    var table = $(element).DataTable({
        responsive: true,
        //select:true,
        lengthMenu: [[10, 25, 50, 100, 500], [10, 25, 50, 100, 500]],
        paging: option[0],
        info: option[1],
        searching: option[2],
        pageLength: option[3],
        processing: true,
        serverSide: true,
        ajax:{
            method:'POST',
            url:ajax[0],
            data: ( ajax[2] ) ? ajax[2] : {}
        },
        columns:ajax[1],
        order: order,
        dom: "<'#btnReport'><'row'<'col-sm-6'l><'col-sm-6'<'#filterAd'>f> >rtip",//Datatable dom
        drawCallback: function(){
            //verifica si el parametro sum no esta vacio para realizar la suma
            if (sum != null && sum.length >= 2) {
                var api = this.api(),
                sumCol = api.column(sum[0], {page:'current'}).data().sum(),
                //la funcion $.number pertenece a la libreria jQuery.number.min 
                format = $.number(sumCol , 2 , ".", "," );
                $(sum[1]).html(sum[2] + format);
            }
        }
    }).on( 'draw.dt', function () {
        $("div.spinner-border").remove();
        $(this).css({ opacity: 1});
    });
    if (report[0]) {
        var titleReport = 'Report',
        date = new Date(),
        dateTime = "_"+date.getDate()+(date.getMonth()+1)+date.getFullYear()
            +"_"+date.getHours()+date.getMinutes()+date.getSeconds();
        
        if (report[1]) {
            titleReport = report[1];
        }
        //Constructor Buttons
        new $.fn.dataTable.Buttons( table, {
            buttons: [
                {
                    extend: 'print',
                    text: 'Imprimir',
                    title:titleReport+dateTime,
                    exportOptions: {
                        columns: ':not(.notexport)',//Ignora la columna de class notExport al Exportar
                        format: {
                            header:  function (data, columnIdx) {
                                return data.split('<')[0];
                            }
                        }
                    }
                },
                {
                    extend: 'excel',
                    title:titleReport+dateTime,
                    text: 'Excel',
                    exportOptions: {
                        columns: ':not(.notexport)',//Ignora la columna de class notExport al Exportar
                        format: {
                            header:  function (data, columnIdx) {
                                return data.split('<')[0];
                            }
                        }
                    }
                },
                {
                    extend: 'pdf',
                    text: 'PDF',
                    title:titleReport+dateTime,
                    exportOptions: {
                        columns: ':not(.notexport)',//Ignora la columna de class notExport al Exportar
                        format: {
                            header:  function (data, columnIdx) {
                                return data.split('<')[0];
                            }
                        }
                    }
                },{
                    extend: 'copy',
                    text: 'Copiar',
                    title:titleReport+dateTime,
                    exportOptions: {
                        columns: ':not(.notexport)',//Ignora la columna de class notExport al Exportar
                        format: {
                            header:  function (data, columnIdx) {
                                return data.split('<')[0];
                            }
                        }
                    }
                }
            ]
            
        });
        table.buttons().containers().appendTo( $('#btnReport') );//Agrega los botones de exportar, al div Creado en propiedad dom
        $('#btnReport').css({"padding": "8px"});
    }
};
//Set lenguaje to DataTable
$.extend( true, $.fn.dataTable.defaults, {
    "language": {
        "decimal": ".",
        "thousands": ",",
        "info": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
        "infoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
        "infoPostFix": "",
        "infoFiltered": "(filtrado de un total de _MAX_ registros)",
        "loadingRecords": "Cargando...",
        "lengthMenu": "Mostrar _MENU_ registros",
        "paginate": {
            "first": "Primero",
            "last": "Último",
            "next": "Sig",
            "previous": "Ant"
        },
        "processing": "Procesando...",
        "search": "Buscar:",
        "searchPlaceholder": "Término de búsqueda",
        "zeroRecords": "No se encontraron resultados",
        "emptyTable": "Ningún dato disponible en esta tabla",
        "aria": {
            "sortAscending":  ": Activar para ordenar la columna de manera ascendente",
            "sortDescending": ": Activar para ordenar la columna de manera descendente"
        },
        //only works for built-in buttons, not for custom buttons
        "buttons": {
            "create": "Nuevo",
            "edit": "Cambiar",
            "remove": "Borrar",
            "copy": "Copiar",
            "csv": "fichero CSV",
            "excel": "tabla Excel",
            "pdf": "documento PDF",
            "print": "Imprimir",
            "colvis": "Visibilidad columnas",
            "collection": "Colección",
            "upload": "Seleccione fichero...."
        },
        "select": {
            "rows": {
                _: '%d filas seleccionadas',
                0: '',
                1: 'una fila seleccionada'
            }
        }
    }           
});