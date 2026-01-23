<?php 
session_start();
require_once("sistema/conexao.php");

// Redirecionar quem acessar a raiz para /sede
header("Location: " . $url_sistema_site . "sede");
exit();

$data_atual = date('Y-m-d');

$id_empresa = 0;
$empresa = 0;

//buscar as informações
$query = $pdo->query("SELECT * FROM site where empresa = '$id_empresa'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$logo = @$res[0]['logo'];
$titulo = @$res[0]['titulo'];
$subtitulo = @$res[0]['subtitulo'];
$botao1 = @$res[0]['botao1'];
$botao2 = @$res[0]['botao2'];
$botao3 = @$res[0]['botao3'];
$item1 = @$res[0]['item1'];
$item2 = @$res[0]['item2'];
$item3 = @$res[0]['item3'];
$titulo_recursos = @$res[0]['titulo_recursos'];
$titulo_perguntas = @$res[0]['titulo_perguntas'];
$titulo_rodape = @$res[0]['titulo_rodape'];
$link_rodape = @$res[0]['link_rodape'];
$descricao_rodape = @$res[0]['descricao_rodape'];
$botao_rodape = @$res[0]['botao_rodape'];
$logo_topo = @$res[0]['logo_topo'];
$fundo_topo = @$res[0]['fundo_topo'];
$fundo_topo_mobile = @$res[0]['fundo_topo_mobile'];

$bg_desktop = "";
$bg_mobile = "";

if($fundo_topo != "sem-foto.png"){
  $bg_desktop = $url_sistema . "img/logos/" . $fundo_topo;
}

if($fundo_topo_mobile != "sem-foto.png"){
  $bg_mobile = $url_sistema . "img/logos/" . $fundo_topo_mobile;
}


if($logo == ""){
  $logo = 'sem-foto.png';
}

// Separando o título em palavras
$palavras = @explode(' ', $titulo);
// Pegando as últimas duas palavras
$ultimo_palavra = array_pop($palavras);
$penultima_palavra = array_pop($palavras);

// Recriar o título com as últimas duas palavras com a classe 'text-accent'
$titulo_modificado = implode(' ', $palavras) . ' <span class="text-accent">' . $penultima_palavra . ' ' . $ultimo_palavra . '</span>';


if (@strpos($subtitulo, $nome_sistema) !== false) {
    // Se o texto foi encontrado, colocamos ele dentro da tag <strong>
  $subtitulo_modificado = @str_ireplace($nome_sistema, "<strong>$nome_sistema</strong>", $subtitulo);
} else {
    // Caso não tenha o texto, usamos o título original
  $subtitulo_modificado = $subtitulo;
}


if (@strpos($descricao_rodape, $nome_sistema) !== false) {
    // Se o texto foi encontrado, colocamos ele dentro da tag <strong>
  $descricao_rodape_modificado = @str_ireplace($nome_sistema, "<strong class='text-white'>$nome_sistema</strong>", $descricao_rodape);
} else {
    // Caso não tenha o texto, usamos o título original
  $descricao_rodape_modificado = $descricao_rodape;
}

if($id_empresa == 0){
  $link_painel = 'login';
}else{
  $link_painel = '../acesso';
}

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <meta name="keywords" content="erp, sistema de gestão, <?php echo $nome_sistema ?>, controle financeiro, gestão de estoque, vendas online" />
  <meta name="description" content="<?php echo $nome_sistema ?> - Sistema para Gestão de Igrejas" />
  <meta name="author" content="Agencia Digital SLZ" />
  <title><?php echo $nome_sistema ?></title>
  <!-- Favicon -->
  <link rel="icon" href="<?php echo $url_sistema ?>img/icone.png" type="image/x-icon" />
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  
  <link rel="stylesheet" type="text/css" href="css/estilos_site.css">
  <link rel="stylesheet" type="text/css" href="css/produtos.css">
  <!-- GSAP -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            primary: {
              DEFAULT: '#2d4c63',
              dark: '#1a2c3d',
              light: '#3a6080'
            },
            accent: {
              DEFAULT: '#ffc107',
              dark: '#e0a800',
              light: '#ffcd38'
            },
            success: '#10b981',
            warning: '#f59e0b',
            danger: '#ef4444',
            customAzul: '#045d8a',
            customGreen: '#2ba304', // Adicionando a cor personalizada
          },
          fontFamily: {
            poppins: ['Poppins', 'sans-serif'],
          },
        }
      }
    }
  </script>

  <style>
    @media (max-width: 768px) {
      .fundo-topo {
        background-image: url('<?php echo $bg_mobile ?>') !important;
      }
    }
  </style>

  
