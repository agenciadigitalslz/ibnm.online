<?php 
require_once("../../conexao.php");

$id = $_GET['id'];


$token_rel = "M543661";
ob_start();
include("rel_carteirinha.php");
$html = ob_get_clean();

//CARREGAR DOMPDF
require_once '../dompdf/autoload.inc.php';
use Dompdf\Dompdf;
use Dompdf\Options;

header("Content-Transfer-Encoding: binary");
header("Content-Type: image/png");

//INICIALIZAR A CLASSE DO DOMPDF
$options = new Options();
$options->set('isRemoteEnabled', TRUE);
$pdf = new DOMPDF($options);


//Definir o tamanho do papel e orientação da página
//$pdf->set_paper(array(0, 0, 841.89, 595.28)); // tamanho folha A4 (Paisagem)
if($verso_carteirinha != 'Sim'){
$pdf->set_paper(array(0, 0, 255.11, 141.73)); // tamanho folha 9x5 cm (Paisagem)
}else{
$pdf->set_paper(array(0, 0, 255.11, 283.46)); 
}


//CARREGAR O CONTEÚDO HTML
$pdf->load_html($html);

//RENDERIZAR O PDF
$pdf->render();
//NOMEAR O PDF GERADO


$pdf->stream(
	'carteirinha.pdf',
	array("Attachment" => false)
);

 ?>