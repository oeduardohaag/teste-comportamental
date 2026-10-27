# 🎯 Teste de Perfil Comportamental (Águia, Gato, Lobo e Tubarão)

Aplicação web desenvolvida em **PHP**, **HTML5**, **CSS3** e **MySQL** para a realização de testes de perfil comportamental baseados em 4 arquétipos principais: **Águia** (Inovador), **Gato** (Comunicativo), **Lobo** (Organizado) e **Tubarão** (Executor).

---

## 🚀 Funcionalidades

- **Autenticação & Cadastro:** Registro e login de usuários salvos diretamente no banco de dados MySQL via PDO (com suporte a hash seguro de senhas).
- **Questionário Completo:** 24 perguntas estruturadas com 4 alternativas cada.
- **Validação em Tempo Real e Servidor:** Impede o envio do teste caso existam perguntas não respondidas, destacando-as visualmente.
- **Barra de Progresso:** Atualização dinâmica do progresso conforme as questões são preenchidas.
- **Processamento de Resultados:**
  - Cálculo automático da pontuação para os 4 perfis comportamentais.
  - Exibição de percentuais e gráficos visuais (barras de progresso).
  - Tratamento nativo para empates entre perfis.
  - Salvamento dos resultados individuais atrelados ao usuário no banco de dados.

---

## 🛠️ Tecnologias Utilizadas

- **PHP 8.x** (Lógica do servidor, sessões e PDO)
- **MySQL / phpMyAdmin** (Banco de dados relacional)
- **HTML5 & CSS3** (Interface responsiva e semântica)
- **JavaScript (Vanilla)** (Controle e feedback de progresso no front-end)

---

## 📁 Estrutura do Projeto

```text
teste-comportamental/
├── css/
│   └── estilo.css           # Folha de estilos padronizada
├── dados/
│   └── perguntas.php        # Array de perguntas e alternativas
├── conexao.php              # Conexão PDO com o MySQL
├── index.php                # Tela inicial, login e cadastro
├── teste.php                # Formulário com as 24 questões
├── resultado.php            # Processamento e exibição dos resultados
└── README.md                # Documentação do projeto
```

---

## 🗄️ Configuração do Banco de Dados (MySQL)

Antes de executar o projeto, crie a estrutura do banco de dados no seu **phpMyAdmin** executando o script SQL abaixo:

```sql
CREATE DATABASE IF NOT EXISTS teste_comportamental CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE teste_comportamental;

CREATE TABLE IF NOT EXISTS usuario (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS resultados (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    aguia INT NOT NULL DEFAULT 0,
    gato INT NOT NULL DEFAULT 0,
    lobo INT NOT NULL DEFAULT 0,
    tubarao INT NOT NULL DEFAULT 0,
    data_teste TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuario(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

---

## ⚙️ Como Executar o Projeto

1. **Clone o repositório** para a pasta `htdocs` do seu XAMPP (ou equivalente):
   ```bash
   git clone https://github.com/seu-usuario/teste-comportamental.git
   ```
2. **Inicie os serviços** do Apache e MySQL através do painel do XAMPP.
3. Importe a estrutura SQL acima no **phpMyAdmin** (`http://localhost/phpmyadmin`).
4. Verifique as credenciais no arquivo `conexao.php`:
   ```php
   $host = 'localhost';
   $db   = 'teste_comportamental';
   $user = 'root';
   $pass = '';
   ```
5. Acesse no seu navegador:
   ```text
   http://localhost/teste-comportamental/
   ```

---

## 📄 Licença

Este projeto foi desenvolvido para fins educacionais e de avaliação acadêmica. Livre para uso e modificações.