/**
 * Financeiro JS - Funcoes centralizadas para o modulo financeiro
 * Usado por: receber.php, pagar.php
 * Criado em: 2026-01-23 - Sprint 3
 *
 * IMPORTANTE: A variavel 'pag' deve ser definida antes de incluir este script
 * Exemplo: <script>var pag = "receber";</script>
 */

/**
 * Funcao de busca/filtro de contas
 */
function buscar() {
    var filtro = $('#tipo_data_filtro').val();
    var dataInicial = $('#dataInicial').val();
    var dataFinal = $('#dataFinal').val();
    var tipo_data = $('#tipo_data').val();
    listar(filtro, dataInicial, dataFinal, tipo_data);
}

/**
 * Altera o tipo de data para filtro
 * @param {string} tipo - Tipo de data (vencimento, data_pgto, data_lanc)
 */
function tipoData(tipo) {
    $('#tipo_data').val(tipo);
    buscar();
}

/**
 * Calcula o subtotal da baixa considerando juros, multa, taxa e desconto
 */
function totalizar() {
    var valor = $('#valor-baixar').val() || '0';
    var desconto = $('#valor-desconto').val() || '0';
    var juros = $('#valor-juros').val() || '0';
    var multa = $('#valor-multa').val() || '0';
    var taxa = $('#valor-taxa').val() || '0';

    valor = valor.replace(",", ".");
    desconto = desconto.replace(",", ".");
    juros = juros.replace(",", ".");
    multa = multa.replace(",", ".");
    taxa = taxa.replace(",", ".");

    valor = parseFloat(valor) || 0;
    desconto = parseFloat(desconto) || 0;
    juros = parseFloat(juros) || 0;
    multa = parseFloat(multa) || 0;
    taxa = parseFloat(taxa) || 0;

    var subtotal = valor + juros + taxa + multa - desconto;
    $('#subtotal').val(subtotal.toFixed(2));
}

/**
 * Calcula a taxa baseada na forma de pagamento
 */
function calcularTaxa() {
    var pgto = $('#saida-baixar').val();
    var valor = $('#valor-baixar').val();

    $.ajax({
        url: 'paginas/' + pag + "/calcular_taxa.php",
        method: 'POST',
        data: { valor: valor, pgto: pgto },
        dataType: "html",
        success: function(result) {
            $('#valor-taxa').val(result);
            totalizar();
        }
    });
}

/**
 * Exclui um registro
 * @param {number} id - ID do registro
 */
function excluir(id) {
    $('#mensagem-excluir').text('Excluindo...');

    $.ajax({
        url: 'paginas/' + pag + "/excluir.php",
        method: 'POST',
        data: { id: id },
        dataType: "html",
        success: function(mensagem) {
            if (mensagem.trim() == "Excluído com Sucesso") {
                buscar();
                limparCampos();
            } else {
                $('#mensagem-excluir').addClass('text-danger');
                $('#mensagem-excluir').text(mensagem);
            }
        }
    });
}

/**
 * Retorna o icone apropriado para preview de arquivo
 * @param {string} extensao - Extensao do arquivo
 * @returns {string|null} - Caminho do icone ou null para imagem
 */
function getIconeArquivo(extensao) {
    var ext = extensao.toLowerCase();

    if (ext === 'pdf') return "../img/pdf.png";
    if (ext === 'rar' || ext === 'zip') return "../img/rar.png";
    if (ext === 'doc' || ext === 'docx' || ext === 'txt') return "../img/word.png";
    if (ext === 'xlsx' || ext === 'xlsm' || ext === 'xls') return "../img/excel.png";
    if (ext === 'xml') return "../img/xml.png";

    return null; // Retorna null para imagens que devem ser exibidas normalmente
}

/**
 * Carrega preview da imagem/arquivo no modal principal
 */
function carregarImg() {
    var target = document.getElementById('target');
    var file = document.querySelector("#arquivo").files[0];

    if (!file) {
        target.src = "";
        return;
    }

    var arquivo = file.name;
    var resultado = arquivo.split(".", 2);
    var icone = getIconeArquivo(resultado[1]);

    if (icone) {
        $('#target').attr('src', icone);
        return;
    }

    // Para imagens, carrega o preview
    var reader = new FileReader();
    reader.onloadend = function() {
        target.src = reader.result;
    };
    reader.readAsDataURL(file);
}