</head>
<body class="font-poppins bg-gradient-to-br from-primary-dark to-primary text-white min-h-screen overflow-x-hidden">
  <div class="py-12 px-4 sm:px-6 lg:px-8">
    <div class="container mx-auto">

      <!-- Bloco CTA Simplificado -->
      <div class="mb-12 bg-primary-dark p-8 rounded-xl border border-accent/20 shadow-lg fundo-topo"
      style="background-image: url('<?php echo $bg_desktop ?>'); background-size: cover; background-position: center;">
      <div class="text-center mb-6">
        <!-- Logo adicionada acima do título -->
        <div class="flex justify-center mb-6">
          <?php if($logo_topo != 'Não'){ ?>
            <img  src="<?php echo $url_sistema ?>img/logos/<?php echo $logo ?>" alt="Logo" class="large-logo" />
          <?php }else{ echo '<br>&nbsp;<br><br>&nbsp;<br><br>&nbsp;<br>'; } ?>
        </div>

        <?php if($titulo != ""){ ?>
          <h1 class="text-3xl md:text-4xl font-bold mb-4">
            <?php echo $titulo_modificado ?>
          </h1>
        <?php } ?>

        <?php if($subtitulo != ""){ ?>
          <p class="text-gray-300 text-lg max-w-3xl mx-auto">
            <strong class="text-white"></strong> <?php echo $subtitulo_modificado ?>
          </p>
        <?php } ?>

      </div>

      <?php if($botao1 != "" || $botao2 != "" || $botao3 != ""){ ?>
        <div class="flex flex-col sm:flex-row justify-center gap-4 mb-6">
          <?php if($botao1 != ""){ ?>
            <a href="#area_igreja" class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-lg shadow-lg text-white bg-customGreen hover:bg-green-700 focus:outline-none animate-pulse-green transition-all duration-300">
              <i class="fas fa-rocket mr-2"></i> <?php echo $botao1 ?>
            </a>
          <?php } ?>
          <?php if($botao2 != ""){ ?>
            <a href="#faq-eventos" class="inline-flex items-center justify-center px-6 py-3 border border-white/20 text-base font-medium rounded-lg shadow-lg text-white hover:bg-white/10 focus:outline-none transition-all duration-300">
              <i class="fas fa-info-circle mr-2"></i> <?php echo $botao2 ?>
            </a>
          <?php } ?>
          <?php if($botao3 != ""){ ?>
          <a href="<?php echo $url_sistema ?>index.php" class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-lg shadow-lg text-white bg-customAzul hover:bg-blue-700 focus:outline-none animate-pulse-blue transition-all duration-300">
            <i class="fas fa-key mr-2"></i> <?php echo $botao3 ?>
          </a>
        <?php } ?>
      </div>
    <?php } ?>

    <?php if($item1 != "" || $item2 != "" || $item3 != ""){ ?>
      <div class="flex justify-center flex-wrap gap-4 text-sm text-gray-300">
        <div class="flex items-center">
          <i class="fas fa-check-circle text-accent mr-2"></i> <?php echo $item1 ?>
        </div>
        <div class="flex items-center">
          <i class="fas fa-check-circle text-accent mr-2"></i> <?php echo $item2 ?>
        </div>
        <div class="flex items-center">
          <i class="fas fa-check-circle text-accent mr-2"></i> <?php echo $item3 ?>
        </div>
      </div>
    <?php } ?>

    <br>&nbsp;<br>

  </div>




  <?php 
  $query = $pdo->prepare("SELECT * FROM igrejas order by id asc limit 10");

  $query->execute();
  $res = $query->fetchAll(PDO::FETCH_ASSOC);
  $linhas = @count($res);
  if($linhas > 0){
   ?>


 



    <div class="mb-12 md:mb-20" id="area_igreja">

      <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-2">
        <?php 
        for($i=0; $i<$linhas; $i++){
          $nome = @$res[$i]['nome'];
          $url = @$res[$i]['url'];
          $endereco = @$res[$i]['endereco'];
          $imagem = @$res[$i]['imagem'];
          ?>
          <div class="rounded-xl border border-gray-400 bg-white/20 shadow-sm p-3 transition-all duration-300 hover:shadow-xl hover:-translate-y-1 hover:border-gray-500">
            <img
            src="sistema/img/igrejas/<?php echo $imagem ?>"
            alt="Imagem da Igreja"
            class="w-full h-28 object-cover rounded-md mb-3">

            <div class="mb-4">
              <!-- Nome centralizado, menor, em Montserrat -->
              <h3
              class="text-center text-sm font-light uppercase tracking-wide text-white mb-1"
              style="font-family: 'Montserrat', sans-serif;">
              <?php echo $nome ?>
            </h3>
            <!-- Decorativo simples -->
            <div class="w-8 h-[1px] bg-gray-300 mx-auto mb-2"></div>
            <!-- Endereço discreto -->
            <p class="text-center text-[10px] font-normal text-gray-200 truncate">
              <?php echo $endereco ?>
            </p>
          </div>

          <a
          href="<?php echo $url ?>"
          target="_blank"
          class="block w-full text-center text-sm font-medium px-4 py-2 bg-gray-700 text-white rounded-lg hover:bg-gray-800 transition">
          Visitar Site
        </a>
      </div>

    <?php } ?>
  </div>


  <!-- Botão "Ver Mais Produtos" -->
  <div class="text-center mt-10">
    <a href="igrejas.php" class="inline-flex items-center justify-center px-8 py-3 border border-accent/30 text-base font-medium rounded-xl shadow-lg text-white hover:bg-accent hover:text-primary-dark focus:outline-none transition-all duration-300 group">
      <i class="fas fa-th-large mr-2 group-hover:animate-pulse"></i> Ver Todos as Igrejas
      <i class="fas fa-arrow-right ml-2 opacity-0 group-hover:opacity-100 group-hover:translate-x-2 transition-all duration-300"></i>
    </a>
  </div>
</div>


<?php } ?>






   <?php 
   $query = $pdo->prepare("SELECT * FROM eventos order by id desc limit 10");

   $query->execute();
   $res = $query->fetchAll(PDO::FETCH_ASSOC);
   $linhas = @count($res);
   if($linhas > 0){
    ?>

       <!-- Carrossel de Banners -->
<div id="carousel" class="relative w-full h-64 sm:h-96 overflow-hidden rounded-lg ocultar_web">
  <!-- Slides -->
  <div id="carousel-inner" class="flex h-full transition-transform duration-700 ease-out">
    <?php 
    for($i=0; $i<$linhas; $i++){
     $subtitulo = $res[$i]['subtitulo'];
          $descricao1 = $res[$i]['descricao1'];
          $descricao2 = $res[$i]['descricao2'];
          $descricao3 = $res[$i]['descricao3'];
          $data_cad = $res[$i]['data_cad'];
          $data_evento = $res[$i]['data_evento'];
          $usuario = $res[$i]['usuario'];
          $imagem = $res[$i]['imagem'];
          $video = $res[$i]['video'];
          $ativo = $res[$i]['ativo'];
          $igreja = $res[$i]['igreja'];
          $obs = $res[$i]['obs'];
          $id = $res[$i]['id'];
          $banner = $res[$i]['banner'];
          $tipo = $res[$i]['tipo'];
          $pregador = $res[$i]['pregador'];
          $data_cadF = implode('/', array_reverse(explode('-', $data_cad)));
      $data_eventoF = implode('/', array_reverse(explode('-', $data_evento)));


      $query_usu = $pdo->query("SELECT * FROM igrejas where id = '$igreja'");
          $res_usu = $query_usu->fetchAll(PDO::FETCH_ASSOC);
          if(count($res_usu) > 0){
            $nome_igreja = $res_usu[0]['nome'];
            $endereco_igreja = $res_usu[0]['endereco'];
          }else{
            $nome_igreja = '';
            $endereco_igreja = '';
          }

          $caminho_img = $url_sistema.'img/eventos/'.$imagem;
      ?>
    
    <!-- Substitua as URLs abaixo pelas suas -->
    <img style="cursor: pointer;" onclick="mostrar('<?php echo $titulo ?>','<?php echo $subtitulo ?>','<?php echo $data_cadF ?>','<?php echo $data_eventoF ?>','<?php echo $tipo ?>','<?php echo $obs ?>','<?php echo $descricao1 ?>','<?php echo $descricao2 ?>','<?php echo $descricao3 ?>','<?php echo $caminho_img ?>','<?php echo $video ?>','<?php echo $nome_igreja ?>','<?php echo $endereco_igreja ?>','<?php echo $pregador ?>')" src="<?php echo $url_sistema ?>/img/eventos/<?php echo $banner ?>" class="w-full flex-shrink-0 object-cover" alt="Banner 1" loading="lazy" decoding="async">
        
    <?php }  ?>

    <!-- …coloque quantos banners precisar -->
  </div>

  <!-- Controles -->
  <button id="prevSlide"
    class="absolute left-2 top-1/2 transform -translate-y-1/2 bg-white/50 hover:bg-white text-gray-800 rounded-full p-2">
    <i class="fa fa-chevron-left"></i>
  </button>
  <button id="nextSlide"
    class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-white/50 hover:bg-white text-gray-800 rounded-full p-2">
    <i class="fa fa-chevron-right"></i>
  </button>

  <!-- Bullets (dinâmicos) -->
  <div id="carousel-bullets" class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex space-x-2"></div>
</div>
<?php } ?>



