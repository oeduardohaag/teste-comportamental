<?php
session_start();
require_once __DIR__ . '/conexao.php';
require_once __DIR__ . '/dados/perguntas.php';

// Verifica se o usuário está logado e se as respostas estão disponíveis
if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['respostas'])) {
    header('Location: index.php');
    exit;
}

// Recupera os dados do usuário e as respostas do teste
$usuario_id = $_SESSION['usuario_id'];
$nome_usuario = $_SESSION['nome_usuario'];
$respostas = $_SESSION['respostas'];

// Inicializa a pontuação de cada perfil
$pontuacao = [
    'aguia'   => 0,
    'gato'    => 0,
    'lobo'    => 0,
    'tubarao' => 0
];

// Calcula a pontuação de cada perfil com base nas respostas
foreach ($respostas as $perfilEscolhido) {
    if (isset($pontuacao[$perfilEscolhido])) {
        $pontuacao[$perfilEscolhido]++;
    }
}

// Insere os resultados do testeno banco de dados
$stmt = $conn->prepare("INSERT INTO resultados (usuario_id, aguia, gato, lobo, tubarao) VALUES (?, ?, ?, ?, ?)");
$stmt->execute([
    $usuario_id,
    $pontuacao['aguia'],
    $pontuacao['gato'],
    $pontuacao['lobo'],
    $pontuacao['tubarao']
]);

$total_perguntas = count($perguntas);
$maior = max($pontuacao);
$predominantes = [];

// Identifica os perfis com a maior pontuação
foreach ($pontuacao as $perfil => $pontos) {
    if ($pontos == $maior) {
        $predominantes[] = $perfil;
    }
}

// Detalhes dos perfis para exibição
$detalhes_perfis = [
    'aguia'   => ['titulo' => 'Águia', 'sub' => 'Sonhador / Inovador', 'desc' => 'Criativo, visionário, gosta de mudanças, inovação e possibilidades futuras.'],
    'gato'    => ['titulo' => 'Gato', 'sub' => 'Comunicativo / Relacional', 'desc' => 'Expressivo, sociável, energizado por pessoas, colaboração e relacionamentos.'],
    'lobo'    => ['titulo' => 'Lobo', 'sub' => 'Organizado / Planejador', 'desc' => 'Analítico, metódico, detalhista, cuidadoso com processos, regras e informações.'],
    'tubarao' => ['titulo' => 'Tubarão', 'sub' => 'Executor / Prático', 'desc' => 'Determinado, direto, orientado a metas, decisões e resultados rápidos.']
];

$classe_principal = count($predominantes) === 1 ? $predominantes[0] : 'empate';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Seu Resultado — Teste Comportamental</title>
  <link rel="stylesheet" href="css/estilo.css">
</head>
<body>
<main class="container">

  <h1 class="titulo-pagina">Resultado de <?= htmlspecialchars($nome_usuario) ?></h1>

  <div class="destaque <?= $classe_principal ?>">
    <div class="pequeno">Perfil Predominante</div>
    <?php if (count($predominantes) === 1): ?>
      <?php $p = $predominantes[0]; ?>
      <h1><?= $detalhes_perfis[$p]['titulo'] ?></h1>
      <p><strong><?= $detalhes_perfis[$p]['sub'] ?></strong></p>
      <p><?= $detalhes_perfis[$p]['desc'] ?></p>
    <?php else: ?>
      <h1>Resultado Combinado: <?= implode(' + ', array_map('ucfirst', $predominantes)) ?></h1>
      <p>Houve um empate entre os perfis com a maior pontuação de <?= $maior ?> pontos!</p>
    <?php endif; ?>
  </div>

  <section class="painel">
    <h2>Placar Detalhado</h2>
    <ul class="placar">
      <?php foreach ($pontuacao as $perfil => $pontos): ?>
        <?php $percentual = ($pontos / $total_perguntas) * 100; ?>
        <li class="<?= $perfil ?>">
          <div class="linha">
            <span><?= $detalhes_perfis[$perfil]['titulo'] ?> (<?= $detalhes_perfis[$perfil]['sub'] ?>)</span>
            <span><?= $pontos ?> pts (<?= number_format($percentual, 1) ?>%)</span>
          </div>
          <div class="trilho">
            <span style="width: <?= $percentual ?>%;"></span>
          </div>
        </li>
      <?php endforeach; ?>
    </ul>

    <div class="acoes" style="margin-top: 2rem;">
      <a class="btn" href="teste.php">Refazer o teste</a>
      <button class="btn secundario" type="button" onclick="window.print()">Imprimir resultado</button>
      <a class="btn secundario" href="index.php">Trocar de Usuário</a>
    </div>
  </section>

</main>
</body>
</html>