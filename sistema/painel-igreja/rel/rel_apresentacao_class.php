<?php 
require_once("../../conexao.php");

$id = $_GET['id'];
$sexo = $_GET['sexo'];


$token_rel = "M543661";
ob_start();
include("rel_apresentacao.php");
$html = ob_get_clean();


if($relatorio_pdf != 'Sim'){
	echo $html;
	exit();
}


//CARREGAR DOMPDF
require_once '../dompdf/autoload.inc.php';
use Dompdf\Dompdf;
use Dompdf\Options;

header("Content-Transfer-Encoding: binary");
header("Content-Type: image/png");

//INICIALIZAR A CLASSE DO DOMPDF
$options = new Options();
$options->set('isRemoteEnabled', true);
$pdf = new DOMPDF($options);


//Definir o tamanho do papel e orientação da página
//$pdf->set_paper(array(0, 0, 841.89, 595.28)); // tamanho folha A4 (Paisagem)
$pdf->set_paper('A4', 'landscape');


//CARREGAR O CONTEÚDO HTML
$pdf->load_html($html);

//RENDERIZAR O PDF
$pdf->render();

//NOMEAR O PDF GERADO
$pdf->stream(
'apresentacao.pdf',
array("Attachment" => false)
);

 ?>