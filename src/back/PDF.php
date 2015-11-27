<?php

//Se importa la libreria FPDF
require '../../lib/fpdf17/fpdf.php';


class PDF extends FPDF{

	//Se configura la cabecera del pdf
	function Header(){
		global $title;

		// Arial bold 15
		$this->SetFont( 'Arial', 'B', 10 );				//SetFont(Family, Style, Size)
		// Calculamos ancho y posición del título.
		$w = $this->GetStringWidth( $title )+6;
		$this->SetX( (210-$w)/2 );
		// Colores de los bordes, fondo y texto
		//$this->SetDrawColor( 0, 80, 180);
		//$this->SetFillColor( 230, 230, 0);
		$this->SetTextColor( 220, 50, 50);
		// Ancho del borde (1 mm)
		$this->SetLineWidth( 1 );
		//Se agrega el titulo al pdf mediante una celda
		// Cell( ancho_celda, alto_celda, texto_a_imprimir, borde, ln, alineacion, llenado )
		// 	borde: puede ser 0 (sin borde) o 1 (dibujar marco)
		// 	ln: Indica donde la posición actual debería ir antes de invocar. Puede ser 0 (a la derecha), 
		//      1 (al comienzo de la sgte linea), 2 (debajo)
		//  alineacion: L (izq), C (centro), R(derecha)
		//  llenado: true si el fondo de la celda es dibujado, false si es transparente
		$this->Cell( $w, 9, $title, 1, 1, 'C', true);
		// Salto de línea
		$this->Ln( 10 );
	}

	
	function Footer(){
		// Posición a 1,5 cm del final
		$this->SetY(-15);
		// Arial itálica 8
		$this->SetFont('Arial','I',8);
		// Color del texto en gris
		$this->SetTextColor(128);
		// Número de página
		$this->Cell(0,10,'Página '.$this->PageNo(),0,0,'C');
	}


	function ChapterBody($txt){
		// Leemos el fichero
		//$txt = file_get_contents($file);
		// Times 12
		$this->SetFont( 'Times', '', 8 );
		// Imprimimos el texto justificado
		$this->MultiCell( 0, 5, $txt);
		// Salto de línea
		$this->Ln();
		// Cita en itálica
		$this->SetFont( '', 'I' );
		$this->Cell( 0, 5, 'Archivo Generado por WebService - Santiago Céspedes Zapata');
	}


	function PrintBody( $txt ){
		$this->AddPage();
		//$this->ChapterTitle( $num, $title );
		$this->ChapterBody( $txt );
	}
}



?>