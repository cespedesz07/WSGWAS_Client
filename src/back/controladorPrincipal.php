<?php

require '../../lib/nusoap/lib/nusoap.php';




//Método para enviar el archivo .ped cargado desde la GUI hasta el Web Service WSGWAS.war
//Retorna el snpJsonArray con los cálculos para cada uno de los SNP (Frecuencias Alelicas, Genotipicas, ...)
function cargarArchivoWebService( $archivoEntrada, $rutaSnpJsonArray ){
	$urlWebService = "http://localhost:8080/WSGWAS/WSGWAS?wsdl";

	//Se establece la conexion con el WebService
	$conexionWS = new nusoap_client( $urlWebService, 'WSDL' );
	$error = $conexionWS->getError();
	if ( $error ){
		echo "Error al establecer la conexion con el Web Service: ";
		print_r($error);
		die();
	}

	$metodoWebService = "procesarArchivoPedWS";
	$parametros = array(  "archivo"=>$archivoEntrada['tmp_name']  );
	//Se invoca el metodo "procesarArchivoPedWS" para generar los resultados del procesamiento
	//y guardarlos en un archivo plano dentro del servidor.
	$resultadoProcesamiento = $conexionWS->call( $metodoWebService, $parametros );
	$error = $conexionWS->getError();

	if ( $error ){
		echo "Error al invocar el metodo procesarArchivoPedWS: ";
		print_r($error);
		die();
	}
	else{
                /*
		$snpJsonArray = $resultadoProcesamiento['return'];
                
		//Se almacena el snpJsonArray en el servidor
		file_put_contents( $rutaSnpJsonArray, $snpJsonArray );
		
		//Y Se almacena en una cookie la ruta del snpJsonArray.json almacenado
                //con el fin de utilizar la ruta para generar el PDF
		setcookie( 'rutaSnpJsonArray', $rutaSnpJsonArray );
		

		//Se configura la cabecera de la respuesta del servidor Apache
		//para retornar el resultado en formato JSON
		header('Content-Type: application/json;charset=utf-8');
		echo json_encode( $snpJsonArray );
                */


        $rutaSnpJsonArray = $resultadoProcesamiento['return'];
        setcookie( 'rutaSnpJsonArray', $rutaSnpJsonArray );                

        $snpJsonArray = file_get_contents( $rutaSnpJsonArray );
        ob_start( "ob_gzhandler" );    
        header( "Content-type: application/json" );
        echo json_encode( $rutaSnpJsonArray );

		/*
		$resultado = $resultadoProcesamiento['return'];
                $contenido = file_get_contents($resultado);
		$snpJsonArray = json_encode( $contenido );
		print_r( $snpJsonArray ) . "<br><br>";

                
		//Acceder al primer snpJsonObject
		$snpJsonObject0 = $snpJsonArray[0];
		echo print_r( $snpJsonObject0 ) . "<br><br>";

		//Acceder al arreglo de funciones snpJsonArray
		$snpJsonArray = $snpJsonObject0 -> SNP0;
		echo print_r( $snpJsonArray ) . "<br><br>";
		
		//jsonObjects con cada uno de los calculos
		$frecAlelicasTodos = $snpJsonArray [0];
		$frecAlelicasCasos = $snpJsonArray [0];

		echo "Frec Alelicas Todos: Frec: " . print_r($frecAlelicasTodos -> Frec) .  "<br><br>";
		echo "Frec Alelicas Casos: Frec: " . print_r($frecAlelicasCasos -> Frec) .  "<br><br>";
                */
		
	}
	//Se libera espacio en la memoria
	$archivoEntrada = null;
	$conexionWS = null;
	$parametros = null;
	$resultadoProcesamiento = null;
	$resultado = null;
}

















//-----------------------------------------Ejecución Principal del Archivo en el Servidor Apache---------------------------------

//Se inicia una nueva sesión con el fin de almacenar rutas temporales
//de los archivos que se generarán en cookies

session_start();


if (  isset( $_POST['btnCargar'] )  ){
	$archivoEntrada = $_FILES['archivo'];
	//Se inicializan algunas variables esenciales para la invocación de otros métodos
	//Ruta del archivo donde se va a almacenar el snpJsonArray luego de ser porocesado
	//por el Web Service WSGWAS.war
	$nombreArchivo = str_replace( ".ped" , "", $archivoEntrada['name'] );
	$rutaSnpJsonArray = "snpJsonArray" . $nombreArchivo . ".json";       
	cargarArchivoWebService( $archivoEntrada, $rutaSnpJsonArray ); 


        /*
        $otro = fopen( "/home/santiago/otro.json", "r" );
        while (  !feof( $otro )  ){
            echo fgets( $otro ) . "<br>";
            ob_flush();
            flush();        
        }
        fclose( $otro );
        ob_end_flush();
        */
}









?>