<br>

   <?php 
   $query = $pdo->prepare("SELECT * FROM eventos where ativo = 'Sim' order by id desc limit 4");

   $query->execute();
   $res = $query->fetchAll(PDO::FETCH_ASSOC);
   $linhas = @count($res);
   if($linhas > 0){
    ?>

    <div class="container mx-auto p-4" id="faq-eventos">
  <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">

     <?php 
    for($i=0; $i<$linhas; $i++){
      $titulo = $res[$i]['titulo'];
          $subtitulo = $res[$i]['subtitulo'];
          $descricao1 = $res[$i]['descricao1'];
          $descricao2 = $res[$i]['descricao2'];
          $descricao3 = $res[$i]['descricao3'];
          $data_cad = $res[$i]['data_cad'];
          $data_evento = $res[$i]['data_evento'];
          $usuario = $res[$i]['usuario'];
          $imagem = $res[$i]['imagem'];
          $video = $res[$i]['video'];
          $ativo = $res[$i]['ativo'];
          $igreja = $res[$i]['igreja'];
          $obs = $res[$i]['obs'];
          $id = $res[$i]['id'];
          $banner = $res[$i]['banner'];
          $tipo = $res[$i]['tipo'];
          $pregador = $res[$i]['pregador'];
          $data_cadF = implode('/', array_reverse(explode('-', $data_cad)));
      $data_eventoF = implode('/', array_reverse(explode('-', $data_evento)));


      $query_usu = $pdo->query("SELECT * FROM igrejas where id = '$igreja'");
          $res_usu = $query_usu->fetchAll(PDO::FETCH_ASSOC);
          if(count($res_usu) > 0){
            $nome_igreja = $res_usu[0]['nome'];
            $endereco_igreja = $res_usu[0]['endereco'];
          }else{
            $nome_igreja = '';
            $endereco_igreja = '';
          }

          $caminho_img = $url_sistema.'img/eventos/'.$imagem;


      ?>



    <!-- Card de produto individual -->
    <div class="product-card" onclick="mostrar('<?php echo $titulo ?>','<?php echo $subtitulo ?>','<?php echo $data_cadF ?>','<?php echo $data_eventoF ?>','<?php echo $tipo ?>','<?php echo $obs ?>','<?php echo $descricao1 ?>','<?php echo $descricao2 ?>','<?php echo $descricao3 ?>','<?php echo $caminho_img ?>','<?php echo $video ?>','<?php echo $nome_igreja ?>','<?php echo $endereco_igreja ?>','<?php echo $pregador ?>')"  style="cursor:pointer">
      <div class="bg-glass border border-gray-700 rounded-2xl overflow-hidden h-full flex flex-col shadow-[0_10px_25px_rgba(0,0,0,0.3)] transition-all duration-500 hover:-translate-y-2 hover:shadow-[0_20px_35px_rgba(0,0,0,0.5)] hover:border-accent group">
        <!-- Badge de status -->
        <div class="absolute top-4 left-4 z-10 bg-customGreen/90 text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-lg backdrop-blur-sm">
          <i class="fas fa-check-circle mr-1"></i> <?php echo $tipo ?>
        </div>
        
        <!-- Badge de vendidos -->
        <div class="absolute top-4 right-4 z-10 bg-accent/90 text-primary-dark text-xs font-bold px-3 py-1.5 rounded-full shadow-lg backdrop-blur-sm">
          <i class="fas fa-fire mr-1"></i> <?php echo $data_eventoF ?>
        </div>
        
        <!-- Imagem do produto com efeito de zoom -->
        <div class="relative h-56 overflow-hidden">
          <div class="absolute inset-0 bg-gradient-to-b from-black/30 to-transparent z-10"></div>
          <img 
            src="<?php echo $url_sistema ?>/img/eventos/<?php echo $imagem ?>"
            alt="Smartphone Galaxy A54" 
            class="w-full h-full object-cover transition-transform duration-700 ease-in-out group-hover:scale-110 group-hover:rotate-1"
            
          />
          
          <!-- Overlay com botões de ação -->
          <div class="absolute inset-0 bg-gradient-to-t from-primary-dark/90 via-primary/70 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end justify-center pb-6 z-20">
            <div class="flex gap-3">
              <button class="bg-white text-primary-dark font-semibold px-4 py-2 rounded-full transform translate-y-10 group-hover:translate-y-0 transition-transform duration-500 hover:bg-accent flex items-center">
                <i class="fas fa-eye mr-2"></i> Mais Informações
              </button>
            
            </div>
          </div>
        </div>
        
        <!-- Conteúdo do produto -->
        <div class="p-6 flex-grow flex flex-col">
          <!-- Título e avaliação -->
          <div class="flex justify-between items-start mb-2">
            <h3 class="text-lg font-bold text-white leading-tight"><?php echo $titulo ?></h3>
           
          </div>
          
          <!-- Descrição -->
          <p class="text-gray-300 text-sm mb-4 line-clamp-2"><?php echo $subtitulo ?></p>
          <span class="text-accent  text-1xl"><?php echo $nome_igreja ?></span>
          
         
        </div>
      </div>
    </div>

    
   
        
    <?php }  ?>

      </div>
    </div>

<?php } ?>




