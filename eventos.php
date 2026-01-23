<?php 
session_start();
require_once("sistema/conexao.php");

$url = @$_SESSION['url_igreja'];

if($url == ""){
  echo '<script>window.location="index.php"</script>';
}


$query = $pdo->query("SELECT * FROM igrejas where url = '$url'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
if(@count($res) > 0){

$nome_igreja = $res[0]['nome'];
$foto_igreja = $res[0]['imagem'];
$endereco_igreja = $res[0]['endereco'];
$telefone_igreja = $res[0]['telefone'];
$youtube = $res[0]['youtube'];
$instagram = $res[0]['instagram'];
$facebook = $res[0]['facebook'];
$id_igreja = $res[0]['id'];
$pastor_id = $res[0]['pastor'];
$video_igreja = $res[0]['video'];
$email_igreja = $res[0]['email'];
$descricao_igreja = $res[0]['descricao'];
$foto_painel = $res[0]['painel_jpg'];
$logo_rel = @$res[0]['logo_rel'];
$logo_igreja = @$res[0]['imagem'];

$telefone_igrejaF = '55'.preg_replace('/[ ()-]+/' , '' , $telefone_igreja);

$query = $pdo->query("SELECT * FROM pastores where id = '$pastor_id'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$pastor_resp = $res[0]['nome'];
}else{
  echo '<script>window.location="index.php"</script>';
}


$data_atual = date('Y-m-d');

$id_empresa = $id_igreja;
$empresa = $id_igreja;

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

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <meta name="keywords" content="erp, sistema de gestão, <?php echo $nome_igreja ?>, controle financeiro, gestão de estoque, vendas online" />
  <meta name="description" content="<?php echo $nome_igreja ?> - O ERP Completo para Alavancar Seu Negócio! Sistema 100% online, intuitivo e integrado." />
  <meta name="author" content="Agencia Digital SLZ" />
  <title><?php echo $nome_igreja ?></title>
  <!-- Favicon -->
  <link rel="icon" href="img/foto-painel.png" type="image/x-icon" />
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <link rel="stylesheet" type="text/css" href="css/produtos.css">
    <link rel="stylesheet" type="text/css" href="css/estilos_site.css">
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



      
      
   <?php 
   $query = $pdo->prepare("SELECT * FROM eventos where igreja = '$id_igreja' and ativo = 'Sim' order by id desc limit 8");

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
            loading="lazy" decoding="async"
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


   <!-- Botão de voltar para a Igreja -->
  <div class="text-center mt-10">
    <a href="igreja.php?nome=<?php echo $url ?>" class="inline-flex items-center justify-center px-8 py-3 border border-white/30 text-base font-medium rounded-xl shadow-lg text-white hover:bg-white/10 focus:outline-none transition-all duration-300">
      <i class="fas fa-arrow-left mr-2"></i> Voltar para a Igreja
    </a>
  </div>



      <!-- Footer -->
      <footer class="py-4 md:py-6 border-t border-gray-700 gsap-fade-up">
        <div class="container mx-auto flex flex-col md:flex-row items-center justify-center md:justify-between px-4">
          <!-- Logo Grande no Footer - ajustado para ser menor em dispositivos móveis -->
          <div class="flex justify-center mb-4 md:mb-6">
            <img src="<?php echo $url_sistema ?>img/logos/<?php echo $logo ?>" alt="Logo" class="large-logo-footer h-16 md:h-20" />
          </div>
          <div class="text-center md:text-right">
            <p class="text-gray-400 text-sm">&copy; <?php echo date('Y'); ?> <?php echo $nome_igreja ?></p>
            <p class="text-gray-500 text-xs md:text-sm mt-1 md:mt-2"><?php echo $telefone_igreja ?> – <?php echo $endereco_igreja ?></p>
          </div>
        </div>
      </footer>
    </div>
  </div>



 <a title="Ir para o whatsapp" target="_blank" href="http://api.whatsapp.com/send?1=pt_BR&phone=<?php echo @$telefone_igrejaF ?>"><img src="<?php echo $url_sistema ?>img/logo_whats.png" width="50px" class="whats-fixed"></a>


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
              <div class="responsive-embed">
                <iframe id="video_dados" class="rounded-xl shadow-lg" src="" title="Vídeo do Evento" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
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


 