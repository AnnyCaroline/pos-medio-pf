<?php
    $email = "";
    $senha = "";
    $error_msg = null;

    if (isset($_POST['submit'])) {
        session_start();

        include $_SERVER['DOCUMENT_ROOT']."/agendamento/common/conexaoBd.php";

        $email = mysqli_real_escape_string($conexao, $_POST['email']);
        $senha = mysqli_real_escape_string($conexao, $_POST['senha']);

        $sql = "SELECT * FROM usuarios WHERE email = '$email' and ativo=1";

        $result = $conexao->query($sql);

        if(mysqli_num_rows($result) < 1) {
            $error_msg = "Usuário ou senha incorretos";
        } else {
            $row = mysqli_fetch_assoc($result);

            $existingHashFromDb = $row['senha'];
            $senhaCorreta = password_verify($senha, $existingHashFromDb);

            if ($senhaCorreta) {
                $_SESSION['usuario_id'] = $row['id'];
                $_SESSION['usuario_nome'] = $row['nome'];
                $_SESSION['usuario_nivel'] = $row['nivel'];
                unset($_SESSION['error_msg']);

                header("Location: /agendamento/modules/home/index.php");
            } else {
                $error_msg = "Usuário ou senha incorretos";
            }
        }
    }
?>

<?php include $_SERVER['DOCUMENT_ROOT']."/agendamento/includes/header.php"; ?>

<body>
    <div class="box absolute">
        <h1>Agendamento de consultas</h1>

        <?php if ($error_msg) { ?>
            <div class="alert alert-danger" role="alert">
                <?php
                    echo $error_msg;
                ?>
            </div>
        <?php } ?>

        <?php if (isset($_SESSION['account_creation_msg'])) { ?>
            <div class="alert alert-warning" role="alert">
                <?php
                    echo $_SESSION['account_creation_msg']; 
                    unset($_SESSION['account_creation_msg']);
                ?>
            </div>
        <?php } ?>
        
        <form action="/agendamento/modules/login/index.php" method="POST">
            <div class="mb-3">
                <label class="form-label" for="email">Email</label>
                <input
                    class="form-control"
                    type="email"
                    name="email"
                    placeholder="Digite aqui o email"
                    value=<?php echo"\"$email\"";?>
                />
            </div>

            <div class="mb-3">
                <label class="form-label" for="senha">Senha</label>
                <input class="form-control" type="password" name="senha" value=<?php echo"\"$senha\"";?>/>
            </div>

            <input class="btn btn-primary w-100 mb-3" type="submit" name="submit" value="Login" />
        </form> 

        Não tem conta? <a href="/agendamento/modules/usuarios/cadastrar.php">Cadastre-se aqui</a>
    </div>

<?php include $_SERVER['DOCUMENT_ROOT']."/agendamento/includes/footer.php"; ?>