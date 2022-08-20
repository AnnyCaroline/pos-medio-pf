<?php
    include_once($_SERVER['DOCUMENT_ROOT'].'/agendamento/includes/utils.php');
    session_start_if_necessary();
    permitir_usuarios_com_niveis([1, 2, 3]);

    $nome = $_SESSION['usuario_nome'];
    $nivel = formatarNivelDeAcesso($_SESSION['usuario_nivel']);

    include $_SERVER['DOCUMENT_ROOT']."/agendamento/includes/header.php";

    echo "<h1> Bem vindo, $nome</h1>";
    echo "<h2> Seu nível é: $nivel </h2>";
    
    include $_SERVER['DOCUMENT_ROOT']."/agendamento/includes/footer.php";
?>