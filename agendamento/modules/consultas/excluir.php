<?php
    include_once($_SERVER['DOCUMENT_ROOT']."/agendamento/includes/utils.php");
    session_start_if_necessary();
    permitir_usuarios_com_niveis([1,2,3]);

    if (!empty($_GET['id'])) {
        include($_SERVER['DOCUMENT_ROOT'].'/agendamento/common/conexaoBd.php');

        $id = mysqli_real_escape_string($conexao, $_GET['id']);

        $sqlSelect = "SELECT id, medico, paciente FROM consultas WHERE id=$id";
        $resultSelect = $conexao->query($sqlSelect);

        if($resultSelect->num_rows > 0) {
            $consulta = mysqli_fetch_assoc($resultSelect);
            
            /*
                "Você não tem permissão para excluir essa consulta" é uma mensagem mais correta, 
                mas que acaba dando mais informações para usuários mal intencionados. Prefira
                respostas mais genéricas, com "Consulta não encontrada".
            */


            if ($_SESSION['usuario_nivel'] == 2 && $consulta['medico'] != $_SESSION['usuario_id']) {
                // echo "Você não tem permissão para excluir essa consulta";
                echo "Consulta não encotrada";
                exit();
            }

            if ($_SESSION['usuario_nivel'] == 3 && $consulta['paciente'] != $_SESSION['usuario_id']) {
                // echo "Você não tem permissão para excluir essa consulta";
                echo "Consulta não encotrada";
                exit();
            }

            $sqlDelete = "DELETE FROM consultas WHERE id=$id";
            $resultDelete = $conexao->query($sqlDelete);

            header('Location: ./index.php');
        }
    }
?>