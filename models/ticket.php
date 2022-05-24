<?php
/**
 * 
 * Clase para unidades de medida para los articulos
 *  
 * @author Manuel Gabriel <ingmanuelgabriel@gmail.com|ingmanuelgabriel@hotmail.com>
 * @copyright Copyright (c) 2020, Manuel Gabriel | WELMASTER
 *
 *
**/

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require dirname(__file__,2).'/models/PHPMailer/src/Exception.php';
require dirname(__file__,2).'/models/PHPMailer/src/PHPMailer.php';
require dirname(__file__,2).'/models/PHPMailer/src/SMTP.php';

include_once dirname(__file__,2)."/config/conexion.php";
class Ticket
{
	private $db;
	private $data;//array of form post
	public $message;//message of result
	public $type;//type of menssage: red|green|orange|blue|purple|dark
	public $time;//set stopwatch
    private $path;
    private $extension;

	function __construct()
	{
		$this->db = new Conexion();
		$this->message = '';
		$this->type = 'green';
		$this->time = true;
		$this->data = array();
        $this->path = "../misc/adjunto/";
        $this->extension = array("image/jpg", "image/jpeg", "image/png", "image/gif");
	}

	public function getAllTickets():?array
    {
    	//$rows = $this->db->get_results("SELECT * FROM vw_productos");
		$rows = $this->db->get_results("SELECT
					T0.TicketId,
					T0.Usuario2Id,
					T0.TicketFecha,
					T0.TicketTitulo,
					T0.TicketEstadoId,
					T1.PrioridadDescripcion,
					T3.ServicioDescripcion,
					T2.TipoDescripcion
				FROM ticket T0
				INNER JOIN ticketprioridad T1 ON (T0.TicketPrioridadId = T1.TicketPrioridadId)
				INNER JOIN tickettipo T2 ON (T0.TicketTipoId = T2.TicketTipoId)
				INNER JOIN ticketservicio T3 ON (T0.TicketServicioId = T3.TicketServicioId)
			");
    	if ($rows)return $rows;
    	return null;
    }
	
	public function getTicketsId($id=NULL):?array
    {
		if( isIntN($id) ){
			//$rows = $this->db->get_results("SELECT * FROM vw_productos");
			$rows = $this->db->get_results("SELECT
					T0.TicketId,
					T0.Usuario2Id,
					T0.TicketFecha,
					T0.TicketTitulo,
					T0.TicketEstadoId,
					T1.PrioridadDescripcion,
					T3.ServicioDescripcion,
					T2.TipoDescripcion
				FROM ticket T0
				INNER JOIN ticketprioridad T1 ON (T0.TicketPrioridadId = T1.TicketPrioridadId)
				INNER JOIN tickettipo T2 ON (T0.TicketTipoId = T2.TicketTipoId)
				INNER JOIN ticketservicio T3 ON (T0.TicketServicioId = T3.TicketServicioId)
				WHERE T0.UsuarioId = '".$id."'  ");
			if ($rows)return $rows;
			return null;
		}else{
            return NULL;
        }
    }
	
	public function getTicketById($id = null):?OBJECT
    {
    	if( isIntN($id) ){
    		$rows = $this->db->get_row("SELECT * FROM ticket WHERE TicketId = '".$id."'");
            if ( !$rows ) return NULL;
            return $rows;
        }else{
            return NULL;
        }
    }
	
	public function getTicketById2($id = null):?OBJECT
    {
    	if( isIntN($id) ){
    		$rows = $this->db->get_row("
				SELECT
					T0.TicketId,
					T0.TicketFecha,
					T0.TicketTitulo,
					T1.PrioridadDescripcion,
					T3.ServicioDescripcion,
					T2.TipoDescripcion,
                    T0.TicketEstadoId,
					T0.UsuarioId,
					T0.Usuario2Id
				FROM ticket T0
				INNER JOIN ticketprioridad T1 ON (T0.TicketPrioridadId = T1.TicketPrioridadId)
				INNER JOIN tickettipo T2 ON (T0.TicketTipoId = T2.TicketTipoId)
				INNER JOIN ticketservicio T3 ON (T0.TicketServicioId = T3.TicketServicioId)
				WHERE T0.TicketId = '".$id."' ");
            if ( !$rows ) return NULL;
            return $rows;
        }else{
            return NULL;
        }
    }
	
	public function getTicketDetails($id = null):?array
    {
    	if( isIntN($id) ){
    		$rows = $this->db->get_results("
				SELECT
					T0.TicketdetalleId,
					T0.TicketId,
					T0.Fecha,
					T0.Descripcion, 
					T0.UsuarioId,
					T1.TicketAdjuntoId,
					T1.Adjunto
				FROM ticketdetalle T0
				LEFT JOIN ticketadjunto T1 ON (T0.TicketdetalleId=T1.TicketdetalleId)
				WHERE T0.TicketId = '".$id."' ");
            if ( !$rows ) return NULL;
            return $rows;
        }else{
            return NULL;
        }
    }
				
	public function getUsers():?array
    {
        $query0 = "SELECT UsuarioId, UsuarioNombre, UsuarioCorreo FROM usuario ";
        $rows = $this->db->get_results($query0);
        if ($rows)return $rows;
        return NULL;
    }
	
	public function getAgent():?array
    {
        $query0 = "SELECT UsuarioId, UsuarioNombre FROM usuario WHERE RolId = 1";
        $rows = $this->db->get_results($query0);
        if ($rows)return $rows;
        return NULL;
    }

    public function getFileAtt($id = null):?array
    {
        if( isIntN($id) ){
            $rows = $this->db->get_results("SELECT * FROM ticketadjunto WHERE TicketdetalleId = '".$id."' ");
            if ($rows)return $rows;
            return null;
        }else{
            return null;
        }
    }  

    public function getTypeT():?array
    {
        $query0 = "SELECT TicketTipoId, TipoDescripcion FROM tickettipo";
        $rows = $this->db->get_results($query0);
        if ($rows)return $rows;
        return NULL;
    }

    public function getPriority():?array
    {
        $query0 = "SELECT TicketPrioridadId, PrioridadDescripcion FROM ticketprioridad";
        $rows = $this->db->get_results($query0);
        if ($rows)return $rows;
        return NULL;
    }

    public function getService():?array
    {
        $query0 = "SELECT TicketServicioId, ServicioDescripcion FROM ticketservicio";
        $rows = $this->db->get_results($query0);
        if ($rows)return $rows;
        return NULL;
    }


    //validates that no field is empty, applies corresponding format to each field
    public function validData($post):bool 
    {
        if (isset($post['id'])) {
        	$this->data['idpk'] = (isIntN($post['id']))?$this->db->escape($post['id']): '';
        }else{
			$this->data['descrption'] = (!empty($post['comment']))? sentenceCase($this->db->escape($post['comment'])): '';
		}
        $this->data['user'] = (!empty($post['user']))?$post['user'] : '';
		$this->data['subjet'] = (!empty($post['subjet']))? sentenceCase($this->db->escape($post['subjet'])): NULL;
        $this->data['type'] = (isIntN($post['type']))?$this->db->escape($post['type']): '';
        $this->data['priority'] = (isIntN($post['priority']))?$this->db->escape($post['priority']): '';
		$this->data['service'] = (isIntN($post['service']))?$this->db->escape($post['service']): '';
		
        foreach ($this->data as $key => $value) {
            if (empty($value)) {
                $this->message = '<strong>Error [1001]</strong>: Un campo del formulario esta vació o no cumple con el formato correcto. Revise los valores ingresados';
                $this->type = 'red';
                return false;
            }
        }
		$this->data['asigned'] = (!empty($post['asigned']))?$post['asigned'] : '';
        //$this->data['file'] = isset($_FILES['images'])?(($_FILES['images']['size'])?'images':NULL):NULL;
        if (isset($this->data['idpk'])) {
        	if( $this->updateData($this->data) ) return true;
        }else{
        	if( $this->newData($this->data) ) return true;
        }
        return false;
    }
	
	public function validDataAs($post):bool 
    {
		$this->data['user'] = (isIntN($post['user']))?$this->db->escape($post['user']): '';
		$this->data['ticket'] = (isIntN($post['id']))?$this->db->escape($post['id']): '';
		
		foreach ($this->data as $key => $value) {
            if (empty($value)) {
                $this->message = '<strong>Error [1001]</strong>: Un campo del formulario esta vació o no cumple con el formato correcto. Revise los valores ingresados';
                $this->type = 'red';
                return false;
            }
        }
		
		if( $this->AssignTicket($this->data) ) return true;
        return false;
	}
	
	public function validCommen($post):bool 
    {
		$this->data['user'] = (isIntN($post['user']))?$this->db->escape($post['user']): '';
		$this->data['ticket'] = (isIntN($post['id']))?$this->db->escape($post['id']): '';
		$this->data['descrption'] = (!empty($post['comment']))? sentenceCase($this->db->escape($post['comment'])): '';
		
		foreach ($this->data as $key => $value) {
            if (empty($value)) {
                $this->message = '<strong>Error [1001]</strong>: Un campo del formulario esta vació o no cumple con el formato correcto. Revise los valores ingresados';
                $this->type = 'red';
                return false;
            }
        }
		
		if( $this->addComment($this->data) ) return true;
        return false;
	}
	
    private function newData($insert):bool
    {
        $msg = '';
		//Se asigna por defecto estado 1
        $query0 = "INSERT INTO ticket
            (TicketFecha, TicketTitulo, TicketPrioridadID, TicketTipoId, TicketServicioId, TicketEstadoId, UsuarioId)
            VALUES
            (
				NOW(),
				'".$insert['subjet']."',
				'".$insert['priority']."',
				'".$insert['type']."',
				'".$insert['service']."',
				'1',
				'".$insert['user']."'
			); ";
        if ( $this->db->query($query0) ) {
            $lastId = $this->db->insert_id;
            $query1 = "INSERT INTO ticketdetalle
                (TicketId, Fecha, Descripcion, UsuarioId)
				VALUES
                (
					'".$lastId."',
					NOW(),
					'".$insert['descrption']."',
					'".$insert['user']."'
				) ";
			if ( $this->db->query($query1) ) {
				$lastId2 = $this->db->insert_id;
				/*if (!empty($this->data['file'])) {
					$namefile = $lastId.'_'.date('YmdHis');
					$this->addImg($this->data['img'],$lastId,$namefile);
					$msg .= $this->message;
				}*/

				$this->message = 'El registro se ha agregado correctamente<br>';;
				return true;
			}else{
				$this->message = '<strong>Error [1003]</strong>: Los datos no fueron guardados debido a un erro interno. Intentelo de nuevo.';
				$this->type = 'red';
				return false;
			}
        }else{
        	$this->message = '<strong>Error [1003]</strong>: Los datos no fueron guardados debido a un erro interno. Intentelo de nuevo.';
        	$this->type = 'red';
        	return false;
        }
    }

    private function setUpdate($array):bool
    {
        $stored = $this->getTicketById($array['idpk']);
        $set =  '';

        if ($stored->Usuario2Id != $array['asigned']) {
            $set .= "Usuario2Id = '".$array['asigned']."', TicketEstadoId = '2',";
        }
        if ($stored->TicketTitulo != $array['subjet']) {
            $set .= "TicketTitulo = '".$array['subjet']."',";
        }
        if ($stored->TicketPrioridadId != $array['priority']) {
            $set .= "TicketPrioridadId = '".$array['priority']."',";
        }
        if ($stored->TicketTipoId != $array['type']) {
            $set .= "TicketTipoId = '".$array['type']."',";
        }
        if ($stored->TicketServicioId != $array['service']) {
            $set .= "TicketServicioId = '".$array['service']."',";
        }
		
        $set = trim($set, ',');//Elimina el ultimo caracter definido ','
        $this->data = array();//reset array
        $this->data['set'] = $set;

        if ( empty($set) ) {
            return false;
        }else{
            return true;
        }
    }

    private function updateData($array):bool
    {
        if( $this->setUpdate($array) ){//verifica si hay cambios para aplicar
        	$query0 = "UPDATE ticket  
        		SET ".$this->data['set']."
        		WHERE TicketId = '".$array['idpk']."' ";
				
        	if($this->db->query($query0)){
        		$result = true;
        	}else{
        		$this->message = '<strong>Error [1003]</strong>: Los datos no fueron guardados debido a un erro interno. Intentelo de nuevo.';
        		$this->type = 'red';
        		$result = false;
        	}
        }else{
            $this->message = 'No se ha detectado ningun cambio';
            $this->type = 'orange';
            $result = false;
        }
        return $result;
    }
	
	private function AssignTicket($array):bool
    {
		$query0 = "UPDATE ticket  
			SET Usuario2Id = '".$array['user']."', TicketEstadoId = '2'
			WHERE TicketId = '".$array['ticket']."' ";
		//$query0 = "UPDATE ticket SET Usuario2Id = NULL, TicketEstadoId = 1 ";
		if($this->db->query($query0)){
			$result = true;
		}else{
			$this->message = '<strong>Error [1003]</strong>: El ticket no pudo ser asignado debido a error interno. Intentelo de nuevo.';
			$this->type = 'red';
			$result = false;
		}
        return $result;
    }
	
	public function Closed($array):bool
    {
		if( isIntN($array['id']) ){
			$query0 = "UPDATE ticket  
				SET TicketEstadoId = '3'
				WHERE TicketId = '".$array['id']."' ";
				
			if($this->db->query($query0)){
				$result = true;
			}else{
				$this->message = '<strong>Error [1003]</strong>: El ticket no pudo ser asignado debido a error interno. Intentelo de nuevo.';
				$this->type = 'red';
				$result = false;
			}
		}else{
			return null;
		}
        return $result;
    }

	public function ROpen($array):bool
    {
		if( isIntN($array['id']) ){
			$query0 = "UPDATE ticket  
				SET TicketEstadoId = '2'
				WHERE TicketId = '".$array['id']."' ";
				
			if($this->db->query($query0)){
				$result = true;
			}else{
				$this->message = '<strong>Error [1003]</strong>: El ticket no pudo ser asignado debido a error interno. Intentelo de nuevo.';
				$this->type = 'red';
				$result = false;
			}
		}else{
			return null;
		}
        return $result;
    }
	
	private function addComment($insert):bool
    {
		$query0 = "INSERT INTO ticketdetalle
            (TicketId, Fecha, Descripcion, UsuarioId)
            VALUES
            (
				'".$insert['ticket']."',
				NOW(),
				'".$insert['descrption']."',
				'".$insert['user']."'
			); ";
			
		if($this->db->query($query0)){
			$result = true;
		}else{
			$this->message = '<strong>Error [1003]</strong>: El ticket no pudo ser asignado debido a error interno. Intentelo de nuevo.';
			$this->type = 'red';
			$result = false;
		}
        return $result;
    }
	

    public function addAttach($file,$id,$fName):bool
    {
        $msg = '';
        foreach($_FILES[$file]['name'] as $key => $val){
            $name = $_FILES[$file]['name'][$key];
            $tmp_img = $_FILES[$file]['tmp_name'][$key];
            $size = round(($_FILES[$file]['size'][$key]/1024/1024),2);
            $type = explode('/', $_FILES[$file]['type'][$key]);
            $type = end($type);
            $rename = $fName.'_'.$key.'.'.$type;

            if (!$_FILES[$file]['error'][$key]){
                if (in_array($_FILES[$file]['type'][$key], $this->extension)){
                        $query0 = "INSERT INTO tbl_producto2 
                            (Id_producto2, Id_producto, tamano, extension, nombre)
                            VALUES
                            (NULL,'".$id."','".$size."','".$type."','".$rename."')";
                        if ($this->db->query($query0)) {
                            if ($this->imageResize($tmp_img, $this->path.$rename)){
                                $msg .= "La imagen ".$name." se ha guardado correctamente.<br>";
                                $result = true;
                            }else{
                                $msg .= '<strong>Error [1503]</strong>: La imagen no se cargó, debido a un error interno<br>';
                                $result = false;
                            }
                        }else{
                            $msg .= '<strong>Error [1003]</strong>: No se ha podido agregar la imagen. Debido a un error interno.<br>';
                            $result = false;
                        }
                }else{
                    $msg .= "La imagen ".$name." no se ha guardado, tiene un formato desconocido.<br>";
                    $result = false;
                }
            }else{
                $msg .= "La imagen no se ha subido por el siguiente error: ".$_FILES[$file]['error'][$key]."<br>";
                $result = false;
            }
        }
        $this->message = $msg;
        return $result;
    }
	
	
	public function sendMail($subjet=null,$namerem=null,$idSender=NULL,$comment=NULL):bool
    {
        $company = 'Tu nombre empresa';
		
		$users = $this->getUsers();
        $mail = new PHPMailer();
        $mail->CharSet = 'UTF-8';
		
		$asigned = '';
		$mailUser = '';
		$header = (empty($namerem))?'<br/>':$namerem;


		foreach ( $users as $names ){
			if($idSender == $names->UsuarioId){
				$asigned = $names->UsuarioNombre;
				$mailUser = $names->UsuarioCorreo;
			}
		}

        try {
            //Server settings
            //$mail->SMTPDebug = SMTP::DEBUG_OFF;//Debug output
            $mail->isSMTP();//Send using SMTP
            $mail->Host       = $GLOBALS['app']['mail']['host'];//Set the SMTP server to send through
            $mail->SMTPAuth   = true;//Enable SMTP authentication
            $mail->Username   = $GLOBALS['app']['mail']['user'];//SMTP username
            $mail->Password   = $GLOBALS['app']['mail']['pass'];//SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;//Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
            $mail->Port       = $GLOBALS['app']['mail']['port'];//TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

            $mail->setFrom($GLOBALS['app']['mail']['user'], 'Ticket'); //Set the mail sender
			$mail->addAddress($mailUser);//Add a recipient

            //Content
            $body = file_get_contents(dirname(__file__,2).'/views/ticket/templateMail.html');//template
            $body = str_replace('{{date}}',date('d/m/Y'),$body);
            $body = str_replace('{{Company}}',$company,$body);
			$body = str_replace('{{recipient}}',$header,$body);
            $body = str_replace('{{comments}}',$comment,$body);
            //end content

            $mail->isHTML(true);//Set email format to HTML
            $mail->Subject = 'Asunto: '.$subjet;
            $mail->Body    = $body;
            $mail->send();
            return true; 
        } catch (Exception $e) {
            $this->message = $mail->ErrorInfo;
        }
        return false; 
    }
	
}
