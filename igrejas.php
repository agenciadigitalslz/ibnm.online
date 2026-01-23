<?php 
session_start();
require_once("sistema/conexao.php");


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
  <meta name="description" content="<?php echo $nome_sistema ?> - O ERP Completo para Alavancar Seu Negócio! Sistema 100% online, intuitivo e integrado." />
  <meta name="author" content="Agencia Digital SLZ" />
  <title><?php echo $nome_sistema ?></title>
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
                  $query = $pdo->prepare("SELECT * FROM igrejas order by id asc ");
                 
                  $query->execute();
                  $res = $query->fetchAll(PDO::FETCH_ASSOC);
                  $linhas = @count($res);
                  if($linhas > 0){

                    ?>

                     <div class="mb-12 md:mb-20">
      
        
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
          <a href="index.php" class="inline-flex items-center justify-center px-8 py-3 border border-accent/30 text-base font-medium rounded-xl shadow-lg text-white hover:bg-accent hover:text-primary-dark focus:outline-none transition-all duration-300 group">
            <i class="fas fa-th-large mr-2 group-hover:animate-pulse"></i> Voltar ao Site
            <i class="fas fa-arrow-right ml-2 opacity-0 group-hover:opacity-100 group-hover:translate-x-2 transition-all duration-300"></i>
          </a>
        </div>
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
            <p class="text-gray-500 text-xs md:text-sm mt-1 md:mt-2"><?php echo $telefone_igreja_sistema ?> – <?php echo $endereco_igreja_sistema ?></p>
          </div>
        </div>
      </footer>
    </div>
  </div>



 <a title="Ir para o whatsapp" target="_blank" href="http://api.whatsapp.com/send?1=pt_BR&phone=<?php echo @$tel_whats ?>"><img src="<?php echo $url_sistema ?>img/logo_whats.png" width="50px" style="position:fixed; bottom:20px; right:20px; z-index: 100"></a>


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

