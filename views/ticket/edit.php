<style type="text/css">
    .tab_{
border-top: 1px solid #dee2e6;
padding: 5px;
border-left: 1px solid #dee2e6;
border-right: 1px solid #dee2e6;
border-radius: 5px 5px 0px 0px;
    }
</style>
<div class="row">
    <div class="col-md-12">
        <div class="modal-content">
            <form class="form-horizontal" id="frmticket">
                    <input type="hidden" name="id" id="id">
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
                            <label class="col-sm-2 col-form-label" for="asigned">Usuario</label>
                            <div class="col-sm-10">
                                <select class="form-control select2" id="asigned" name="asigned" style="width: 100%;">
                                    <option value="" disabled selected>--Seleccione una opción--</option>
                                </select>
                            </div>
                        </div>
						
                        <div class="col-sm-12">
                            <button type="submit" class="btn btn-info float-right" name="update" id="btnsbm">
                                Actualizar
                            </button>
                            <button type="button" class="nav-opt btn btn-default float-right" href="home">
                                Cerrar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
    </div>
</div>