<?php
$ggTipoBD=3;
$ggTipoCon=1;

//Clase para acceso a datos
class clsAccesoDatos{      
	//Codigo de Tabla
	public $gIdTabla;
	// Conexion BD
	private $gCnx;
	// Para ejecutar procedures
	public $gStmt;
	
	public $gError;
	public $gMsg;

	//Servidor de Base de Datos
	private $gServidor = "localhost";
	//Nombre de Base de Datos
	private $gBaseDatos = "miframework";
	//Nombre de Usuario
	//Tipo de Base Datos
	private $gTipoBD = 3; //1=SQLSERVER, 2=MYSQL, 3=POSTGRESQL
	public $gTipoConex = 1; //1=PDO, 2 = PDOSICA
	
	private $gFB;
	
	
	// Constructor de la clase
	function __construct($cnx = NULL){
		global $ggTipoBD;
		$this->gTipoBD=$ggTipoBD;
		if($this->gTipoBD==1){
			$this->gServidor = "192.168.1.11";
		}
		if($this->gTipoBD==2){
			$this->gServidor = "localhost";
			$user='root';
			$pass='root';
		}
		if($this->gTipoBD==3){
			$this->gServidor = "localhost";
			$user='postgres';
			$pass='123';
		}
		// Crea una Conexion SQLSERVER 2000
		//try {
		if($cnx){
			$this->gCnx = $cnx;
		}else{
			if($this->gTipoBD==1){
				$this->gCnx = new PDO("mssql:host=".$this->gServidor.";dbname=".$this->gBaseDatos,$user,$pass);
			}
			if($this->gTipoBD==2){
				$this->gCnx = new PDO("mysql:host=".$this->gServidor.";port=3306;dbname=".$this->gBaseDatos,$user,$pass);
			}
			if($this->gTipoBD==3){
				if($this->gTipoConex==1){
					$this->gCnx = new PDO("pgsql:host=".$this->gServidor.";port=5432;dbname=".$this->gBaseDatos,$user,$pass);
				}else{
					$this->gCnx = new PDOSICA("pgsql:host=".$this->gServidor.";port=5432;dbname=".$this->gBaseDatos,$user,$pass);
					//echo "Inicia::....".$this->gCnx->errorInfo();
				}
			}
		}
			//$this->gCnx->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
			//$PDO->setAttribute(PDO_ATTR_PERSISTENT, true);
		//} catch (PDOException $e) {
			//echo "Error:\n" . $e->getMessage();
		//}

		//mysql_query("SET NAMES 'utf8'");
	}
	
	
	
	// Destructor de la clase
	function __destruct(){
		//Cierra la Conexion BD
		try{
			unset($this->gCnx);
		} catch (PDOException $e) {
			return "Error:\n" . $e->getMessage();
		}
	}
	
	function getCnx(){
		return $this->gCnx;
	}
	
	function getTipoBD(){
		return $this->gTipoBD;
	}
	
	function obtenerDataSP($sql)
 	{
		if($this->gTipoBD>1){
			$sql = substr($sql,8,strlen($sql)-8);
			$valor = strpos($sql,' ');
			return  $this->obtenerDataSQL("SELECT * FROM ".substr($sql,0,$valor)."(".substr($sql,$valor, strlen($sql)-$valor).")");
		}else{
			$this->gStmt = $this->gCnx->prepare($sql);
			$this->gStmt->execute();
			if($this->gStmt->errorCode()=="00000"){
				$this->gError = $this->gStmt->errorInfo();
				return $this->gStmt;
			}else{
				$this->gError = $this->gStmt->errorInfo();
				return $this->gError[2];
			}		
		}
 	}

