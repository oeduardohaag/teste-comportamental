<?php
$host    = "localhost";
$banco   = "teste_comportamental";
$usuario = "root";
$senha   = "";

$conn = new PDO(
    "mysql:host=$host;dbname=$banco;charset=utf8",
    $usuario,
    $senha
);

$conn->setAttribute(
    PDO::ATTR_ERRMODE,
    PDO::ERRMODE_EXCEPTION
);
?>