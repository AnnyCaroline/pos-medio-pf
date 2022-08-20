<?php
    include_once($_SERVER['DOCUMENT_ROOT']."/agendamento/includes/utils.php");
    session_start_if_necessary();
    permitir_usuarios_com_niveis([1,2,3]);

    $idUsuarioLogado = $_SESSION['usuario_id'];
    $id = null;
    $edicao = false;

    include_once($_SERVER['DOCUMENT_ROOT'].'/agendamento/common/conexaoBd.php');
    
    if (isset($_GET['id'])) {
        $edicao = true;
        $id = mysqli_real_escape_string($conexao, $_GET['id']);
    }

    if ($idUsuarioLogado == $id) {
        permitir_usuarios_com_niveis([1,2,3]);
    } else {
        permitir_usuarios_com_niveis([1]);
    }

    $nome = "";
    $email = "";
    $senha = "";
    $telefone = "";
    $nivel = "";
    $ativo = null;


    if (isset($_POST['submit'])) {
        if (isset($_POST['senha']) && isset($_POST['senha2']) ) {
            $senha = mysqli_real_escape_string($conexao, $_POST['senha']);
            $senha2 = mysqli_real_escape_string($conexao, $_POST['senha2']);
            $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

            if(strcmp($senha, $senha2) == 0) {
                $sql = "UPDATE usuarios SET senha='$senhaHash' WHERE id=$id";
                $result = mysqli_query($conexao, $sql);
                
                if ($idUsuarioLogado != $id) {
                    header('Location: index.php');
                }
            } else {
                $error_msg = 'As senhas são diferentes';
            }
        } else {
            $nome = mysqli_real_escape_string($conexao, $_POST['nome']);
            $email = mysqli_real_escape_string($conexao, $_POST['email']);
            $senha = mysqli_real_escape_string($conexao, $_POST['senha']);
            $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
            $telefone = mysqli_real_escape_string($conexao, $_POST['telefone']);
            $nivel = mysqli_real_escape_string($conexao, $_POST['nivel']);
            $ativo = mysqli_real_escape_string($conexao, $_POST['ativo']);

            if ($edicao) {
                $sql = "UPDATE usuarios SET nome='$nome', email='$email', senha='$senhaHash', telefone='$telefone', nivel=$nivel, ativo=$ativo WHERE id=$id";
            } else {
                $sql = "INSERT INTO usuarios(nome,email,senha,telefone,nivel,ativo) VALUES ('$nome','$email','$senhaHash','$telefone','$nivel', '$ativo')";
            }

            $result = mysqli_query($conexao, $sql);

            if ($idUsuarioLogado != $id) {
                header('Location: index.php');
            }
        }
    } else if ($edicao) {
        $sql = "SELECT * FROM usuarios WHERE id=$id";

        $result = mysqli_query($conexao, $sql);
        $user_data = mysqli_fetch_assoc($result);

        $nome = $user_data['nome'];
        $email = $user_data['email'];
        $telefone = $user_data['telefone'];
        $nivel = $user_data['nivel'];
        $ativo = $user_data['ativo'];
    }
?>

<?php
    $queryParam = "";
    if (isset($id)) {
        $queryParam = "?id=$id;";
    }
?>

<?php include $_SERVER['DOCUMENT_ROOT']."/agendamento/includes/header.php"; ?>

<div class="mb-3">
    <?php if ($idUsuarioLogado == $id) { ?>
        <h1> Minha conta </h1>
    <?php } else if ($edicao) { ?>
        <h1> Editar usuário </h1>
    <?php } else { ?>
        <h1> Novo usuário </h1>
    <?php } ?>

    <a href="index.php">Voltar</a>
</div>

