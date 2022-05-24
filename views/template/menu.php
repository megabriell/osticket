<aside class="main-sidebar sidebar-light-primary elevation-4">
    <a href="home" class="brand-link nav-opt">
        <img src="./misc/img/system/<?php echo $infoCompany['lprincipal'] ?>" alt="<?php echo $infoCompany['nombre']?>" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light"><?php echo $infoCompany['nombre']?></span>
    </a>

    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="./misc/img/user/<?php echo 'default.png';//$infoUser['fperfil']?>" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">
                    <?php echo $infoUser['name']?>
                </a>
            </div>
        </div>

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="true">
                <!-- Add icons to the links using the .nav-icon class with font-awesome or any other icon font library -->
                <li class="nav-item">
                    <a href="home" class="nav-link nav-opt">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>
				
				
				<li class="nav-item nav-parent">
					<a class="nav-link" href="">
						<i class="nav-icon fas fa-tags"></i>
						<p>Ticket <i class="right fas fa-angle-left"></i> </p>
					</a>
					<ul class="nav nav-treeview">
						<li class="nav-item">
							<a class="nav-link nav-opt" href="ticket/new" data-ref="0" >
								<i class="far fa-circle nav-icon"></i>Nuevo Ticket
							</a>
						</li>
					</ul>
				</li>
								
               
            </ul>
        </nav>
    </div>
</aside>