/**
 * Carrega preview do arquivo no modal de arquivos adicionais
 */
function carregarImgArquivos() {
    var target = document.getElementById('target-arquivos');
    var file = document.querySelector("#arquivo_conta").files[0];

    if (!file) {
        target.src = "";
        return;
    }

    var arquivo = file.name;
    var resultado = arquivo.split(".", 2);
    var icone = getIconeArquivo(resultado[1]);

    if (icone) {
        $('#target-arquivos').attr('src', icone);
        return;
    }

    // Para imagens, carrega o preview
    var reader = new FileReader();
    reader.onloadend = function() {
        target.src = reader.result;
    };
    reader.readAsDataURL(file);
}

/**
 * Busca o valor total das contas selecionadas para baixa em lote
 */
function valorBaixar() {
    var ids = $('#ids').val();

    $.ajax({
        url: 'paginas/' + pag + "/valor_baixar.php",
        method: 'POST',
        data: { ids: ids },
        dataType: "html",
        success: function(result) {
            $("#total_contas").html(result);
        }
    });
}

/**
 * Lista os arquivos anexados a uma conta
 */
function listarArquivos() {
    var id = $('#id-arquivo').val();

    $.ajax({
        url: 'paginas/' + pag + "/listar-arquivos.php",
        method: 'POST',
        data: { id: id },
        dataType: "text",
        success: function(result) {
            $("#listar-arquivos").html(result);
        }
    });
}

/**
 * Inicializa os handlers de formulario quando o documento estiver pronto
 */
$(document).ready(function() {

    // Handler do formulario de baixa
    $("#form-baixar").submit(function(event) {
        event.preventDefault();
        var formData = new FormData(this);

        $.ajax({
            url: 'paginas/' + pag + "/baixar.php",
            type: 'POST',
            data: formData,
            success: function(mensagem) {
                $('#mensagem-baixar').text('');
                $('#mensagem-baixar').removeClass();
                if (mensagem.trim() == "Baixado com Sucesso") {
                    $('#btn-fechar-baixar').click();
                    buscar();
                } else {
                    $('#mensagem-baixar').addClass('text-danger');
                    $('#mensagem-baixar').text(mensagem);
                }
            },
            cache: false,
            contentType: false,
            processData: false
        });
    });

    // Handler do formulario de parcelamento
    $("#form-parcelar").submit(function(event) {
        event.preventDefault();
        var formData = new FormData(this);

        $.ajax({
            url: 'paginas/' + pag + "/parcelar.php",
            type: 'POST',
            data: formData,
            success: function(mensagem) {
                $('#mensagem-parcelar').text('');
                $('#mensagem-parcelar').removeClass();
                if (mensagem.trim() == "Parcelado com Sucesso") {
                    $('#btn-fechar-parcelar').click();
                    buscar();
                } else {
                    $('#mensagem-parcelar').addClass('text-danger');
                    $('#mensagem-parcelar').text(mensagem);
                }
            },
            cache: false,
            contentType: false,
            processData: false
        });
    });

    // Handler do formulario de arquivos
    $("#form-arquivos").submit(function(event) {
        event.preventDefault();
        var formData = new FormData(this);

        $.ajax({
            url: 'paginas/' + pag + "/arquivos.php",
            type: 'POST',
            data: formData,
            success: function(mensagem) {
                $('#mensagem-arquivo').text('');
                $('#mensagem-arquivo').removeClass();
                if (mensagem.trim() == "Inserido com Sucesso") {
                    $('#nome-arq').val('');
                    $('#arquivo_conta').val('');
                    $('#target-arquivos').attr('src', '../img/arquivos/sem-foto.png');
                    listarArquivos();
                } else {
                    $('#mensagem-arquivo').addClass('text-danger');
                    $('#mensagem-arquivo').text(mensagem);
                }
            },
            cache: false,
            contentType: false,
            processData: false
        });
    });

});