<br>



<br>
<?php 
$query = $pdo->query("SELECT * FROM recursos_site where empresa = '$id_empresa' order by posicao_recurso asc");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);
if($total_reg > 0) {
 ?>

 <!-- Feature Cards Section -->
  <div class="mb-10 md:mb-20" style="background: transparent;">
  <?php if($titulo_recursos != ""){ ?>
    <h2 class="text-3xl font-bold text-center mb-6 md:mb-10 gsap-fade-up"><?php echo $titulo_recursos ?></h2>
  <?php } ?>
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 lg:gap-8" id="features-container">

    <?php 
    for ($i = 0; $i < $total_reg; $i++) {
      $id = $res[$i]['id'];
      $titulo = $res[$i]['titulo_recurso'];
      $icone = $res[$i]['icone_recurso'];
      $descricao = $res[$i]['descricao_recurso'];
      $posicao = $res[$i]['posicao_recurso'];     

      ?>
      <div class="bg-glass rounded-xl p-6 text-center feature-card gsap-stagger-item">
        <div class="text-4xl mb-4 custom-green-icon" style="color: #2ba304;">
          <i class="<?php echo $icone ?>"></i>
        </div>
        <h3 class="text-xl font-semibold mb-3"><?php echo $titulo ?></h3>
        <p class="text-gray-300"><?php echo $descricao ?></p>
      </div>
    <?php } ?>


  </div>
</div>

<?php } ?>