	function obtenerDataSQL($sql, $mill=true)
 	{
		if($this->gTipoBD==2){
			$sql = $this->millSQL($sql);
		}
		if($this->gTipoBD==3){
			/*if($mill){
				$sql = $this->millSQL($sql);
			}*/
			$sql = str_replace('LIKE','ILIKE',$sql);			
			$sql = str_replace('like','ilike',$sql);
		}
		//echo $sql;
		if($this->gTipoConex==1){
			$this->gCnx->query("SET DATESTYLE TO EUROPEAN, SQL;SET search_path = sica, pg_catalog;");
			$rst = $this->gCnx->query($sql);
			if($this->gCnx->errorCode()=="00000"){
				return $rst;
			}else{
				$this->gError = $this->gCnx->errorInfo();
				return $sql.$this->gError[2];
			}
		}else{
			$rst = $this->gCnx->prepare("SET DATESTYLE TO EUROPEAN, SQL;SET search_path = sica, pg_catalog;");
			$rst->execute();
			$rst = $this->gCnx->prepare($sql);
			if($rst->execute()){
				//echo "BIEN";
				return $rst;
			}else{
				//echo "MAL";
				$this->gError = $this->gCnx->errorInfo();
				return $sql.$this->gError[2];
			}
		}
 	}

	function ejecutarSP($sql)
 	{
		if($this->gTipoBD==2){
			$sql = substr($sql,8,strlen($sql)-8);
			$valor = strpos($sql,' ');
			return  $this->ejecutarSQL("CALL ".substr($sql,0,$valor)."(".substr($sql,$valor, strlen($sql)-$valor).")");
		}elseif($this->gTipoBD==3){
			$sql = substr($sql,8,strlen($sql)-8);
			$valor = strpos($sql,' ');
			return  $this->ejecutarSQL("SELECT ".substr($sql,0,$valor)."(".substr($sql,$valor, strlen($sql)-$valor).")");
		}else{
			$this->gStmt = $this->gCnx->prepare($sql);
			$this->gStmt->execute();
			if($this->gStmt->errorCode()=="00000"){
				$this->gMsg = "Guardado correctamente";
				return 0;
			}else{
				$this->gError = $sql.$this->gStmt->errorInfo();
				return 1;
			}
		}
 	}

	function ejecutarSQL($sql)
 	{
		if($this->gTipoConex==1){
			$this->gCnx->query("SET DATESTYLE TO EUROPEAN, SQL;SET search_path = public, pg_catalog;");
			$rst = $this->gCnx->query($sql);
			if($this->gCnx->errorCode()=="00000"){
				return 0;
			}else{
				$this->gError = $this->gCnx->errorInfo();
				return 1;
			}
		}else{
			$rst = $this->gCnx->prepare("SET DATESTYLE TO EUROPEAN, SQL;SET search_path = public, pg_catalog;");
			$rst->execute();			
			$rst = $this->gCnx->prepare($sql);
			//print_r( $rst);
			$rst->execute();
			if($rst->errorCode()=="SQLER"){
				$this->gError = $rst->errorInfo();
				return 1;
			}else{
				//$this->gMsg = $rst->errorCode();
				return 0;
			}
		}
		//if($rst->execute()){
		
		
		//$this->gCnx->query($sql);	
		//if($rst->errorCode()=="SQLER"){
			//return $rst->errorCode();//$this->gCnx->errorCode();
			//$this->gMsg = $rst->errorCode();
			//return 0;
		//}else{
			//$this->gError = $this->gCnx->errorInfo();
			//$this->gMsg = $this->gCnx->errorCode()."Guardado correctamente";
			//return 1;
		//}
 	}
		
	function iniciarTransaccion()
 	{
		if($this->gTipoBD==2){
   		$this->ejecutarSQL("START TRANSACTION;");
		}else{
		$this->ejecutarSQL("BEGIN TRANSACTION");
		}
 	}
	
	function abortarTransaccion()
 	{
		if($this->gTipoBD==2){
   		$this->ejecutarSQL("ROLLBACK;");
		}else{
		$this->ejecutarSQL("ROLLBACK TRANSACTION");
		}
   		
 	}
	
	function finalizarTransaccion()
 	{
		if($this->gTipoBD==2){
   		$this->ejecutarSQL("COMMIT;");
		}else{
		$this->ejecutarSQL("COMMIT TRANSACTION");
		}
   		
 	}
	
	function ControlaTransaccion()
 	{
		$rst=$this->obtenerDataSQL("SELECT @@ERROR AS ERROR");
		$dato=$rst->fetchObject();
		if($dato->ERROR=='0'){
			$this->finalizarTransaccion();
			return "Guardado correctamente";
		}else{
			$this->abortarTransaccion();
			return "Fallo Transacci�n";
		}
 	}

}
?>