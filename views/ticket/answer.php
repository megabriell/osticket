<div class="row">
    <div class="col-md-12">
        <div class="modal-content">
            <form class="form-horizontal" id="frmAnswer">
                    <input type="hidden" name="id" id="id">
                    <div class="card-body">
                        
						<div class="form-group row">
                            <label for="subjet" class="col-sm-2 col-form-label">Asunto</label>
                            <div class="col-sm-10" id="subjet">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label" for="type">Tipo</label>
                            <div class="col-sm-10" id="type">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label" for="priority">Prioridad</label>
                            <div class="col-sm-10" id="priority">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label" for="service">Servicio</label>
                            <div class="col-sm-10" id="service">
                            </div>
                        </div>
						
						<div class="form-group row">
                            <label class="col-sm-2 col-form-label" for="asigned">Asignado</label>
                            <div class="col-sm-10" id="asigned">
                            </div>
                        </div>
						
						<div class="form-group row">
                            <label class="col-sm-2 col-form-label" for="comment">Repuesta</label>
                            <div class="col-sm-10">
                                <textarea id="comment" name="comment" class="form-control" rows="5"></textarea>
                            </div>
                        </div>
						
                        <div class="col-sm-12">
                            <button type="submit" class="btn btn-info float-right" name="addComm" id="btnsbm">
                                Comentar
                            </button>
							<button type="button" class="btn btn-warning float-right" id="T_close">
                                Cerrar Ticket
                            </button>
                            <button style="display:none" type="button" class="btn btn-success float-right" id="T_ropen">
                                Abrir Ticket
                            </button>
							<button type="button" class="nav-opt btn btn-default float-right" href="home">
                                Salir
                            </button>
                        </div>
                    </div>
                </form>
				<div class="card-footer">
				
					<div class="row">
						<div class="col-12" id="CommentsDetail">
							<h4>Historial</h4>
							
						</div>
					</div>						
						
				</div>
            </div>
    </div>
</div>