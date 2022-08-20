<?php
    include_once($_SERVER['DOCUMENT_ROOT']."/agendamento/includes/utils.php");
    session_start_if_necessary();
    permitir_usuarios_com_niveis([1]);

    // OBTER LISTA DE USUARIOS
    include  $_SERVER['DOCUMENT_ROOT']."/agendamento/common/conexaoBd.php";
    
    $idUsuarioLogado = mysqli_real_escape_string($conexao, $_SESSION['usuario_id']);

    $sql = "SELECT id, nome, email, nivel, telefone, ativo FROM usuarios WHERE id != $idUsuarioLogado ORDER BY nome ASC";

    if (isset($_GET['campoDaBusca']) and isset($_GET['textoDaBusca'])) {
        $campoDaBusca = mysqli_real_escape_string($conexao, $_GET['campoDaBusca']);
        $comparador = 'igual a';
        $textoDaBusca = mysqli_real_escape_string($conexao, $_GET['textoDaBusca']);

        if (!$campoDaBusca or $campoDaBusca == '' or !$textoDaBusca or $textoDaBusca == '') {
            // ToDo
        }

        $sql = "SELECT * FROM usuarios WHERE $campoDaBusca = '$textoDaBusca' ORDER BY '$campoDaBusca' ASC";
    }

    $result = $conexao->query($sql);
?>

<?php include $_SERVER['DOCUMENT_ROOT']."/agendamento/includes/header.php"; ?>

<div class="box mb-3">
    <form action="./index.php" method="GET">

        <div class="row mb-3">
            <div class="col">
                <label 
                    class="form-label"
                    for="campoDaBusca"
                >
                    Campo
                </label>
                <select
                    class="form-select"
                    id="campoDaBusca"
                    name="campoDaBusca"
                >
                    <option
                        value=""
                    />

                    <option
                        value="Nome"
                    >
                        Nome
                    </option>

                    <option
                        value="Email"
                    >
                        Email
                    </option>

                    <option
                        value="Telefone"
                    >
                        Telefone
                    </option>
                </select>
            </div>
            <div class="col">
                <label class="form-label" for="textoDaBusca">Valor</label>
                <input 
                    type="text"
                    id="textoDaBusca"
                    name="textoDaBusca"
                    class="form-control"
                >
            </div>
        </div>

        <div class="text-end">
            <button type="submit" class="btn btn-primary mb-3"> Filtrar </button>
            <a href="./index.php" class="btn btn-secondary mb-3"> Limpar </a>
        </div>  
    </form>
</div>

<div class="btn-group mb-3" role="group" aria-label="Toolbar">
    <a href="./manter.php" class="btn btn-primary"> Novo usuário </a>

    <!-- ToDo: add d-print-none classes -->
    <button class="btn btn-secondary" onClick="window.print()">Imprimir</button>
</div>

<table class="table text-white table-bg">
    <thead>
        <tr>
            <th>Ativo</th>
            <!-- <th>id</th> -->
            <th>Nível</th>
            <th>Nome</th>
            <th>Email</th>
            <th>Telefone</th>
            <th></th>
        </tr>
    </thead>

    <tbody>
        <?php
            while($user_data = mysqli_fetch_assoc($result)) {

            $id = $user_data['id'];
        ?>
            <tr>
                <td>
                    <?php if($user_data['ativo']) {?>
                        <svg data-toggle="tooltip" data-placement="top" title="O usuário está ativo" class="text-success" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-lg" viewBox="0 0 16 16">
                            <path d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425a.247.247 0 0 1 .02-.022Z"/>
                        </svg>
                    <?php } ?>
                </td>
                <!-- <td><?php echo $user_data['id'];?></td> -->
                <td><?php echo formatarNivelDeAcesso($user_data['nivel']);?></td>
                <td><?php echo $user_data['nome'];?></td>
                <td><?php echo $user_data['email'];?></td>
                <td><?php echo $user_data['telefone'];?></td>
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
                        onclick="return confirm('Tem certeza que deseja excluir esse usuário?')"
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