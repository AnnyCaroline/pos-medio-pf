<?php
    include_once($_SERVER['DOCUMENT_ROOT']."/agendamento/includes/utils.php");

    session_start_if_necessary();

    if (!isset($_SESSION['email']) || !isset($_SESSION['senha'])) {
        unset($_SESSION['nome']);
        unset($_SESSION['email']);

        header("Location: /agendamento/modules/login/index.php");
    }
?>