<?php

	/**********************************************************************
	*  Author: Justin Vincent (jv@jvmultimedia.com)
	*  Web...: http://twitter.com/justinvincent
	*  Name..: ezSQL_pdo
	*  Desc..: PDO component (part of ezSQL databse abstraction library)
	*
	*/

	/**********************************************************************
	*	Modified and adapted by
	*	Manuel Gabriel
	*	From: Guatemala
	*/
	
	
	/**********************************************************************
	*  ezSQL error strings - PDO
	*/
    
    global $ezsql_pdo_str;

	$ezsql_pdo_str = array
	(
		1 => 'Requiere $dsn y $user y $password para crear una conexión'
	);

	/**********************************************************************
	*  ezSQL Database specific class - PDO
	*/

	if ( !extension_loaded ('PDO') ) die('<b>Fatal Error:</b> El sistema requiere Libreria PDO para ser compilado y/o enlazado en el motor de PHP');
	if ( !class_exists ('ezSQLcore') ) die('<b>Fatal Error:</b> zSQL_pdo requiere ezSQLcore para ser incluido / debe ser cargado antes de que pueda ser usado');

	class ezSQL_pdo extends ezSQLcore
	{
		/*var $dsn;
		var $user;
		var $password;*/
		var $rows_affected = false;
		var $insert_id;
		var $last_result;
		var $num_rows;
		

		/**********************************************************************
		*  Intente conectarse al servidor de base de datos
		*/

		function connect($dsn='', $user='', $password='', $ssl='')
		{
			global $ezsql_pdo_str;
			$return_val = false;
			
			// Debe tener un dsn y usuario
			if ( ! $dsn || ! $user )
			{
				$this->register_error($ezsql_pdo_str[1].' in '.__FILE__.' on line '.__LINE__);
				$this->show_errors ? trigger_error($ezsql_pdo_str[1],E_USER_WARNING) : null;
			}
			
			//Establecer conexión PDO
			try 
			{
				if(!empty($ssl))
				{
					$this->dbh = new PDO($dsn, $user, $password, $ssl);
				}
				else
				{
					$this->dbh = new PDO($dsn, $user, $password);
				}
				$this->conn_queries = 0;
				$return_val = true;
			} 
			catch (PDOException $e) 
			{
				$this->register_error($e->getMessage());
				$this->show_errors ? trigger_error($e->getMessage(),E_USER_WARNING) : null;
			}

			return $return_val;			
		}

		/**********************************************************************
		* Ningún equivalente real de MySQL selecciona en PDO
		*  una vez más, función incluida por el motivo de consistencia
		*/

		function select($dsn='', $user='', $password='', $ssl=array())
		{
			return $this->connect($dsn, $user, $password);
		}
		
		/**********************************************************************
		*  Formatee una cadena correctamente para una inserción segura de PDO
		*  (no importa si las comillas mágicas están encendidas o no)
		*/

		function escape($str)
		{
			switch (gettype($str))
			{
				case 'string' : $str = addslashes(stripslashes($str));
				break;
				case 'boolean' : $str = ($str === FALSE) ? 0 : 1;
				break;
				default : $str = ($str === NULL) ? 'NULL' : $str;
				break;
			}

			return $str;
		}

		/**********************************************************************
		*  Devolver la sintaxis de fecha del sistema específico
		*  i.e. Oracle: SYSDATE Mysql: NOW()
		*/

		function sysdate()
		{
			return "NOW()";			
		}

		/**********************************************************************
		*  Se conecta al sistema de error de PDO y lo informa al usuario
		*/

		function catch_error()
		{
			$error_str = 'No error info';
						
			$err_array = $this->dbh->errorInfo();
			
			// Nota: Ignorar error - vincular o columna de índice fuera de rango
			if ( isset($err_array[1]) && $err_array[1] != 25)
			{
				
				$error_str = '';
				foreach ( $err_array as $entry )
				{
					$error_str .= $entry . ', ';
				}

				$error_str = substr($error_str,0,-2);

				$this->register_error($error_str);
				$this->show_errors ? trigger_error($error_str.' '.$this->last_query,E_USER_WARNING) : null;
				
				return true;
			}

		}

		// ==================================================================
		//	Consulta básica: consulte los documentos para obtener más detalles

		function query($query)
		{

			// Para expresiones reg
			$query = str_replace("/[\n\r]/",'',trim($query)); 

			// initialise return
			$return_val = 0;

			// Flush cached values..
			$this->flush();

			// Log how the function was called
			$this->func_call = "\$db->query(\"$query\")";

			// Keep track of the last query for debug..
			$this->last_query = $query;

			$this->count(true, true);

			// Start timer
			$this->timer_start($this->num_queries);

			// Use core file cache function
			if ( $cache = $this->get_cache($query) )
			{

				// Keep tack of how long all queries have taken
				$this->timer_update_global($this->num_queries);

				// Trace all queries
				if ( $this->use_trace_log )
				{
					$this->trace_log[] = $this->debug(false);
				}

				return $cache;
			}

			// If there is no existing database connection then try to connect
			if ( ! isset($this->dbh) || ! $this->dbh )
			{
				/*$this->connect($this->dsn, $this->user, $this->password,$this->configdb);
				// No existing connection at this point means the server is unreachable
				if ( ! isset($this->dbh) || ! $this->dbh )*/
					return false;
			}

			// Query was an insert, delete, update, replace
			if ( preg_match("/^(insert|delete|update|replace|drop|create)\s+/i",$query) )
			{		

				// Perform the query and log number of affected rows
				$this->rows_affected = $this->dbh->exec($query);
	
				// If there is an error then take note of it..
				if ( $this->catch_error() ) return false;

				$is_insert = true;

				// Take note of the insert_id
				if ( preg_match("/^(insert|replace)\s+/i",$query) )
				{
					$this->insert_id = @$this->dbh->lastInsertId();	
				}

				// Return number fo rows affected
				$return_val = $this->rows_affected;
	
			}
			// Query was an select
			else
			{

				// Perform the query and log number of affected rows
				$sth = $this->dbh->query($query);
	
				// If there is an error then take note of it..
				if ( $this->catch_error() ) return false;

				$is_insert = false;
				
				$col_count = $sth->columnCount();
				
				for ( $i=0 ; $i < $col_count ; $i++ )
				{
					$this->col_info[$i] = new stdClass();
					
					if ( $meta = $sth->getColumnMeta($i) )
					{					
						$this->col_info[$i]->name =  $meta['name'];
						$this->col_info[$i]->type =  !empty($meta['native_type']) ? $meta['native_type'] : 'undefined';
						$this->col_info[$i]->max_length =  '';
					}
					else
					{
						$this->col_info[$i]->name =  'undefined';
						$this->col_info[$i]->type =  'undefined';
						$this->col_info[$i]->max_length = '';
					}
				}

				// Store Query Results
				$num_rows=0;
				while ( $row = @$sth->fetch(PDO::FETCH_ASSOC) )
				{
					// Store relults as an objects within main array
					$this->last_result[$num_rows] = (object) $row;
					$num_rows++;
				}

				// Log number of rows the query returned
				$this->num_rows = $num_rows;

				// Return number of rows selected
				$return_val = $this->num_rows;

			}
			
			// disk caching of queries
			$this->store_cache($query,$is_insert);

			// If debug ALL queries
			$this->trace || $this->debug_all ? $this->debug() : null ;

			// Keep tack of how long all queries have taken
			$this->timer_update_global($this->num_queries);

			// Trace all queries
			if ( $this->use_trace_log )
			{
				$this->trace_log[] = $this->debug(false);
			}
			
			return $return_val;

		}

	}