<?php 
$query = $pdo->query("SELECT * FROM perguntas_site where empresa = '$id_empresa' order by posicao_pergunta asc");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);
if($total_reg > 0) {
 ?>

 <!-- FAQ Section -->
  <div class="mb-12 md:mb-20" style="background: #0e1e4a; padding:15px;">
  <?php if($titulo_perguntas != ""){ ?>
    <h2 class="text-2xl md:text-3xl font-bold text-center mb-6 md:mb-10 gsap-fade-up"><?php echo $titulo_perguntas ?></h2>
  <?php } ?>
  <div class="max-w-3xl mx-auto space-y-3 md:space-y-4 px-4" id="faq-container">

   <?php 
   for ($i = 0; $i < $total_reg; $i++) {
    $id = $res[$i]['id'];
    $titulo = $res[$i]['titulo_pergunta'];              
    $descricao = $res[$i]['descricao_pergunta'];
    $posicao = $res[$i]['posicao_pergunta'];              

    if (@strpos($descricao, $nome_sistema) !== false) {
                // Se o texto foi encontrado, colocamos ele dentro da tag <strong>
      $descricao_modificado = @str_ireplace($nome_sistema, "<strong>$nome_sistema</strong>", $descricao);
    } else {
                // Caso não tenha o texto, usamos o título original
      $descricao_modificado = $descricao;
    }  

    ?>

    <div class="bg-glass border border-gray-700 rounded-xl overflow-hidden gsap-stagger-item faq-item">
      <button class="w-full flex justify-between items-center p-4 text-left font-semibold focus:outline-none" 
      onclick="toggleFaq('faq<?php echo $id ?>')">
      <?php echo $titulo ?>
      <i class="fas fa-chevron-down transition-transform duration-300" id="icon-faq1"></i>
    </button>
    <div class="hidden p-4 bg-gray-900 bg-opacity-50 border-t border-gray-700" id="faq<?php echo $id ?>">
     <?php echo $descricao_modificado ?>
   </div>
 </div>

<?php } ?>


</div>
</div>

<?php } ?>


<?php if($titulo_rodape != "" or $descricao_rodape != "" or $botao_rodape != ""){ ?>
<!-- CTA Final após FAQ -->
<div class="mb-12 md:mb-20 bg-glass rounded-xl p-8 border border-accent/20 shadow-lg text-center" style="background:#010a1a">
  <?php if($titulo_rodape != ""){ ?>
    <h2 class="text-2xl md:text-3xl font-bold mb-4"><?php echo $titulo_rodape ?></h2>
  <?php } ?>

  <?php if($descricao_rodape_modificado != ""){ ?>
    <p class="text-gray-300 text-lg max-w-3xl mx-auto mb-8">
      <?php echo $descricao_rodape_modificado ?>
    </p>
  <?php } ?>

  <?php if($botao_rodape != ""){ ?>
    <div class="flex justify-center">
      <a href="<?php echo $link_rodape ?>" class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-lg shadow-lg text-white bg-customGreen hover:bg-green-700 focus:outline-none animate-pulse-green transition-all duration-300 transform hover:-translate-y-1">
        <i class="fas fa-rocket mr-2"></i> <?php echo $botao_rodape ?>
      </a>
    </div>
  <?php } ?>

</div>
<?php } ?>


<!-- Footer -->
<footer class="py-4 md:py-6 border-t border-gray-700 gsap-fade-up">
  <div class="container mx-auto flex flex-col md:flex-row items-center justify-center md:justify-between px-4">
    <!-- Logo Grande no Footer - ajustado para ser menor em dispositivos móveis -->
    <div class="flex justify-center mb-4 md:mb-6">
      <img src="<?php echo $url_sistema ?>img/logos/<?php echo $logo ?>" alt="Logo" class="large-logo-footer h-16 md:h-20" />
    </div>
    <div class="text-center md:text-right">
      <p class="text-gray-400 text-sm">&copy; <?php echo date('Y'); ?> <?php echo $nome_sistema ?></p>
      <p class="text-gray-500 text-xs md:text-sm mt-1 md:mt-2"><?php echo $telefone_sistema ?> – <?php echo $endereco_sistema ?></p>
    </div>
  </div>
