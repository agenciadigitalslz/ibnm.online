<?php 
require_once("../../../conexao.php");

$ultimo = @$_POST['ultimo'];

echo '<select class="sel2" name="cliente" id="cliente" style="width:100%; height:35px; ">';

$query = $pdo->query("SELECT * from clientes order by id desc");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$linhas = @count($res);
if($linhas > 0){
	for($i=0; $i<$linhas; $i++){
		
		echo '<option value="'.$res[$i]['id'].'">'.$res[$i]['nome'].'</option>';

	} } 

echo '</select>';
?>


	<script type="text/javascript">
		$(document).ready(function() {			
			$('.sel2').select2({
				dropdownParent: $('#modalForm')
			});
		});
	</script>