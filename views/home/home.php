<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header border-transparent">
                <h3 class="card-title">Mis tickets</h3>
                <div class="card-tools">
                    <a class="btn btn-outline-primary btn-sm" onclick="add()" title="Agregar nuevo registro">
                        <i class="fas fa-plus"></i>agregar
                    </a>
                </div>
            </div>
            <div class="card-body p-0  d-flex justify-content-center">
                <div class="col-md-12">
                    <table id="dataTable0" class="table m-0 responsive" width="100%">
                        <thead class="bg-info">
                            <tr>
								<th>#</th>
                                <th>Fecha</th>
								<th>Fecha</th>
                                <th>Asunto</th>
                                <th>Prioridad</th>
                                <th>Servicio</th>
								<th>Tipo</th>
								<th>Asignado</th>
                                <th>Estado</th>
                                <th></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="subContent"></div>

<script type="text/javascript">
    $.getScript("./views/home/js/script.js");
</script>