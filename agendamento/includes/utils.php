<?php
    function formatarNivelDeAcesso($numeroNivel) {
        $niveis = ['Administrador', 'Médico', 'Paciente'];

        return $niveis[$numeroNivel-1];
    }

    function session_start_if_necessary() {
        if(!isset($_SESSION)){
            session_start();
        }
    }

    function usuario_esta_logado() {
        return isset($_SESSION['usuario_id']) and isset($_SESSION['usuario_nome']) and isset($_SESSION['usuario_nivel']);
    }

    function permitir_usuarios_com_niveis($niveisPermitidos) {
        if(
            !usuario_esta_logado() or
            !in_array($_SESSION['usuario_nivel'], $niveisPermitidos)
        ) {
            include $_SERVER['DOCUMENT_ROOT']."/agendamento/modules/login/sair.php";
        }
    }
?>