<form class="needs-validation" novalidate action=<?php echo "manter.php".$queryParam; ?> method="POST">
    <div class="mb-3">
        <label class="form-label" for="nome">Nome completo</label>
        <input class="form-control" type="text" value=<?php echo "\"$nome\""; ?> name="nome" id="nome" required>
    </div>

    <div class="mb-3">
        <label class="form-label" for="email">Email</label>
        <input class="form-control" type="email" value=<?php echo "\"$email\""; ?> name="email" id="email" required>
    </div>

    <?php if (!$edicao) { ?> 
        <div class="mb-3">
            <label class="form-label" for="senha">Senha</label>
            <input class="form-control" type="password" value=<?php echo "\"$senha\""; ?> name="senha" id="senha" required>
        </div>
        <div class="mb-3">
            <label class="form-label" for="senha2">Digite a senha novamente</label>
            <input class="form-control" type="password" value=<?php echo "\"$senha2\""; ?> name="senha2" id="senha2" required>
        </div>
    <?php } ?>

    <?php $telefone = $telefone;?>

    <div class="mb-3">
        <label class="form-label" for="telefone">Telefone</label>
        <input 
            type="text"
            class="form-control"
            value=<?php echo "\"$telefone\""; ?>
            name="telefone"
            id="telefone"
            class="inputUser"
            data-mask="(00) 00000-0000"
        >
    </div>

    <div class="mb-3">
        <label class="form-label" for="nivel">Nível</label>
        <select required class="form-control" name="nivel" id="nivel">
            <option
                value="1"
                <?php if(empty($nivel)) echo 'selected'; ?>
            >
                
            </option>
            <option
                value="1"
                <?php if($nivel == '1') echo 'selected'; ?>
            >
                <?php echo formatarNivelDeAcesso("1"); ?>
            </option>
            <option
                value="2"
                <?php if($nivel == '2') echo 'selected'; ?>
            >
                <?php echo formatarNivelDeAcesso("2"); ?>
            </option>
            <option
                value="3"
                <?php if($nivel == '3') echo 'selected'; ?>
            >
                <?php echo formatarNivelDeAcesso("3"); ?>
            </option>
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Status</label>

        <div class="form-check">
            <input 
                class="form-check-input"
                type="radio"
                id="ativo"
                value="1"
                name="ativo"
                required
                <?php if ($ativo == "1") {echo 'checked';}  ?>
            >
            <label class="form-check-label" for="ativo">
                Ativo
            </label>
        </div>

        <div class="form-check">
            <input 
                class="form-check-input"
                type="radio"
                id="inativo"
                name="ativo"
                value="0"
                <?php if ($ativo == "0") {echo 'checked';}  ?>
            >
            <label class="form-check-label" for="inativo">
                Inativo
            </label>
        </div>
    </div>
    
    <input class="btn btn-primary w-100 mb-3" type="submit" name="submit" id="submit">
</form>

<?php if ($edicao) { ?> 
    <h2 class="mt-5"> Redefinir senha </h2>

    <?php if (isset($error_msg)) { ?>
        <div class="alert alert-danger" role="alert">
            <?php
                echo $error_msg; 
                unset($error_msg);
            ?>
        </div>
    <?php } ?>

    <form action="manter.php?id=<?php echo $id;?>" method="POST">   
        <div class="mb-3">
            <label class="form-label" for="senha">Senha</label>
            <input class="form-control" type="password" id="senha" name="senha" required>
        </div>

        <div class="mb-3">
            <label class="form-label" for="senha2">Digite a senha novamente</label>
            <input class="form-control" id="senha2" name="senha2" type="password" required />
        </div>

        <button class="btn btn-primary" type="submit" name="submit"> Salvar </button>
    </form>


    <h2 class="mt-5 text-danger"> Deletar conta </h2>

    <p>
        Atenção! Essa ação é irreversível.
    </p>

    <a
        title="Deletar"
        class="btn btn-sm btn-danger mb-5"
        <?php if ($idUsuarioLogado == $id) { ?>
            onclick="return confirm('Tem certeza que deseja excluir SEU usuário? Você será deslogado automáticamente e não terá mais acesso ao sistema.')"
        <?php } else { ?>
            onclick="return confirm('Tem certeza que deseja excluir esse usuário?')"
        <?php } ?>
        href=<?php echo "excluir.php?id=$id"; ?>
    >
        Excluir
    </a>
<?php } ?>

<?php include $_SERVER['DOCUMENT_ROOT']."/agendamento/includes/footer.php"; ?>