</footer>
</div>
</div>



<a title="Ir para o whatsapp" target="_blank" href="http://api.whatsapp.com/send?1=pt_BR&phone=<?php echo @$tel_whats ?>"><img src="<?php echo $url_sistema ?>img/logo_whats.png" width="50px" class="whats-fixed"></a>


<script src="assets/js/lgpd-cookie.js" type="module"></script>
<Lgpd-cookie text='Não capturamos nenhuma informação de dados sensíveis, somente cookies para melhor performance de nosso website!' />

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- JavaScript for FAQ Accordion and Responsive Utilities -->
<script>
    // FAQ Accordion
    function toggleFaq(id) {
      const content = document.getElementById(id);
      const icon = document.getElementById('icon-' + id);
      if (content.classList.contains('hidden')) {
        content.classList.remove('hidden');
        icon.classList.add('rotate-180');
      } else {
        content.classList.add('hidden');
        icon.classList.remove('rotate-180');
      }
    }
    // Utilitários responsivos
    function adjustForScreenSize() {
      // Migração para CSS responsivo; função mantida para compatibilidade mas sem manipulação de estilos via JS
      return;
    }
    // Melhorar o scroll em dispositivos móveis
    function smoothScrollToElement(elementId) {
      const element = document.getElementById(elementId);
      if (element) {
        const headerOffset = 60;
        const elementPosition = element.getBoundingClientRect().top;
        const offsetPosition = elementPosition + window.pageYOffset - headerOffset;
        window.scrollTo({
          top: offsetPosition,
          behavior: 'smooth'
        });
      }
    }
    // GSAP Animations
    document.addEventListener('DOMContentLoaded', function() {
      // Executar ajustes responsivos
      adjustForScreenSize();
      // Adicionar listener para redimensionamento
      window.addEventListener('resize', adjustForScreenSize);
      // Respeitar prefers-reduced-motion
      const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
      if (reduceMotion) {
        gsap.globalTimeline.timeScale(0.01);
      }

      // Substituir links de âncora por scroll suave
      const anchorLinks = document.querySelectorAll('a[href^="#"]');
      anchorLinks.forEach(link => {
        link.addEventListener('click', function(e) {
          e.preventDefault();
          const targetId = this.getAttribute('href').substring(1);
          smoothScrollToElement(targetId);
        });
      });
      // Registrar o plugin ScrollTrigger
      gsap.registerPlugin(ScrollTrigger);

      const isDesktop = window.innerWidth >= 1024;

      if (!isDesktop && !reduceMotion) {
        // Apenas em mobile/tablet aplicamos as animações; no desktop renderiza direto
        // Animação dos títulos de seção
        gsap.utils.toArray(".gsap-fade-up").forEach(element => {
          gsap.to(element, {
            scrollTrigger: {
              trigger: element,
              start: "top 80%",
            },
            opacity: 1,
            y: 0,
            duration: 0.8,
            ease: "power2.out"
          });
        });
        // Animação dos cards de planos
        gsap.to(".plan-card", {
          scrollTrigger: {
            trigger: "#plans-container",
            start: "top 70%",
          },
          opacity: 1,
          y: 0,
          stagger: 0.1,
          duration: 0.6,
          ease: "back.out(1.2)"
        });
        // Animação dos preços
        gsap.from(".price-text", {
          scrollTrigger: {
            trigger: "#plans-container",
            start: "top 60%",
          },
          textContent: 0,
          duration: 1.5,
          ease: "power1.out",
          snap: { textContent: 1 },
          stagger: 0.1,
          delay: 0.5
        });
        // Animação dos itens de recursos
        gsap.from(".feature-item", {
          scrollTrigger: {
            trigger: "#plans-container",
            start: "top 50%",
          },
          opacity: 0,
          x: -20,
          stagger: 0.05,
          duration: 0.4,
          delay: 0.8
        });
        // Animação dos cards de recursos
        gsap.to(".feature-card", {
          scrollTrigger: {
            trigger: "#features-container",
            start: "top 70%",
          },
          opacity: 1,
          y: 0,
          stagger: 0.1,
          duration: 0.6,
          ease: "back.out(1.2)"
        });
        // Animação dos itens de FAQ
        gsap.to(".faq-item", {
          scrollTrigger: {
            trigger: "#faq-container",
            start: "top 80%",
          },
          opacity: 1,
          y: 0,
          stagger: 0.1,
          duration: 0.6
        });
      } else {
        // Desktop: garantir que todos os elementos sejam visíveis imediatamente
        document.addEventListener('DOMContentLoaded', function() {
          const elementsToShow = document.querySelectorAll('.gsap-fade-up, .feature-card, .faq-item, .gsap-stagger-item, .gsap-reveal, .gsap-fade-in, .gsap-scale-in');
          elementsToShow.forEach(el => {
            el.style.opacity = '1';
            el.style.transform = 'none';
            el.style.visibility = 'visible';
          });
          
          // Garantir visibilidade das seções principais
          const sections = document.querySelectorAll('#features-container, #faq-container, footer');
          sections.forEach(section => {
            if (section) {
              section.style.opacity = '1';
              section.style.visibility = 'visible';
            }
          });
        });
      }

      // Efeito de hover nos botões de assinatura
      const buttons = document.querySelectorAll('.btn-subscribe');
      buttons.forEach(button => {
        button.addEventListener('mouseenter', () => {
          gsap.to(button, {
            scale: 1.05,
            duration: 0.3,
            ease: "power1.out"
          });
        });
        button.addEventListener('mouseleave', () => {
          gsap.to(button, {
            scale: 1,
            duration: 0.3,
            ease: "power1.out"
          });
        });
      });
      // Efeito de parallax no fundo
      gsap.to("body", {
        backgroundPosition: "50% 100%",
        ease: "none",
        scrollTrigger: {
          trigger: "body",
          start: "top top",
          end: "bottom top",
          scrub: true
        }
      });
    });
  </script>
