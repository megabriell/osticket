<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Language" content="es"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Title</title>
    <link id="favicon" rel="icon" href="#" type="image/png">
    <link rel="stylesheet" href="../../AdminLTE/css/adminlte.min.css">
    <link rel="stylesheet" href="../../plugins/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="../../plugins/jquery-confirm/css/jquery-confirm.min.css">
    <link rel="stylesheet" href="../../plugins/bootstrapvalidator/css/bootstrapValidator.min.css">
</head>
<body class="hold-transition login-page">
<style type="text/css">
.has-error .form-control-feedback {right: -18px;}
.has-success .form-control-feedback {right: -18px;}
</style>
	<div class="login-box">
		<div class="card card-outline card-secondary">
			<div class="card-header text-center">
                <img src="#" alt="logo" width="180px" id='imglogin'>
            <p class="h2" id="tltlogin"></p>
            </div>
			<div class="card-body login-card-body">
				<p class="login-box-msg">Ingrese sus datos</p>

				<form id="frmlog" autocomplete="off">

					<div class="input-group mb-3">
						<input type="text" name="user" id="user" class="form-control" placeholder="Usuario">
						<div class="input-group-append">
							<div class="input-group-text">
								<span class="fas fa-user"></span>
							</div>
						</div>
					</div>

					<!--<div class="input-group mb-3">
						<input type="password" name="pwd" id="pwd" class="form-control" placeholder="Clave">
						<div class="input-group-append">
							<div class="input-group-text">
								<span class="fas fa-lock"></span>
							</div>
						</div>
					</div>-->

					<div class="row">
						<div class="col-12">
							<button type="submit" class="btn btn-primary float-right" name="login">
								Ingresar
							</button>
						</div>
					</div>
				</form>     
			</div>
		</div>
	</div>

    <script src="../../plugins/jQuery/jquery-3.5.1.min.js"></script>
    <script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../AdminLTE/js/adminlte.min.js"></script>
    <script src="../../plugins/bootstrapvalidator/js/bootstrapValidator.min.js"></script>
    <script src="../../plugins/jquery-confirm/js/jquery-confirm.min.js"></script>
    <script src="./js/script.js"></script>
</body>
</html>