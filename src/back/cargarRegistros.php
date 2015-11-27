<?php


$rutaSnpJsonArray = $_COOKIE['rutaSnpJsonArray'];

//Se carga el snpJsonArray.json
$snpJsonArray = file_get_contents( $rutaSnpJsonArray );

$snpInicio = $_POST['snpInicio'];
$snpFin =    $_POST['snpFin'];
$snpJsonArrayDecode = json_decode( $snpJsonArray );


$resultado = array();
for ( $i=$snpInicio; $i<=$snpFin; $i++ ){
	array_push( $resultado, $snpJsonArrayDecode[$i] );
}

ob_start( "ob_gzhandler" );    
echo json_encode( $resultado );


?>
