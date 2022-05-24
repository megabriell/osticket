    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="home" class="nav-link nav-opt">Home</a>
        </li>
    </ul>

    <ul class="navbar-nav ml-auto">
        <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
        <i class="fas fa-expand-arrows-alt"></i>
        </a>
        </li>

        <li class="nav-item dropdown user-menu">
            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
                <img src="./misc/img/user/<?php echo 'default.png';//$infoUser['fperfil']?>" class="user-image img-circle elevation-2" alt="User Image">
                <span class="d-none d-md-inline">
                    <?php echo $infoUser['name']?>
                </span>
            </a>
            <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                
                <li class="user-header bg-dark" style="height: auto">
                    <img src="./misc/img/user/<?php echo 'default.png';//$infoUser['fperfil']?>" class="img-circle elevation-2" alt="User Image">
                    <p>
                        <?php echo $infoUser['name']?>
                        <small>ID: <?php echo $infoUser['email']?></small>
                        <small><?php echo $infoUser['departament']?></small>
                    </p>
                </li>
                <li class="user-footer">
                    <a href="./controllers/login_controller?logout" class="btn btn-default btn-flat float-right">Cerrar sesion</a>
                </li>
            </ul>
        </li>

    </ul>