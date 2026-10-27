<?php
session_start();
require_once __DIR__ . '/conexao.php';

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') { 
    $nome = trim($_POST['nome'] ?? '');
    $senha = trim($_POST['senha'] ?? '');
    
    // Valida se o nome de usuário e a senha foram fornecidos
    if (empty($nome) || empty($senha)) {
        $erro = 'Por favor, informe o nome de usuário e a senha.';
    } else {
        $stmt = $conn->prepare("SELECT id, nome, senha FROM usuario WHERE nome = ?");
        $stmt->execute([$nome]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        // Valida a senha de um usuário que existe
        if ($usuario) {
            if (password_verify($senha, $usuario['senha']) || $senha === $usuario['senha']) {
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['nome_usuario'] = $usuario['nome'];
                header('Location: teste.php');
                exit;
            } else {
                $erro = 'Senha incorreta. Tente novamente.';
            }
        // Se o usuário não existir, cria um novo usuário e salva no banco de dados
        } else {
            $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
            $stmtInsert = $conn->prepare("INSERT INTO usuario (nome, senha) VALUES (?, ?)");
            $stmtInsert->execute([$nome, $senhaHash]);
            
            // Salva o ID do novo usuário e o nome na sessão
            $_SESSION['usuario_id'] = $conn->lastInsertId();
            $_SESSION['nome_usuario'] = $nome;
            header('Location: teste.php');
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Qual é o seu perfil? — Águia, Gato, Lobo ou Tubarão</title>
  <link rel="stylesheet" href="css/estilo.css">
</head>
<body>
<main class="container">

  <div class="capa" aria-hidden="true">
    <div class="aguia">Águia</div><div class="gato">Gato</div>
    <div class="lobo">Lobo</div><div class="tubarao">Tubarão</div>
  </div>

  <h1 class="titulo-pagina">Qual animal é o seu perfil?</h1>
  <p>São 24 perguntas rápidas com quatro alternativas cada. Escolha a que mais combina com você. No final, você descobre seu perfil predominante.</p>

  <section class="painel">
    
    <?php if (!empty($erro)): ?>
      <div class="alerta" role="alert">
        <strong><?= htmlspecialchars($erro) ?></strong>
      </div>
    <?php endif; ?>

    <form action="index.php" method="post">
      <label class="campo" for="nome">Nome de Usuário</label>
      <input type="text" id="nome" name="nome" placeholder="Digite seu usuário" required autocomplete="username" value="<?= htmlspecialchars($_POST['nome'] ?? '') ?>">

      <label class="campo" for="senha">Senha</label>
      <input type="password" id="senha" name="senha" placeholder="Digite sua senha" required autocomplete="current-password">

      <button class="btn" type="submit" style="margin-top: 10px;">Entrar e Iniciar Teste</button>
    </form>

    <ul class="perfis-lista">
      <li class="aguia"><strong>Águia</strong> — Sonhador / Inovador</li>
      <li class="gato"><strong>Gato</strong> — Comunicativo / Relacional</li>
      <li class="lobo"><strong>Lobo</strong> — Organizado / Planejador</li>
      <li class="tubarao"><strong>Tubarão</strong> — Executor / Prático</li>
    </ul>
  </section>

</main>
</body>
</html>