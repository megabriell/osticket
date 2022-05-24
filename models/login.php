<?php
/**
 * 
 * Clase que valida el acceso a usuario registrados
 *  
 * @author Manuel Gabriel <ingmanuelgabriel@gmail.com|ingmanuelgabriel@hotmail.com>
 * @copyright Copyright (c) 2020, Manuel Gabriel | WELMASTER
 *
 *
**/

include_once dirname(__file__,2)."/config/conexion.php";
class Login
{
    private $db;
    private $data;//array of form post
    public $changepwd;
    public $message;//message of result
    public $type;//type of menssage: red|green|orange|blue|purple|dark
    public $time;//set stopwatch

    public function __construct(){
        $this->db = new Conexion();
        $this->data = array();
        $this->changepwd = false;
        $this->message = '';
        $this->type = 'green';
        $this->time = true;
    }

    public Static function doLogout()
    {
        session_start();
        $_SESSION = array();// Destruir todas las variables de sesión.
        session_destroy();
        setcookie("_data0U", "", time() - 10);
        setcookie("_data1C", "", time() - 10);
        $url = 'http://' . $_SERVER['HTTP_HOST'];
        $url .= rtrim(dirname($_SERVER['PHP_SELF'],2), '/\\');//PHP_SELF: retorna el nombre y ruta del archivo de script ejecutándose actualmente
        header("location:".$url."/views/login/");
    }
    
    public function validData($post):bool
    {
        $this->data['correo'] = (isString($post['user']))? $this->db->escape(lowerCase($post['user'])) : '' ;
        //$this->data['contrasena'] = (isString($post['pwd']))? $this->db->escape($post['pwd']) : '';
        foreach ($this->data as $key => $value) {
            if ( empty($value) ) {
                $this->message = '<strong>Error [1001]</strong>: Un campo del formulario esta vació o no cumple con el formato correcto. Revise los valores ingresados';
                $this->type = 'red';
                return false;
            }
        }
        return ( $this->Login($this->data) )?  true : false;
    }   

    private function Login($data):bool
    {
        try{
            $query0 = "SELECT T0.*, T1.DepartamentoDescripcion, T2.RolDescripcion
                FROM usuario T0
				INNER JOIN departamento T1 ON (T0.DepartamentoId = T1.DepartamentoId)
				INNER JOIN rol T2 ON (T0.RolId = T2.RolId)
                WHERE T0.UsuarioCorreo = '".$this->data['correo']."' ";
            $user = $this->db->get_row($query0);
            if( $user ){
                if ( $user->Estado > 0 ) {
                    /*if ( password_verify($this->data['contrasena'], $user->pwd) ) {
                        if ($user->actualiza_pwd) {//cambiar contraseña
                            $inUser = $this->infoUser($user->id_empleado);
                            $this->changepwd = true;
                            $this->message = $inUser['usuario'];
                        }else{*/
                            session_start();
                            $_SESSION['idusuario'] = $user->UsuarioId;
                            $_SESSION['rol'] = $user->RolId;//contains the user system role
                            $_SESSION['session'] = (37197314);
                            $_SESSION['csrf'] = base64_encode(openssl_random_pseudo_bytes(32));
							
							$array = [];
							$array['id'] = $user->UsuarioId;
							$array['email'] = $user->UsuarioCorreo;
							$array['name'] = $user->UsuarioNombre;
							$array['idDepart'] = $user->DepartamentoId;
							$array['departament'] = $user->DepartamentoDescripcion;
							$array['idRol'] = $user->RolId;
							$array['rol'] = $user->RolDescripcion;							
                            setcookie("_data0U", encode('encrypt',json_encode($array)),0, "/");//Create-Cookie data of user

                            $inCompany = $this->infoCompany();
                            setcookie("_data1C", encode('encrypt',json_encode($inCompany)), 0 , "/");//Create-Cookie data of company
                        return true;
                        /*}
                        return true;
                    } else {
                        throw new Exception("La contraseña no coincide.");
                    }*/
                } else {
                    throw new Exception("Su usuario no tiene acceso al Sistema. Contacte al administrador");
                }
            } else {
                throw new Exception("Usuario o correo no coinciden.");
            }
        }catch(Exception $e){
            $this->type = 'orange';
            $this->message = $e->getMessage();
        }
        return false;
    }


    public function infoCompany():?array//Return function array or null "?array"
    {
        $array = [];
        $query0 = "SELECT * FROM empresa";
        $row = $this->db->get_row($query0);
        if ($row) {
            $array['id'] = $row->EmpresaId;
            $array['nombre'] = $row->EmpresaNombre;
            $array['lprincipal'] = !empty($row->Logo1)? $row->Logo1 : 'default.png';
            $array['lsegundario'] = !empty($row->Logo2)? $row->Logo2 : 'default.png';
            $array['favicon'] = !empty($row->Logo3)? $row->Logo3 : 'default.ico';
        }else{
            $array = NULL;
        }
        return $array;
    }
}
?>
