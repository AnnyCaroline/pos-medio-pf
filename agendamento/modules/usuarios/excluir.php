<?php
    include_once($_SERVER['DOCUMENT_ROOT']."/agendamento/includes/utils.php");
    session_start_if_necessary();
    permitir_usuarios_com_niveis([1]);

    if (!empty($_GET['id'])) {
        include($_SERVER['DOCUMENT_ROOT'].'/agendamento/common/conexaoBd.php');

        $id = mysqli_real_escape_string($conexao, $_GET['id']);

        $sqlSelect = "SELECT id FROM usuarios WHERE id=$id";
        $resultSelect = $conexao->query($sqlSelect);

        if($resultSelect->num_rows > 0) {
            $sqlDelete = "DELETE FROM usuarios WHERE id=$id";
            $resultDelete = $conexao->query($sqlDelete);

            $idUsuarioLogado = $_SESSION['usuario_id'];
            if ($idUsuarioLogado == $id) { 
                header('Location: ../login/sair.php');
            } else {
                header('Location: index.php');
            }
        }
    }
?>