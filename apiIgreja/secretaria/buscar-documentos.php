<?php 

include_once('../conexao.php');

$postjson = json_decode(file_get_contents('php://input'), true);

$buscar = '%' .@$_GET['buscar']. '%';
$id_igreja = @$_GET['igreja']; 

$query = $pdo->prepare("SELECT * from documentos where nome LIKE '$buscar' and igreja = '$id_igreja' order by id desc");

$query->execute();

$res = $query->fetchAll(PDO::FETCH_ASSOC);

for ($i=0; $i < count($res); $i++) { 
    
                    $descricao = $res[$i]['descricao'];
                    $nome = $res[$i]['nome'];
                    $usuario = $res[$i]['usuario'];
                    $data = $res[$i]['data'];
                    $arquivo = $res[$i]['arquivo'];
                    $igreja = $res[$i]['igreja'];
                    
                    $id = $res[$i]['id'];


                    //EXTRAIR EXTENSÃO DO ARQUIVO
                    $ext = pathinfo($arquivo, PATHINFO_EXTENSION);   
                    if($ext == 'pdf'){ 
                        $tumb_arquivo = 'pdf.png';
                    }else if($ext == 'rar' || $ext == 'zip'){
                        $tumb_arquivo = 'rar.png';
                    }else if($ext == 'doc' || $ext == 'docx'){
                        $tumb_arquivo = 'word.png';
                    }else{
                        $tumb_arquivo = $arquivo;
                    }


                    $query_con = $pdo->query("SELECT * FROM usuarios where id = '$usuario'");
                    $res_con = $query_con->fetchAll(PDO::FETCH_ASSOC);
                    if(count($res_con) > 0){
                        $usuario_cad = $res_con[0]['nome'];

                    }else{
                        $usuario_cad = '';
                    }

                    $dataF = implode('/', array_reverse(explode('-', $data)));

    $dados[] = array(
        'id' => $res[$i]['id'],
        'nome' => $res[$i]['nome'],
        'usuario' => $usuario_cad,
        'data' => $dataF,
        'arquivo' => $arquivo,
        'descricao' => $descricao,
        'tumb' => $tumb_arquivo,
       
    );
}

if(count($res) > 0){
    $result = json_encode(array('success'=>true, 'clientes'=>$dados));
}else{
    $result = json_encode(array('success'=>false, 'resultado'=>'0'));
}

echo $result;

?>