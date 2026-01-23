<?php 
session_start();
require_once("sistema/conexao.php"); 
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <title>Cadastro de Membro</title>
  <meta name="description" content="Cadastro de membro - <?php echo $nome_sistema ?>" />
  <link rel="icon" href="<?php echo $url_sistema ?>img/icone.png" type="image/x-icon" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" type="text/css" href="css/estilos_site.css">
  <style>
    .step { display: none; }
    .step.active { display: block; }
    .stepper-dot { width: 36px; height: 36px; border-radius: 9999px; display: flex; align-items: center; justify-content: center; }
    .stepper-dot.active { background-color: #ffc107; color: #1a2c3d; }
    .stepper-dot.inactive { background-color: rgba(255,255,255,0.15); color: #fff; }
    /* Dark mode overrides */
    .light\:bg-white { background-color: #ffffff; }
  </style>
</head>
<body class="font-poppins min-h-screen overflow-x-hidden dark:bg-[#0b1628] dark:text-white bg-gradient-to-br from-primary-dark to-primary text-white">
  <div class="py-4 px-4 sm:px-6 lg:px-8">
    <div class="container mx-auto">
      <div class="bg-glass rounded-2xl p-6 md:p-8 border border-accent/20 shadow-xl dark:border-white/10">
        <div class="flex justify-end mb-2">
          <button type="button" onclick="toggleTheme()" class="inline-flex items-center px-3 py-2 rounded-lg bg-white/10 hover:bg-white/20 text-white">
            <i class="fa fa-moon mr-2"></i><span class="hidden sm:inline">Alternar tema</span>
          </button>
        </div>
        <div class="mb-6 text-center">
          <h1 class="text-2xl md:text-3xl font-bold">Cadastro de Membro</h1>
          <p class="text-gray-300 mt-2 max-w-2xl mx-auto">Preencha seus dados em etapas simples. Você pode revisar antes de enviar.</p>
        </div>
        <div class="flex items-center justify-center gap-4 md:gap-8 mb-8">
          <div class="flex flex-col items-center">
            <div id="dot-1" class="stepper-dot active"><span>1</span></div>
            <span class="text-xs mt-2 hidden md:block">Dados Pessoais</span>
          </div>
          <div class="w-10 md:w-24 h-1 bg-white/20"></div>
          <div class="flex flex-col items-center">
            <div id="dot-2" class="stepper-dot inactive"><span>2</span></div>
            <span class="text-xs mt-2 hidden md:block">Contato & Endereço</span>
          </div>
          <div class="w-10 md:w-24 h-1 bg-white/20"></div>
          <div class="flex flex-col items-center">
            <div id="dot-3" class="stepper-dot inactive"><span>3</span></div>
            <span class="text-xs mt-2 hidden md:block">Documentos & Extras</span>
          </div>
          <div class="w-10 md:w-24 h-1 bg-white/20"></div>
          <div class="flex flex-col items-center">
            <div id="dot-4" class="stepper-dot inactive"><span>4</span></div>
            <span class="text-xs mt-2 hidden md:block">Igreja & Envio</span>
          </div>
        </div>
        <form id="form" method="POST" enctype='multipart/form-data' class="space-y-6">
        <!-- Etapa 1: Dados Pessoais -->
        <div id="step-1" class="step active">
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
              <label class="form-label text-gray-200">Nome Completo</label>
              <input type="text" class="form-input w-full" id="nome" name="nome" placeholder="Seu nome" required />
            </div>
            <div>
              <label class="form-label text-gray-200">CPF</label>
              <input type="text" class="form-input w-full" id="cpf" name="cpf" placeholder="000.000.000-00" />
            </div>
            <div>
              <label class="form-label text-gray-200">Nascimento</label>
              <input type="date" class="form-input w-full" id="data_nasc" name="data_nasc" value="<?php echo date('Y-m-d') ?>" />
            </div>
          </div>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
            <div>
              <label class="form-label text-gray-200">Estado Civil</label>
              <select class="form-select w-full" id="estado" name="estado">
                <option value="0">Selecione</option>
                <option value="Solteiro">Solteiro</option>
                <option value="Casado">Casado</option>
              </select>
            </div>
            <div>
              <label class="form-label text-gray-200">Profissão</label>
              <input type="text" class="form-input w-full" id="profissao" name="profissao" />
            </div>
            <div>
              <label class="form-label text-gray-200">Cônjuge</label>
              <input type="text" class="form-input w-full" id="conjuge" name="conjuge" />
            </div>
          </div>
        </div>

        <!-- Etapa 2: Contato & Endereço -->
        <div id="step-2" class="step">
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
              <label class="form-label text-gray-200">Telefone</label>
              <input type="tel" class="form-input w-full" id="telefone" name="telefone" placeholder="(00) 00000-0000" />
            </div>
            <div>
              <label class="form-label text-gray-200">Email</label>
              <input type="email" class="form-input w-full" id="email" name="email" placeholder="email@exemplo.com" />
            </div>
            <div>
              <label class="form-label text-gray-200">Endereço</label>
              <input type="text" class="form-input w-full" id="endereco" name="endereco" placeholder="Rua, Número, Bairro" />
            </div>
          </div>
          <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-4">
            <div>
              <label class="form-label text-gray-200">Cidade</label>
              <input type="text" class="form-input w-full" id="cidade_anterior" name="cidade_anterior" />
            </div>
            <div>
              <label class="form-label text-gray-200">Estado</label>
              <input type="text" class="form-input w-full" id="estado_votacao" name="estado_votacao" />
            </div>
            <div>
              <label class="form-label text-gray-200">RG</label>
              <input type="text" class="form-input w-full" id="rg" name="rg" />
            </div>
            <div>
              <label class="form-label text-gray-200">Título de Eleitor</label>
              <input type="text" class="form-input w-full" id="titulo_eleitor" name="titulo_eleitor" />
            </div>
          </div>
        </div>

        <!-- Etapa 3: Documentos & Extras -->
        <div id="step-3" class="step">
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
              <label class="form-label text-gray-200">Data Batismo</label>
              <input type="date" class="form-input w-full" id="data_bat" name="data_bat" />
            </div>
            <div>
              <label class="form-label text-gray-200">Data Consagração</label>
              <input type="date" class="form-input w-full" id="data_consagracao" name="data_consagracao" />
            </div>
            <div>
              <label class="form-label text-gray-200">Data Membresia</label>
              <input type="date" class="form-input w-full" id="membresia" name="membresia" />
            </div>
          </div>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
            <div>
              <label class="form-label text-gray-200">Igreja Anterior</label>
              <input type="text" class="form-input w-full" id="igreja_anterior" name="igreja_anterior" />
            </div>
            <div>
              <label class="form-label text-gray-200">Cidade Votação</label>
              <input type="text" class="form-input w-full" id="cidade_votacao" name="cidade_votacao" />
            </div>
            <div>
              <label class="form-label text-gray-200">Estado Cívil</label>
              <select class="form-select w-full" id="cargo" name="cargo">
                <option value="0">Selecione um Cargo</option>
                <?php 
                $query = $pdo->query("SELECT * FROM cargos order by id asc");
                $res = $query->fetchAll(PDO::FETCH_ASSOC);
                foreach($res as $c){
                  $id_reg = $c['id'];
                  $nome_reg = $c['nome'];
                  echo "<option value='{$id_reg}'>{$nome_reg}</option>";
                }
                ?>
              </select>
            </div>
          </div>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4 items-center">
            <div class="md:col-span-2">
              <label class="form-label text-gray-200">Observações</label>
              <textarea class="form-input w-full" id="obs" name="obs" rows="4" maxlength="3000" placeholder="Informações adicionais..."></textarea>
            </div>
            <div class="flex flex-col items-center gap-2">
              <label class="form-label text-gray-200">Foto (200x200)</label>
              <input class="form-input w-full" type="file" name="imagem" id="foto" accept="image/*" />
              <img id="preview" src="sistema/img/sem-foto.png" class="rounded-lg w-24 h-24 object-cover" alt="Prévia"/>
            </div>
          </div>
        </div>

        <!-- Etapa 4: Igreja & Envio -->
        <div id="step-4" class="step">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="form-label text-gray-200">Selecionar Congregação</label>
              <select class="form-select w-full" id="igreja" name="igreja" required>
                <option value="0">Selecione uma Igreja</option>
                <?php 
                $query = $pdo->query("SELECT * FROM igrejas order by id asc");
                $res = $query->fetchAll(PDO::FETCH_ASSOC);
                foreach($res as $i){
                  echo "<option value='{$i['id']}'>{$i['nome']}</option>";
                }
                ?>
              </select>
            </div>
            <div class="flex items-end">
              <div class="bg-gray-900/50 border border-white/10 rounded-xl p-3 w-full">
                <p class="text-sm text-gray-300">Ao enviar, você confirma que leu e concorda com os termos de privacidade da igreja.</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Ações -->
        <div class="flex items-center justify-between pt-4">
          <button type="button" id="prev" class="inline-flex items-center px-4 py-2 rounded-lg bg-white/10 hover:bg-white/20 text-white disabled:opacity-40">
            <i class="fa fa-chevron-left mr-2"></i> Voltar
          </button>
          <div class="flex gap-3">
            <button type="button" id="next" class="inline-flex items-center px-4 py-2 rounded-lg bg-accent text-primary-dark hover:bg-accent/90">
              Próximo <i class="fa fa-chevron-right ml-2"></i>
            </button>
            <button type="submit" id="submitBtn" class="hidden inline-flex items-center px-4 py-2 rounded-lg bg-customGreen hover:bg-green-700 text-white">
              <i class="fa fa-paper-plane mr-2"></i> Enviar
            </button>
          </div>
        </div>
        </form>
      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
    // Toggle dark mode respeitando preferências do sistema
    const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
    const savedTheme = localStorage.getItem('membro_theme');
    if (savedTheme === 'dark' || (!savedTheme && prefersDark)) {
      document.documentElement.classList.add('dark');
    }

    function toggleTheme(){
      const isDark = document.documentElement.classList.toggle('dark');
      localStorage.setItem('membro_theme', isDark ? 'dark' : 'light');
    }

    const steps = [
      document.getElementById('step-1'),
      document.getElementById('step-2'),
      document.getElementById('step-3'),
      document.getElementById('step-4'),
    ];
    const dots = [
      document.getElementById('dot-1'),
      document.getElementById('dot-2'),
      document.getElementById('dot-3'),
      document.getElementById('dot-4'),
    ];
    let current = 0;

    function updateUI(){
      steps.forEach((s, i) => s.classList.toggle('active', i === current));
      dots.forEach((d, i) => {
        d.classList.toggle('active', i <= current);
        d.classList.toggle('inactive', i > current);
      });
      document.getElementById('prev').disabled = current === 0;
      document.getElementById('next').classList.toggle('hidden', current === steps.length - 1);
      document.getElementById('submitBtn').classList.toggle('hidden', current !== steps.length - 1);
    }

    document.getElementById('prev').addEventListener('click', () => { if(current > 0){ current--; updateUI(); }});
    document.getElementById('next').addEventListener('click', () => { if(current < steps.length - 1){ current++; updateUI(); }});

    // Prévia da imagem
    document.getElementById('foto').addEventListener('change', function(){
      const file = this.files[0];
      if(!file) return;
      const reader = new FileReader();
      reader.onload = e => document.getElementById('preview').src = e.target.result;
      reader.readAsDataURL(file);
    });

    // Submit
    $("#form").submit(function (e) {
      e.preventDefault();
      const formData = new FormData(this);
      $.ajax({
        url: 'sistema/painel-igreja/paginas/membros/salvar.php',
        type: 'POST',
        data: formData,
        success: function (mensagem) {
          alert(mensagem);
        },
        cache: false,
        contentType: false,
        processData: false,
      });
    });

    updateUI();
  </script>
</body>
</html>