</body>
</html>





<script>
document.addEventListener('DOMContentLoaded', () => {
  const inner = document.getElementById('carousel-inner');
  const slides = inner.children;
  const total = slides.length;
  let idx = 0;
  const interval = 5000;
  let timer;

  function show(i) {
    inner.style.transform = `translateX(-${i * 100}%)`;
    bullets.forEach((b, j) => b.classList.toggle('bg-white', j === i));
  }

  function next() { idx = (idx + 1) % total; show(idx); }
  function prev() { idx = (idx - 1 + total) % total; show(idx); }

  // reset timer
  function reset() {
    clearInterval(timer);
    timer = setInterval(next, interval);
  }

  // botões
  document.getElementById('nextSlide').addEventListener('click', () => { next(); reset(); });
  document.getElementById('prevSlide').addEventListener('click', () => { prev(); reset(); });

  // bullets dinâmicos
  const bulletsContainer = document.getElementById('carousel-bullets');
  bulletsContainer.innerHTML = '';
  for (let i = 0; i < total; i++) {
    const b = document.createElement('button');
    b.className = 'carousel-bullet w-3 h-3 rounded-full bg-white/50 hover:bg-white';
    b.dataset.index = i.toString();
    b.addEventListener('click', () => { idx = i; show(idx); reset(); });
    bulletsContainer.appendChild(b);
  }
  const bullets = Array.from(document.querySelectorAll('.carousel-bullet'));

  // inicia
  show(0);
  timer = setInterval(next, interval);
});
</script>




