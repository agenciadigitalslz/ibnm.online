<?php 

include_once('../conexao.php');

$postjson = json_decode(file_get_contents('php://input'), true);

$buscar = '%' .@$_GET['buscar']. '%';
$id_igreja = @$_GET['igreja']; 

$query = $pdo->prepare("SELECT * from patrimonios where (nome LIKE '$buscar' or codigo LIKE '$buscar') and (igreja_cad = '$id_igreja' or igreja_item = '$id_igreja') order by id desc");

$query->execute();

$res = $query->fetchAll(PDO::FETCH_ASSOC);

for ($i=0; $i < count($res); $i++) { 
    foreach ($res[$i] as $key => $value) {  }

   $nome = $res[$i]['nome'];
    $codigo = $res[$i]['codigo'];
    $descricao = $res[$i]['descricao'];
    $valor = $res[$i]['valor'];
    $usuario_cad = $res[$i]['usuario_cad'];
    $foto = $res[$i]['foto'];
    $igreja_cad = $res[$i]['igreja_cad'];
    $data_cad = $res[$i]['data_cad'];
    $obs = $res[$i]['obs'];
    $igreja_item = $res[$i]['igreja_item'];
    $usuario_emprestou = $res[$i]['usuario_emprestou'];
    $data_emprestimo = $res[$i]['data_emprestimo'];
    $ativo = $res[$i]['ativo'];
    $entrada = $res[$i]['entrada'];
    $doador = $res[$i]['doador'];
    $id = $res[$i]['id'];



                if($igreja_cad == $id_igreja){
                        $ocultar_devolucao = 'd-none';
                        $ocultar_transferir = '';
                        if($igreja_item == $id_igreja){
                            $classe_item = '#000';  
                        }else{
                            $classe_item = 'red';
                        }
                    }else{
                        $ocultar_devolucao = '';
                        $ocultar_transferir = 'd-none';
                        $classe_item = 'blue';
                    }


                    if($obs != ""){
                        $classe_obs = 'text-warning';
                    }else{
                        $classe_obs = 'text-secondary';
                    }


                    if($ativo == 'Sim'){
                        $classe = 'text-success';
                        $ativo = 'Desativar Item';
                        $icone = 'bi-check-square';
                        $ativar = 'Não';
                        $inativa = '';
                        $tab = 'Ativo';

                    }else{
                        $classe = 'text-danger';
                        $ativo = 'Ativar Item';
                        $icone = 'bi-square';
                        $ativar = 'Sim';
                        $inativa = 'text-muted';
                        $tab = 'Inativo';
                    }

                    $query_con = $pdo->query("SELECT * FROM usuarios where id = '$usuario_cad'");
                    $res_con = $query_con->fetchAll(PDO::FETCH_ASSOC);
                    if(count($res_con) > 0){
                        $nome_usu_cad = $res_con[0]['nome'];
                    }else{
                        $nome_usu_cad = '';
                    }


                    $query_con = $pdo->query("SELECT * FROM usuarios where id = '$usuario_emprestou'");
                    $res_con = $query_con->fetchAll(PDO::FETCH_ASSOC);
                    if(count($res_con) > 0){
                        $nome_usu_emp = $res_con[0]['nome'];
                    }else{
                        $nome_usu_emp = 'Sem Empréstimo';
                    }


                    $query_con = $pdo->query("SELECT * FROM igrejas where id = '$igreja_cad'");
                    $res_con = $query_con->fetchAll(PDO::FETCH_ASSOC);
                    if(count($res_con) > 0){
                        $nome_ig_cad = $res_con[0]['nome'];
                    }else{
                        $nome_ig_cad = '';
                    }


                    $query_con = $pdo->query("SELECT * FROM igrejas where id = '$igreja_item'");
                    $res_con = $query_con->fetchAll(PDO::FETCH_ASSOC);
                    if(count($res_con) > 0){
                        $nome_ig_item = $res_con[0]['nome'];
                    }else{
                        $nome_ig_item = '';
                    }


                    $data_cadF = implode('/', array_reverse(explode('-', $data_cad)));
                    $data_emprestimoF = implode('/', array_reverse(explode('-', $data_emprestimo)));
                    $valorF = number_format($valor, 2, ',', '.');


    $dados[] = array(
        'id' => $id,
        'nome' => $res[$i]['nome'],
        'codigo' => $codigo,
        'nome_usu_cad' => $nome_usu_cad,
        'data_cadF' => $data_cadF,
        'nome_ig_cad' => $nome_ig_cad,
        'ig_cad' => $igreja_cad,
        'foto' => $foto,
        'nome_usu_emp' => $nome_usu_emp,
        'nome_ig_item' => $nome_ig_item,
        'data_emprestimoF' => $data_emprestimoF,
        'valorF' => $valorF,
        'ativo' => $res[$i]['ativo'],
        'obs' => $obs,
        'entrada' => $entrada,
        'doador' => $doador,
        'classe_item' => $classe_item,
        'statusF' => $tab,
        'descricao' => $descricao,
        
    );
}

if(count($res) > 0){
    $result = json_encode(array('success'=>true, 'lista'=>$dados));
}else{
    $result = json_encode(array('success'=>false, 'resultado'=>'0'));
}

echo $result;

?>