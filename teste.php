<?php
session_start();
require_once __DIR__ . '/dados/perguntas.php';

// Verifica se o usuário está logado
$nome = trim($_SESSION['nome_usuario'] ?? '');

if (empty($nome) || !isset($_SESSION['usuario_id'])) {
    header('Location: index.php');
    exit;
}

$respostas = $_POST['respostas'] ?? [];
$erro = '';
$perguntas_faltando = [];

// Valida se todas as perguntas foram respondidas
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['enviado'])) {
    foreach ($perguntas as $index => $pergunta) {
        if (!isset($respostas[$index]) || empty($respostas[$index])) {
            $perguntas_faltando[] = $index;
        }
    }

    // Se houver perguntas sem resposta, exibe uma mensagem de erro
    if (!empty($perguntas_faltando)) { 
        $erro = 'Faltam ' . count($perguntas_faltando) . ' pergunta(s) sem resposta. Responda todas para ver seu resultado.';
    } else { // Se todas as perguntas foram respondidas, salva as respostas na sessão e redireciona para a página de resultados
        $_SESSION['respostas'] = $respostas;
        header('Location: resultado.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Teste de perfil — perguntas</title>
  <link rel="stylesheet" href="css/estilo.css">
</head>
<body>

<div class="topo-progresso">
  <div class="container" style="padding:0">
    <div class="texto">
      <span>Olá, <?= htmlspecialchars($nome) ?></span>
      <span id="contador">0 de <?= count($perguntas) ?> respondidas</span>
    </div>
    <div class="barra"><span id="preenchimento"></span></div>
  </div>
</div>

<main class="container">
  <h1 class="titulo-pagina">Escolha uma alternativa por pergunta</h1>

  <?php if (!empty($erro)): ?>
    <div class="alerta" role="alert">
      <strong><?= htmlspecialchars($erro) ?></strong>
    </div>
  <?php endif; ?>

  <form action="teste.php" method="post" id="form-teste">
    <input type="hidden" name="enviado" value="1">

    <?php foreach ($perguntas as $index => $pergunta): ?>
      <?php 
        $faltou = in_array($index, $perguntas_faltando);
        $classe_pergunta = 'pergunta' . ($faltou ? ' sem-resposta' : '');
        $resposta_selecionada = $respostas[$index] ?? '';
      ?>
      <fieldset class="<?= $classe_pergunta ?>" style="margin-left:0;margin-right:0" id="pergunta-<?= $index ?>">
        <legend class="numero" style="padding:0">Pergunta <?= $index + 1 ?> de <?= count($perguntas) ?></legend>
        <h2><?= htmlspecialchars($pergunta['texto']) ?></h2>

        <?php foreach ($pergunta['alternativas'] as $opcao): ?>
          <?php $checked = ($resposta_selecionada === $opcao['perfil']) ? 'checked' : ''; ?>
          <label class="opcao">
            <input type="radio" name="respostas[<?= $index ?>]" value="<?= htmlspecialchars($opcao['perfil']) ?>" <?= $checked ?>>
            <span><?= htmlspecialchars($opcao['texto']) ?></span>
          </label>
        <?php endforeach; ?>
      </fieldset>
    <?php endforeach; ?>

    <div class="acoes">
      <button class="btn" type="submit">Ver meu resultado</button>
      <a class="btn secundario" href="index.php">Voltar ao início</a>
    </div>
  </form>
</main>

<script>
(function () {
  var form = document.getElementById('form-teste');
  var total = <?= count($perguntas) ?>;
  function atualizar() {
    var nomes = {};
    form.querySelectorAll('input[type=radio]:checked').forEach(function (r) { nomes[r.name] = true; });
    var feitas = Object.keys(nomes).length;
    document.getElementById('contador').textContent = feitas + ' de ' + total + ' respondidas';
    document.getElementById('preenchimento').style.width = (feitas / total * 100) + '%';
  }
  form.addEventListener('change', atualizar);
  atualizar();
})();
</script>
</body>
</html>