<?php
    include_once($_SERVER['DOCUMENT_ROOT']."/agendamento/includes/utils.php");
    session_start_if_necessary();

    $error_msg = "";

    $nome="";
    $email="";
    $telefone="";
    $senha="";
    $senha2="";
    $nivel="";

    if(isset($_POST['submit'])) {
        include_once($_SERVER['DOCUMENT_ROOT'].'/agendamento/common/conexaoBd.php');

        $senha = mysqli_real_escape_string($conexao, $_POST['senha']);
        $senha2 = mysqli_real_escape_string($conexao, $_POST['senha2']);

        if(strcmp($senha, $senha2) != 0) {
            $error_msg = 'As senhas são diferentes';
        } else {
            $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

            $nome = mysqli_real_escape_string($conexao, $_POST['nome']);
            $email = mysqli_real_escape_string($conexao, $_POST['email']);
            $telefone = mysqli_real_escape_string($conexao, $_POST['telefone']);
            $nivel = mysqli_real_escape_string($conexao, $_POST['nivel']);

            if ($nivel != '2' && $nivel != '3') {
                $error_msg = "Houve um problema ao salvar os dados. Verifique o formulário.";
            } else {
                try {
                    $result = mysqli_query($conexao, "INSERT INTO usuarios(nome,email,senha,telefone,nivel) 
                VALUES ('$nome','$email','$senhaHash','$telefone','$nivel')");

                    $_SESSION['account_creation_msg'] = "Sua conta foi criada com sucesso! Aguarde a aprovação por uma administrador";
                    header('Location: index.php');
                } catch (Exception $e) {
                    $error_msg = $e->getMessage();
        
                    if (str_contains($error_msg, 'Duplicate')) { 
                        $error_msg = "E-mail jé está em uso";
                    }
                }
            }
        }
    }

?>

<?php include $_SERVER['DOCUMENT_ROOT']."/agendamento/includes/header.php"; ?>

<div class="box absolute">
    <h1> Cadastro de usuários </h1>

    <a href="/agendamento/index.php">Voltar</a>

    <?php if ($error_msg) { ?>
        <div class="alert alert-danger mt-3" role="alert">
            <?php
                echo $error_msg;
            ?>
        </div>
    <?php } ?>

    <form class="needs-validation" novalidate action="/agendamento/modules/usuarios/cadastrar.php" method="POST">
        <br>
        
        <div class="mb-3">
            <label class="form-label" for="nome">Nome completo</label>
            <input value=<?php echo"\"$nome\"";?> class="form-control" type="text" name="nome" id="nome" class="inputUser" required>
        </div>
    
        <div class="mb-3">
            <label class="form-label" for="email">Email</label>
            <input value=<?php echo"\"$email\"";?> class="form-control" type="email" name="email" id="email" class="inputUser" required>
        </div>  
    
        <div class="mb-3">
            <label for="senha" class="form-label" for="senha">Senha</label>
            <input value=<?php echo"\"$senha\"";?> class="form-control" type="password" name="senha" id="senha" class="inputUser" required>
        </div>

        <div class="mb-3">
            <label for="senha2" class="form-label" for="senha2">Digite a senha novamente</label>
            <input value=<?php echo"\"$senha2\"";?> class="form-control" type="password" name="senha2" id="senha2" class="inputUser" required>
        </div>
    
        <div class="mb-3">
            <label class="form-label" for="telefone">Telefone</label>
            <input value=<?php echo"\"$telefone\"";?> class="form-control" type="tel" name="telefone" id="telefone" class="inputUser" data-mask="(00) 00000-0000">
        </div>

        <div class="mb-3">
            <label class="form-label" for="nivel">Você é:</label>
            <select class="form-control" id="nivel" name="nivel" required>
                <option
                    value="3"
                    <?php if(empty($nivel) || $nivel == "3") echo 'selected'; ?>
                >
                    <?php echo formatarNivelDeAcesso("3"); ?>
                </option>
                <option
                    value="2"
                    <?php if($nivel == "2") echo 'selected'; ?>
                >
                    <?php echo formatarNivelDeAcesso("2"); ?>
                </option>
            </select>
        </div>
        
        <div class="alert alert-primary" role="alert">
            Você deverá ter sua conta aprovada por um administrador do sistema
        </div>

        <input class="btn btn-primary w-100 mb-3" type="submit" name="submit" id="submit">
    </form>
</div>

<?php include $_SERVER['DOCUMENT_ROOT']."/agendamento/includes/footer.php"; ?>