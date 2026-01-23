<?php 
@session_start();
require_once("../../conexao.php");

$id_igreja = @$_SESSION['id_igreja'];

$dataInicial = $_POST['dataInicial'];
$dataFinal = $_POST['dataFinal'];
$pago = $_POST['pago'];
$tipo_data = $_POST['tipo_data'];
$filtro_usuario_lanc = isset($_POST['usuario_lanc']) ? preg_replace('/\D/','', $_POST['usuario_lanc']) : '';

$token_rel = "A5030";
ob_start();
include("pagar.php");
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
$pdf->set_paper('A4', 'portrait');

//CARREGAR O CONTEÚDO HTML
$pdf->load_html($html);

//RENDERIZAR O PDF
$pdf->render();
//NOMEAR O PDF GERADO


$pdf->stream(
	'contas_pagar.pdf',
	array("Attachment" => false)
);

 ?>