<?php
    include_once($_SERVER['DOCUMENT_ROOT']."/agendamento/includes/utils.php");
    session_start_if_necessary();
    permitir_usuarios_com_niveis([1,2]);

    $error_msg = null;
    $nome = "";

    $edicao = false;
    if (isset($_GET['id'])) {
        $edicao = true;
        $id = mysqli_real_escape_string($conexao, $_GET['id']);
    }
    
    include_once($_SERVER['DOCUMENT_ROOT'].'/agendamento/common/conexaoBd.php');

    if (isset($_POST['submit'])) {
        $nome = mysqli_real_escape_string($conexao, $_POST['nome']);

        if (empty(trim($nome))) {
            $error_msg = "Digite uma sala";
        } else {
            if ($edicao) {
                $sql = "UPDATE salas SET nome='$nome' WHERE id=$id";
            } else {
                $sql = "INSERT INTO salas SET nome='$nome'";
            }
    
            $sala = null;
    
            try {
                $result = mysqli_query($conexao, $sql);
                header('Location: index.php');
            } catch (Exception $e) {
                $error_msg = $e->getMessage();
    
                if (str_contains($error_msg, 'Duplicate')) { 
                    $error_msg = "Já existe uma sala com este nome";
                }
            }
        }
    } else {
        if ($edicao) {
            $sql = "SELECT id, nome FROM salas WHERE id=$id";
    
            $result = mysqli_query($conexao, $sql);
            $sala = mysqli_fetch_assoc($result);
    
            $nome = $sala['nome'];
        }
    }
?>

<?php include $_SERVER['DOCUMENT_ROOT']."/agendamento/includes/header.php"; ?>

<div class="mb-3">
    <?php if ($edicao) { ?>
        <h1> Edição de sala </h1>
    <?php } else { ?>
        <h1> Criação de sala </h1>
    <?php } ?>

    <a href="index.php">Voltar</a>
</div>

<?php if ($error_msg) { ?>
    <div class="alert alert-danger" role="alert">
        <?php
            echo $error_msg; 
        ?>
    </div>
<?php } ?>

<?php
    $queryParam = "";
    if (isset($id)) {
        $queryParam = "?id=$id;";
    }
?>

<form class="needs-validation" novalidate action=<?php echo "manter.php".$queryParam; ?> method="POST">
    <div class="mb-3">
        <label class="form-label" for="nome">Sala</label>
        <input maxlength="20" class="form-control" type="text"
        value=<?php echo"\"$nome\"";?> name="nome" id="nome" required>
    </div>
    
    <input class="btn btn-primary w-100 mb-3" type="submit" name="submit" id="submit">
</form>

<?php include $_SERVER['DOCUMENT_ROOT']."/agendamento/includes/footer.php"; ?>