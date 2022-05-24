
<div class="row">
    <div class="col-md-12">
        <div class="modal-content">
            <form class="form-horizontal" id="frmticket">
                <div class="modal-body">
                    <div class="card-body">
						<div class="form-group row">
                            <label for="subjet" class="col-sm-2 col-form-label">Asunto</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="subjet" id="subjet" placeholder="Asunto">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label" for="type">Tipo</label>
                            <div class="col-sm-10">
                                <select class="form-control select2" id="type" name="type" style="width: 100%;">
                                    <option value="" disabled selected>--Seleccione una opción--</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label" for="priority">Prioridad</label>
                            <div class="col-sm-10">
                                <select class="form-control select2" id="priority" name="priority" style="width: 100%;">
                                    <option value="" disabled selected>--Seleccione una opción--</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label" for="service">Servicio</label>
                            <div class="col-sm-10">
                                <select class="form-control select2" id="service" name="service" style="width: 100%;">
                                    <option value="" disabled selected>--Seleccione una opción--</option>
                                </select>
                            </div>
                        </div>
						
						<div class="form-group row">
                            <label class="col-sm-2 col-form-label" for="comment">Descripcion</label>
                            <div class="col-sm-10">
                                <textarea id="comment" name="comment" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
						
						<div class="form-group row">
                            <div class="col-sm-10 offset-sm-2">
                                <div class="file-loading">
                                    <!--<input id="images" type="file" name="images[]" accept="image/*" >-->
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-info" name="add" id="btnsbm">Guardar</button>
                    <button type="button" class="nav-opt btn btn-default float-right" href="home">
                        Cerrar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    $.getScript("./plugins/bootstrapfileinput/js/fileinput.min.js").done(function(){
        $.getScript("./plugins/bootstrapfileinput/themes/explorer-fas/theme.min.js").done(function(){
            $("#images").fileinput({
                'theme': 'explorer-fas',
                browseClass:'btn btn-outline-primary',
                browseLabel:'Agregar imagen',
                showUpload: false,
                showRemove: false,
                dropZoneEnabled:false,
                uploadUrl: "#",
                maxFileCount:1,
                layoutTemplates:{
                    actionUpload: '',
                    actionDownload: ''
                }
            });
        });
    });
    $.getScript("./views/ticket/js/script1.js");
</script>