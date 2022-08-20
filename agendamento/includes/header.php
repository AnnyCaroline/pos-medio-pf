<?php
    if(!isset($_SESSION)){
        session_start();
    }
    
    include_once($_SERVER['DOCUMENT_ROOT']."/agendamento/includes/utils.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendamento - cadastro de usuário</title>

    <link rel="stylesheet" href="/agendamento/includes/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
</head>
<body class="bg-dark text-white">
    <?php
        if (usuario_esta_logado()) {
            include $_SERVER['DOCUMENT_ROOT']."/agendamento/includes/nav.php";
        }
    ?>
    <main class="container">