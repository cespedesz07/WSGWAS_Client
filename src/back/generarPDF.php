<?php

require 'PDF.php';

//Ingresa un array de tipo:
//Array ( [Tipo] => FrecuenciasAlelicasCasos [Total] => Array ( [0] => 1 [1] => 1 ) [Alelo] => Array ( [0] => A [1] => G ) [Frec] => Array ( [0] => 0.5 [1] => 0.5 ) )
function obtenerValoresCalculo( $arregloCalculo ){
	$valoresCalculo = "";
	$valoresCalculo = $valoresCalculo . $arregloCalculo["Tipo"] . "\n";
	$claves = array_keys( $arregloCalculo );
	//print_r( $claves );
	//echo "<br><br>";
	//Se elimina "Tipo" del arreglo de claves, porque yá fue utilizado anteriormente
	unset( $claves[  array_search("Tipo", $claves)  ] );
	//print_r( $claves );
	//echo "<br><br>";
	foreach ( $claves as $clave ){
		$valoresCalculo = $valoresCalculo . $clave . "                                      ";
	}
	$valoresCalculo = $valoresCalculo . "\n";
	//echo "    $valoresCalculo <br> <br>";
	for ( $i=0; $i<count( $arregloCalculo[ $claves[1] ] ); $i++ ){
		foreach ( $claves as $clave ){
			$valoresCalculo = $valoresCalculo . $arregloCalculo[ $clave ][ $i ] . "                                          ";
		}
		$valoresCalculo = $valoresCalculo . "\n";

	}	
	//print_r( $valoresCalculo );
	return $valoresCalculo;
}



function formatearSnpJsonArray( $snpJsonArray ){
	$formatoSnpJsonArray = "";
	$snpJsonArrayDecode = json_decode($snpJsonArray);

	for ( $i=0; $i<count( $snpJsonArrayDecode ); $i++ ){
		$snpJsonObject = $snpJsonArrayDecode[ $i ];
		$formatoSnpJsonArray = $formatoSnpJsonArray . "------------ " . "SNP".$i . " ------------" . "\n";
		$calculosSnpJsonObject = get_object_vars( $snpJsonObject )["SNP".$i];
		for ( $j=0; $j<count( $calculosSnpJsonObject ); $j++ ){	
			$calculoRef = get_object_vars( $calculosSnpJsonObject[$j] );	
			$formatoSnpJsonArray = $formatoSnpJsonArray . obtenerValoresCalculo( $calculoRef ) . "\n";
		}

	}
	$formatoSnpJsonArray = $formatoSnpJsonArray . "\n\n";
	return $formatoSnpJsonArray;
}




//Se captura la ruta del archivo con los resultados del procesamiento (snpJsonArray.json)
//(esta ruta está almacenada en una cookie que fué almacenada en controldorPrincipal.php)
$rutaSnpJsonArray = $_COOKIE['rutaSnpJsonArray'];

//Se carga el snpJsonArray.json
$snpJsonArray = file_get_contents( $rutaSnpJsonArray );

$snpJsonArrayFormateado = formatearSnpJsonArray( $snpJsonArray );



$pdf = new PDF();
$title = 'Resultado del Procesamiento de SNPs';
$pdf->SetTitle( $title );
$pdf->SetAuthor( 'Santiago Céspedes Zapata' );
$pdf->PrintBody(  $snpJsonArrayFormateado  );
$pdf->Output( 'Result.pdf', 'I' );
exit();


?>