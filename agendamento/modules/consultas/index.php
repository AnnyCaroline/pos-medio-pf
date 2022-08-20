<?php
    include_once($_SERVER['DOCUMENT_ROOT']."/agendamento/includes/utils.php");
    session_start_if_necessary();
    permitir_usuarios_com_niveis([1,2, 3]);
    
    $idUsuarioLogado = $_SESSION['usuario_id'];

    include $_SERVER['DOCUMENT_ROOT']."/agendamento/common/conexaoBd.php";

    $sql = "SELECT C.id, C.data, C.hora, C.paciente, C.medico, C.sala, UM.nome as medico_nome, UP.nome as paciente_nome, S.nome as sala_nome FROM consultas C ";
    $sql .= "LEFT JOIN usuarios UM ON C.medico = UM.id ";
    $sql .= "LEFT JOIN usuarios UP ON C.paciente = UP.id ";
    $sql .= "LEFT JOIN salas S ON C.sala = S.id ";

    if ($_SESSION['usuario_nivel'] == 2) {
        $sql .= "WHERE C.medico=$idUsuarioLogado ";
    } else if ($_SESSION['usuario_nivel'] == 3) {
        $sql .= "WHERE C.paciente=$idUsuarioLogado ";
    }


    $sql .= "ORDER BY data DESC";

    $result = $conexao->query($sql);
?>

<?php include $_SERVER['DOCUMENT_ROOT']."/agendamento/includes/header.php"; ?>

<a href="./manter.php" class="btn btn-primary mb-3"> Agendar consulta </a>

<table class="table text-white table-bg">
    <thead>
        <tr>
            <th>Data</th>
            <th>Hora</th>
            <th>Paciente</th>
            <th>Médico</th>
            <th>Sala</th>
            <th></th>
        </tr>
    </thead>

    <tbody>
        <?php
            while($consulta = mysqli_fetch_assoc($result)) {
                $id = $consulta['id'];
        ?>
                <tr>
                    <td><?php echo $consulta['data'];?></td>
                    <td><?php echo $consulta['hora'];?></td>
                    <td><?php echo $consulta['paciente_nome'];?></td>
                    <td><?php echo $consulta['medico_nome'];?></td>
                    <td><?php echo $consulta['sala_nome'];?></td>
                    
                    <td class="text-end">
                        <a
                            title="Editar"
                            class="btn btn-sm btn-primary"
                            href=<?php echo "manter.php?id=$id"; ?>
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                                <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/>
                            </svg>
                        </a>

                        <a
                            title="Deletar"
                            class="btn btn-sm btn-danger"
                            onclick="return confirm('Tem certeza que deseja excluir?')"
                            href=<?php echo "excluir.php?id=$id"; ?>
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                                <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                            </svg>
                        </a>
                    </td>
                </tr>

                <?php
            }

        ?>
    </tbody>
</table>

<?php include $_SERVER['DOCUMENT_ROOT']."/agendamento/includes/footer.php"; ?>