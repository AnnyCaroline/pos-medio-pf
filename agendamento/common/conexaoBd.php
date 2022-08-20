<?php
    $dbHost = 'localhost';
    $dbUsername = 'root';
    $dbPassword = '';
    $dbName = 'agendamento';

    $conexao = new mysqli($dbHost,$dbUsername,$dbPassword,$dbName);

    if($conexao->connect_errno) {
        exit("Erro ao conectar ao banco de dados");
    } else {
        //echo "Conexão efetuada com sucesso";
        //echo "</br>";
    }
?>