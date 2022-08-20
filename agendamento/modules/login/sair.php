<?php
    session_start();

    unset($_SESSION['usuario_id']);
    unset($_SESSION['usuario_nome']);
    unset($_SESSION['usuario_nivel']);

    header("Location: /agendamento/index.php");
?>