<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Language" content="es"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?php echo $infoCompany['nombre'] ?></title>

    <link rel="icon" href="misc/img/system/<?php echo $infoCompany['favicon']?>" type="image/png">
    <link rel="stylesheet" href="plugins/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="AdminLTE/css/adminlte.min.css">
    <link rel="stylesheet" href="plugins/OverlayScrollbars/css/OverlayScrollbars.min.css">
    <script src="plugins/jQuery/jquery-3.5.1.min.js"></script>
</head>

<body class="sidebar-collapse layout-navbar-fixed">
<div class="wrapper">
	<nav class="main-header navbar navbar-expand navbar-white navbar-light">
		<?php include_once 'navbar.php'; ?>
	</nav>

    <?php include_once 'menu.php'; ?>

	<div class="content-wrapper">
		<?php include_once 'content-header.php'; ?>
        <section class="content">
            <div class="container-fluid">