<!-- Modal de Detalhes do Produto -->
  <div id="product-modal" class="modal-backdrop">
    <div class="modal-content">
      <!-- Cabeçalho do Modal -->
      <div class="modal-header">
        <h2 id="modal-product-title" class="text-xl md:text-2xl font-bold" ><span id="titulo_dados"></span></h2>
        
        <button id="modal-close" class="modal-close">
          <i class="fas fa-times"></i>
        </button>
      </div>
      
      <!-- Corpo do Modal -->
      <div class="modal-body">
        <!-- Imagem Principal e Galeria -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
          <div>
            <div class="rounded-xl overflow-hidden bg-gray-800 mb-3">
              <img id="modal-product-main-image" src="" alt="<?php echo $titulo ?>" class="w-full h-64 md:h-80 object-contain">
            </div>
            
            
          </div>
          
          <div>
            <!-- Informações Básicas -->
            <div class="mb-4">
              <div class="flex items-center mb-2">
                <div class="flex items-center bg-accent/20 rounded-full px-3 py-1.5">
                  
                  <span id="modal-product-rating" class="font-semibold">Data Evento: <span id="data_dados"></span></span>
                </div>
               
              </div>
              
              <div class="text-accent mt-3 mb-2">
                <span id=""><span id="igreja_dados"></span></span>
              </div>
              
              <div class="text-gray-300 mb-3">
               <span id="modal-product-installment" style="font-size: 12px"><span id="endereco_dados"></span> 
              </div>
              
              <!-- Estoque -->
              <div class="mb-4">
                <div class="flex items-center mb-2">
                  <i class="fas fa-calendar-alt text-accent mr-2"></i>
                  <span id="modal-product-stock-status"><span id="tipo_dados"></span></span>
                  <span class="mx-2">|</span>
                  <span id="modal-product-stock-qty"></span> <span id="pregador_dados"></span>
                </div>
                
                <div class="w-full bg-gray-700 rounded-full h-2">
                  <div id="modal-product-stock-bar" class="bg-customGreen h-2 rounded-full" style="width: 50%"></div>
                </div>
              </div>

              <span id="subtitulo_dados" style="font-size: 13px"></span>
              
              
            </div>
          </div>
        </div>
        
        <!-- Abas de Informações -->
        <div class="mb-4">
          <div class="modal-tabs">
            <div class="modal-tab active" data-tab="description">Descrição</div>
            <div class="modal-tab" data-tab="specifications">Vídeo</div>
            
          </div>
          
          <!-- Conteúdo da Aba de Descrição -->
          <div id="tab-description" class="modal-tab-content active">
            <p id="modal-product-description" class="text-gray-300">
              <span id="descricao1_dados"></span>
            </p><br>

            <p id="modal-product-description" class="text-gray-300">
              <span id="descricao2_dados"></span>
            </p><br>

            <p id="modal-product-description" class="text-gray-300">
              <span id="descricao3_dados"></span>
            </p>
          </div>
          
          <!-- Conteúdo da Aba de Especificações -->
         <div id="tab-specifications" class="modal-tab-content">
            <div id="modal-product-specifications" class="grid grid-cols-1 md:grid-cols-1 gap-4">
              <!-- Container para o vídeo com maior tamanho -->
              <div class="w-full h-96 md:h-[500px] relative">
                <iframe id="video_dados" class="w-full h-full rounded-xl shadow-lg absolute top-0 left-0" src="" title="Vídeo do Evento" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
              </div>   
            </div>
          </div>


          
          
        </div>
      </div>
      
     
    </div>
  </div>
  
  <!-- Script simples para demonstração -->
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Fazendo os botões de fechar o modal funcionarem
      const closeButtons = document.querySelectorAll('#modal-close, #modal-close-bottom');
      closeButtons.forEach(button => {
        button.addEventListener('click', function() {
          document.getElementById('product-modal').classList.remove('active');
        });
      });
      
      // Fazendo as miniaturas de imagem funcionarem
      const galleryItems = document.querySelectorAll('.modal-gallery-item');
      galleryItems.forEach(item => {
        item.addEventListener('click', function() {
          // Remover classe ativa de todos os itens
          galleryItems.forEach(i => i.classList.remove('active'));
          
          // Adicionar classe ativa ao item clicado
          this.classList.add('active');
          
          // Atualizar imagem principal
          const mainImage = document.getElementById('modal-product-main-image');
          mainImage.src = this.querySelector('img').src;
        });
      });
      
      // Fazendo as abas funcionarem
      const tabButtons = document.querySelectorAll('.modal-tab');
      tabButtons.forEach(tab => {
        tab.addEventListener('click', function() {
          // Remover classe ativa de todas as abas
          tabButtons.forEach(t => t.classList.remove('active'));
          
          // Adicionar classe ativa à aba clicada
          this.classList.add('active');
          
          // Obter o ID da tab
          const tabId = this.getAttribute('data-tab');
          
          // Esconder todos os conteúdos
          document.querySelectorAll('.modal-tab-content').forEach(content => {
            content.classList.remove('active');
          });
          
          // Mostrar o conteúdo da aba selecionada
          document.getElementById('tab-' + tabId).classList.add('active');
        });
      });
      
      // Controles de quantidade
      const decreaseBtn = document.getElementById('decrease-qty');
      const increaseBtn = document.getElementById('increase-qty');
      const qtyInput = document.getElementById('product-qty');
      
      decreaseBtn.addEventListener('click', function() {
        let currentQty = parseInt(qtyInput.value);
        if (currentQty > 1) {
          qtyInput.value = currentQty - 1;
        }
      });
      
      increaseBtn.addEventListener('click', function() {
        let currentQty = parseInt(qtyInput.value);
        qtyInput.value = currentQty + 1;
      });
      
      // Simular adição ao carrinho
      const addToCartBtns = document.querySelectorAll('#modal-add-to-cart, #modal-add-to-cart-bottom');
      addToCartBtns.forEach(btn => {
        btn.addEventListener('click', function() {
          alert('Produto adicionado ao carrinho: ' + qtyInput.value + 'x Smartphone Galaxy A54');
        });
      });
      
      // Simular compra rápida
      document.getElementById('modal-buy-now').addEventListener('click', function() {
        alert('Redirecionando para checkout...');
      });
    });
  </script>




  <script type="text/javascript">
    function mostrar(titulo, subtitulo, data_cadF, data_eventoF, tipo, obs, descricao1, descricao2, descricao3, imagem, video, nome_igreja, endereco_igreja, pregador){    
    
    
    document.getElementById('titulo_dados').innerText = titulo;
    document.getElementById('subtitulo_dados').innerText = subtitulo;
    document.getElementById('modal-product-main-image').src = imagem;
     document.getElementById('data_dados').innerText = data_eventoF;
      document.getElementById('igreja_dados').innerText = nome_igreja;
    document.getElementById('endereco_dados').innerText = endereco_igreja;
    document.getElementById('tipo_dados').innerText = tipo;
    document.getElementById('pregador_dados').innerText = pregador;
    document.getElementById('descricao1_dados').innerText = descricao1;
    document.getElementById('descricao2_dados').innerText = descricao2;
    document.getElementById('descricao3_dados').innerText = descricao3;
    document.getElementById('video_dados').src = video;


    document.getElementById('product-modal').classList.add('active')
  }
  </script>


 