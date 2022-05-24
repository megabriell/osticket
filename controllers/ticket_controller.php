<?php
/**
 * 
 * Controllador gestionar para la clase ticket
 *  
 * @author Manuel Gabriel <ingmanuelgabriel@gmail.com|ingmanuelgabriel@hotmail.com>
 * @copyright Copyright (c) 2020, Manuel Gabriel | WELMASTER
 *
 *
**/

	include_once dirname(__file__,2).'/models/ticket.php';
	$sessionUser = Conexion::session();
	//avoid Cross-site request forgery
	if( empty($sessionUser) || $_SERVER['HTTP_CSRF'] !== $sessionUser['Token'])
	{
		header('Content-Type: application/javascript');
		$url = 'http://' . $_SERVER['HTTP_HOST'];
        $url .= rtrim(dirname($_SERVER['PHP_SELF'],2), '/\\');
		echo "$.alert({
			title: 'Error Interno',
			content: '<strong>Error [1007]</strong>: No se han procesado los datos correctamente. Notifique el error si se vuelve a presentar',
			type: 'red',
			typeAnimated: true,
			buttons: {
				Ok: function () {
					window.location.href = '".$url."/views/login/';
				}
			}
		});";
		return false;
	}

	$ticket = new Ticket();//instance
	$contentMessage = '';
	$timeMessage = '';
	$typeMessage = '';//type of menssage: red|green|orange|blue|purple|dark
	$inf_user = json_decode(encode('decrypt',$_COOKIE["_data0U"]),true);//Cookie, info user
	

	if(isset($_POST['getTable'])) {
		header('Content-Type: application/json');
		if($sessionUser['rol'] == 1){
			$rows = $ticket->getAllTickets();
		}else{
			$rows = $ticket->getTicketsId($sessionUser['iduser']);
		}
		
		$user = $ticket->getUsers();
		$json['data']=[];
	    if ($rows) {
	        foreach ( $rows as $row ){
	            $id = encode('encrypt',$row->TicketId);
				$asigned = '';
	        	$btnEdit = '';
				
				if(empty($row->Usuario2Id)){
					$btnEdit .= '<button type="button" class="btn btn-outline-primary btn-sm disabled" title="Agregar respuesta"> <i class="fas fa-check-circle"></i> Responder</button>';
				}else{
					$btnEdit .= '<button type="button" class="btn btn-outline-primary btn-sm" title="Agregar respuesta" onclick="answer(\''.$id.'\')"> <i class="fas fa-check-circle"></i> Responder</button>';
				}
				
	            
				if($sessionUser['rol'] == 1){//Si es admin muestra el boton de editar
					$btnEdit .= '<button type="button" class="btn btn-outline-warning btn-sm" title="Actualizar regsitro" onclick="update(\''.$id.'\')"> <i class="fas fa-edit"></i> Actualizar</button>';
				}
				if($sessionUser['rol'] == 1 && empty($row->Usuario2Id)){//Si no se ha asignado y es admin
					$btnEdit .= '<button type="button" class="btn btn-outline-secondary btn-sm" title="Asignarme el ticket" onclick="take(\''.$id.'\',$(this))" id="'.$id.'"> <i class="fas fa-edit"></i> Tomar Ticket</button>';
				}
	            
				foreach ( $user as $names ){
					if($row->Usuario2Id == $names->UsuarioId){$asigned = $names->UsuarioNombre;}
				}

	            $json['data'][] = [
					'col8'=>$row->TicketId,
	            	'col0'=>fDateDefault($row->TicketFecha),
					'col1'=>lblDateFull(dDifference($row->TicketFecha,date("Y-m-d H:i:s")),'ymd'),
	                'col2'=>$row->TicketTitulo,
	                'col3'=>$row->PrioridadDescripcion,
	                'col4'=>$row->ServicioDescripcion,
	                'col5'=>$row->TipoDescripcion,
					'col6'=>$asigned,
					'col7'=>$app['status'][$row->TicketEstadoId],
	                'btns'=>$btnEdit
	            ];
	        }
	        echo json_encode($json);
	    }else{
	        echo json_encode($json);
	    }
	}
	
	if ( isset($_POST['complent']) ) {
		header('Content-Type: application/json');
		$data0 = $ticket->getTypeT();
		$data1 = $ticket->getPriority();
		$data2 = $ticket->getService();
		$data3 = $ticket->getAgent();
		$json=[];
		if ($data0)
		{
			foreach ($data0 as $value) {
				$json['select1'][] = [
					'value'=>$value->TicketTipoId,
					'caption'=>$value->TipoDescripcion
				];
			}
		}
		if ($data1)
		{
			foreach ($data1 as $value) {
				$json['select2'][] = [
					'value'=>$value->TicketPrioridadId,
					'caption'=>$value->PrioridadDescripcion
				];
			}
		}
		if ($data2)
		{
			foreach ($data2 as $value) {
				$json['select3'][] = [
					'value'=>$value->TicketServicioId,
					'caption'=>$value->ServicioDescripcion
				];
			}
		}
		if ($data3)
		{
			foreach ($data3 as $value) {
				$json['select4'][] = [
					'value'=>$value->UsuarioId,
					'caption'=>$value->UsuarioNombre
				];
			}
		}
		echo json_encode($json);
	}

	
	if(isset($_POST['add'])) {
		header('Content-Type: application/javascript');
		$_POST['user'] = $sessionUser['iduser'];
		if( $ticket->validData($_POST) ){
			$contentMessage = 'Se ha agregado un nuevo registro correctamente';
			if (!empty($ticket->message)) $contentMessage = $ticket->message;
			$typeMessage = $ticket->type;
		}else{
			if (!empty($ticket->message)) {
				$contentMessage = $ticket->message;
				$typeMessage = $ticket->type;
			}else{
				$contentMessage = '<strong>Error [1005]</strong>: se produjo un error al procesar los datos. Intentelo de nuevo.';
				$typeMessage = 'red';
			}
		}
		$timeMessage = ($ticket->time)? "autoClose: 'Ok|15000'," : '';
		echo "$.alert({
			title: false,
			content: '".$contentMessage."',
			".$timeMessage."
			type: '".$typeMessage."',
			typeAnimated: true,
			buttons: {
				Ok: function(){
					$.get('./views/home/home', function(content){
						$('#contentBody').html(content);
					});
				}
			}
		});";
	}
	
	if(isset($_POST['take'])) {
		header('Content-Type: application/javascript');
		$_POST['id'] = encode('decrypt',$_POST['id']);
		$_POST['user'] = $sessionUser['iduser'];
		$msgEmail = '';
		
		if( $ticket->validDataAs($_POST) ){
			$contentMessage = 'Se ha asignado el ticket correctamente';
			$row = $ticket->getTicketById($_POST['id']);
			$msgEmail = 'Le notificamos que su ticket #'.$row->TicketId.' ha sido asignado a '.$inf_user['name'].'. En breve, recibira mas informacion e indicaciones.';
			
			$ticket->sendMail($row->TicketTitulo,NULL,$row->UsuarioId,$msgEmail);
			
			if (!empty($ticket->message)) $contentMessage = $ticket->message;
			$typeMessage = $ticket->type;
		}else{
			if (!empty($ticket->message)) {
				$contentMessage = $ticket->message;
				$typeMessage = $ticket->type;
			}else{
				$contentMessage = '<strong>Error [1005]</strong>: se produjo un error al procesar los datos. Intentelo de nuevo.';
				$typeMessage = 'red';
			}
		}
		$timeMessage = ($ticket->time)? "autoClose: 'Ok|15000'," : '';
		echo "$.alert({
			title: false,
			content: '".$contentMessage."',
			".$timeMessage."
			type: '".$typeMessage."',
			typeAnimated: true,
			buttons: {
				Ok: function(){
					$('#dataTable0').DataTable().ajax.reload(null,false);
				}
			}
		});";
		
	}
	
	if(isset($_POST['tClosed'])) {
		header('Content-Type: application/javascript');
		$_POST['id'] = encode('decrypt',$_POST['id']);
		$_POST['user'] = $sessionUser['iduser'];
		$msgEmail = '';
		
		if( $ticket->Closed($_POST) ){
			$contentMessage = 'Se ha cerrado el ticket correctamente';
			
			$row = $ticket->getTicketById($_POST['id']);//Detalle de ticket
			$user = $ticket->getUsers();//Listado de usuario
			foreach ( $user as $names ){//Recorrido de usuarios
				$asigned = ($row->UsuarioId == $names->UsuarioId)?'Estimado/a '.$names->UsuarioNombre.',':'';
			}
			$msgEmail = 'Le notificamos que su ticket #'.$row->TicketId.' ha dado por resuelto.';
			
			$ticket->sendMail($row->TicketTitulo,$asigned,$row->UsuarioId,$msgEmail);
			
			if (!empty($ticket->message)) $contentMessage = $ticket->message;
			$typeMessage = $ticket->type;
		}else{
			if (!empty($ticket->message)) {
				$contentMessage = $ticket->message;
				$typeMessage = $ticket->type;
			}else{
				$contentMessage = '<strong>Error [1005]</strong>: se produjo un error al procesar los datos. Intentelo de nuevo.';
				$typeMessage = 'red';
			}
		}
		$timeMessage = ($ticket->time)? "autoClose: 'Ok|15000'," : '';
		echo "$.alert({
			title: false,
			content: '".$contentMessage."',
			".$timeMessage."
			type: '".$typeMessage."',
			typeAnimated: true,
			buttons: {
				Ok: function(){
					$.get('./views/home/home', function(content){
						$('#contentBody').html(content);
					});
				}
			}
		});";
		
	}
	
	if(isset($_POST['tROpen'])) {
		header('Content-Type: application/javascript');
		$_POST['id'] = encode('decrypt',$_POST['id']);
		$_POST['user'] = $sessionUser['iduser'];
		
		if( $ticket->ROpen($_POST) ){
			$contentMessage = 'El ticket se ha abierto nuevamente';
			$row = $ticket->getTicketById($_POST['id']);
			$user = $ticket->getUsers();//Listado de usuario
			foreach ( $user as $names ){//Recorrido de usuarios
				$asigned = ($row->Usuario2Id == $names->UsuarioId)?'Estimado/a '.$names->UsuarioNombre.',':'';
			}
			$msgEmail = 'Le notificamos el ticket #'.$row->TicketId.' se ha re-aperturado.';
			
			$ticket->sendMail($row->TicketTitulo,$msgEmail,$row->Usuario2Id,$msgEmail);
			
			if (!empty($ticket->message)) $contentMessage = $ticket->message;
			$typeMessage = $ticket->type;
		}else{
			if (!empty($ticket->message)) {
				$contentMessage = $ticket->message;
				$typeMessage = $ticket->type;
			}else{
				$contentMessage = '<strong>Error [1005]</strong>: se produjo un error al procesar los datos. Intentelo de nuevo.';
				$typeMessage = 'red';
			}
		}
		$timeMessage = ($ticket->time)? "autoClose: 'Ok|15000'," : '';
		echo "$.alert({
			title: false,
			content: '".$contentMessage."',
			".$timeMessage."
			type: '".$typeMessage."',
			typeAnimated: true,
			buttons: {
				Ok: function(){
					$('#T_close').show();
					$('#T_ropen').hide();
					$('#btnsbm').show();
				}
			}
		});";
		
	}
	
	if(isset($_POST['update'])) {
		header('Content-Type: application/javascript');
		$_POST['user'] = $sessionUser['iduser'];
		$_POST['id'] = encode('decrypt',$_POST['id']);
		if( $ticket->validData($_POST) ){
			$contentMessage = 'El registro se ha actualizado correctamente';
			if (!empty($ticket->message)) $contentMessage = $ticket->message;
			$typeMessage = $ticket->type;
		}else{
			if (!empty($ticket->message)) {
				$contentMessage = $ticket->message;
				$typeMessage = $ticket->type;
			}else{
				$contentMessage = '<strong>Error [1005]</strong>: se produjo un error al procesar los datos. Intentelo de nuevo.';
				$typeMessage = 'red';
			}
		}
		$timeMessage = ($ticket->time)? "autoClose: 'Ok|15000'," : '';
		echo "$.alert({
			title: false,
			content: '".$contentMessage."',
			".$timeMessage."
			type: '".$typeMessage."',
			typeAnimated: true,
			buttons: {
				Ok: function(){
					$.get('./views/home/home', function(content){
						$('#contentBody').html(content);
					});
				}
			}
		});";
	}
	
	if(isset($_POST['addComm'])) {
		header('Content-Type: application/javascript');
		$_POST['user'] = $sessionUser['iduser'];
		$_POST['id'] = encode('decrypt',$_POST['id']);
		if( $ticket->validCommen($_POST) ){
			$contentMessage = 'La respuesta se ha agregado correctamente';
			//Content message email	######
			$row = $ticket->getTicketById($_POST['id']);
			$user = $ticket->getUsers();//Listado de usuario
			if($_POST['user'] == $row->Usuario2Id){//Si id es igual a Id usuario asignado
				foreach ( $user as $names ){//Recorrido de usuarios
					$asigned = ($row->UsuarioId == $names->UsuarioId)?'Estimado/a '.$names->UsuarioNombre.',':'';
					$sender = $row->UsuarioId;
				}
			}else{
				foreach ( $user as $names ){//Recorrido de usuarios
					$asigned = ($row->Usuario2Id == $names->UsuarioId)?'Estimado/a '.$names->UsuarioNombre.',':'';
					$sender = $row->Usuario2Id;
				}
			}
			//End content message email	###
			$ticket->sendMail($row->TicketTitulo,$asigned,$sender,$_POST['comment']);
			
			if (!empty($ticket->message)) $contentMessage = $ticket->message;
			$typeMessage = $ticket->type;
		}else{
			if (!empty($ticket->message)) {
				$contentMessage = $ticket->message;
				$typeMessage = $ticket->type;
			}else{
				$contentMessage = '<strong>Error [1005]</strong>: se produjo un error al procesar los datos. Intentelo de nuevo.';
				$typeMessage = 'red';
			}
		}
		$timeMessage = ($ticket->time)? "autoClose: 'Ok|15000'," : '';
		echo "$.alert({
			title: false,
			content: '".$contentMessage."',
			".$timeMessage."
			type: '".$typeMessage."',
			typeAnimated: true,
			buttons: {
				Ok: function(){
					$.get('./views/home/home', function(content){
						$('#contentBody').html(content);
					});
				}
			}
		});";
	}
	
	if(isset($_POST['getField'])) {
		header('Content-Type: application/json');
		$id = encode('decrypt',$_POST['id']);
		$row = $ticket->getTicketById($id);
		$json=[];
	    if ($row) {
	    	$json['data1'] = $row->TicketTitulo;
	    	$json['data2'] = $row->TicketTipoId;
	    	$json['data3'] = $row->TicketPrioridadId;
	    	$json['data4'] = $row->TicketServicioId;
			$json['data5'] = empty($row->Usuario2Id)?'':$row->Usuario2Id;
	        echo json_encode($json);
	    }else{
	        echo json_encode($json);
	    }
	}
	
	if(isset($_POST['getdetails'])) {
		
		header('Content-Type: application/json');
		$id = encode('decrypt',$_POST['id']);
		
		$row = $ticket->getTicketById2($id);
		$comments = $ticket->getTicketDetails($id);
		$user = $ticket->getUsers();
		
		$json=[];
		$asigned = '';
		
		
	    if ($row) {
			foreach ( $user as $names ){
				if($row->Usuario2Id == $names->UsuarioId){$asigned = $names->UsuarioNombre;}
			}
			
	    	$json['data1'] = $row->TicketId;
	    	$json['data2'] = $row->TicketFecha;
	    	$json['data3'] = $row->TicketTitulo;
	    	$json['data4'] = $row->PrioridadDescripcion;
			$json['data5'] = $row->ServicioDescripcion;
			$json['data6'] = $row->TipoDescripcion;
			$json['data7'] = $asigned;
			$json['data9'] = $row->TicketEstadoId;
			
			if (!empty($comments))
			{
				foreach ($comments as $value) {
					$Creator = '';
					foreach ( $user as $names ){
						if($value->UsuarioId == $names->UsuarioId){$Creator = $names->UsuarioNombre;}
					}
					$json['data8'][] = [
						'date'=>formatDateTime($value->Fecha,'d/m/Y H:i:s'),
						'comment'=>$value->Descripcion,
						'user'=>$Creator,
						'adjunto'=>$value->Adjunto
					];
				}
			}
	        echo json_encode($json);
	    }else{
	        echo json_encode($json);
	    }
	}
	
?>