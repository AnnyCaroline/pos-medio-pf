<?php
    include_once($_SERVER['DOCUMENT_ROOT']."/agendamento/includes/utils.php");
    session_start_if_necessary();
    permitir_usuarios_com_niveis([1,2,3]);

    $idUsuarioLogado = $_SESSION['usuario_id'];
    $nomeUsuarioLogado = $_SESSION['usuario_nome'];
    $id = null;
    $edicao = false;

    include_once($_SERVER['DOCUMENT_ROOT'].'/agendamento/common/conexaoBd.php');

    $data = "";
    $hora = "";
    $medico = "";
    $paciente = "";
    $sala = "";
    
    if (isset($_GET['id'])) {
        $edicao = true;
        $id = mysqli_real_escape_string($conexao, $_GET['id']);
    }

    function getUsers($conexao) {
        $sql = "SELECT id, nome, nivel FROM usuarios ORDER BY id DESC";

        $result = $conexao->query($sql);
    
        $pacientes = [];
        $medicos = [];
    
        while($user_data = mysqli_fetch_assoc($result)) {
            if ($user_data['nivel'] == "3") {
                array_push($pacientes, $user_data);
            } else if ($user_data['nivel'] == "2") {
                array_push($medicos, $user_data);
            }
        }   
        
        return array($pacientes, $medicos);
    }

    function getSalas($conexao) {
        $sql = "SELECT id, nome FROM salas ORDER BY nome ASC";

        $result = $conexao->query($sql);

        $salas = mysqli_fetch_all($result);
        return $salas;
    }

    list($pacientes, $medicos) = getUsers($conexao);
    $salas = getSalas($conexao);
    
    if (isset($_POST['submit'])) {
        $data = mysqli_real_escape_string($conexao, $_POST['data']);
        $hora = mysqli_real_escape_string($conexao, $_POST['hora']);

        if ($_SESSION['usuario_nivel'] == 1) {
            $sala = mysqli_real_escape_string($conexao, $_POST['sala']);
        }

        if ($_SESSION['usuario_nivel'] == 2) {
            $medico = $idUsuarioLogado;
        } else {
            $medico = mysqli_real_escape_string($conexao, $_POST['medico']);
        }

        if ($_SESSION['usuario_nivel'] == 3) {
            $paciente = $idUsuarioLogado;
        } else {
            $paciente = mysqli_real_escape_string($conexao, $_POST['paciente']);
        }        

        if ($edicao) {
            $sql = "UPDATE consultas SET data='$data', hora='$hora', paciente='$paciente', medico='$medico'";
        } else {
            $sql = "INSERT INTO consultas SET data='$data', hora='$hora', paciente='$paciente', medico='$medico'";
        }
        
        if ($_SESSION['usuario_nivel'] == 1) {
            $sql .= ", sala='$sala'";
        }

        if ($edicao) {
            $sql .= " WHERE id=$id";
        }

        $result = mysqli_query($conexao, $sql);

        header('Location: index.php');
    } else {
        if ($edicao) {
            if ($_SESSION['usuario_nivel'] == 1) {
                $sql = "SELECT data, hora, medico, paciente, sala FROM consultas WHERE id=$id";
            } else {
                $sql = "SELECT data, hora, medico, paciente FROM consultas WHERE id=$id";
            }
    
            $result = mysqli_query($conexao, $sql);
            $consulta = mysqli_fetch_assoc($result);

            $data = $consulta['data'];
            $hora = $consulta['hora'];
            $medico = $consulta['medico'];
            $paciente = $consulta['paciente'];

            if ($_SESSION['usuario_nivel'] == 1) {
                $sala = $consulta['sala'];
            }
        }
    }

    $queryParam = "";
    if (isset($id)) {
        $queryParam = "?id=$id;";
    }
?>

<?php include $_SERVER['DOCUMENT_ROOT']."/agendamento/includes/header.php"; ?>

    <?php if ($edicao) { ?>
        <h1> Editar consulta </h1>
    <?php } else { ?>
        <h1> Agendar consulta </h1>
    <?php } ?>

    <a href="index.php">Voltar</a>

    <form class="needs-validation" novalidate action=<?php echo "manter.php".$queryParam; ?> method="POST">
        <br>
        
        <div class="mb-3">
            <label class="form-label" for="data">Data</label>
            <input class="form-control" type="date" name="data" value=<?php echo"\"$data\"";?> required>
        </div>

        <div class="mb-3">
            <label class="form-label" for="hora">Hora</label>
            <input class="form-control" type="time" name="hora" value=<?php echo"\"$hora\"";?> required>
        </div>

        <div class="mb-3">
            <label class="form-label" for="medico">Médico</label>

            <?php if ($_SESSION['usuario_nivel'] == 2) { ?>
                <input
                    readonly
                    type="text"
                    class="form-control-plaintext text-white"
                    id="medico"
                    value=<?php echo"\"$nomeUsuarioLogado\"";?>
                >
            <?php } else { ?>
                <select
                    class="form-select"
                    id="medico"
                    name="medico"
                    required
                >
                    <option
                        value=""
                    />

                    <?php
                        foreach($medicos as $m) {
                            $id = $m['id'];
                            $nome = $m['nome'];

                            $selected = "";

                            if ($id == $medico) {
                                $selected = "selected";
                            }

                            echo "<option $selected value=$id>$nome</option>";
                        }
                    ?>
                </select>
            <?php } ?>
        </div>

        <div class="mb-3">
            <label class="form-label" for="paciente">Paciente</label>
            
            <?php if ($_SESSION['usuario_nivel'] == 3) { ?>
                <input
                    readonly
                    type="text"
                    class="form-control-plaintext text-white"
                    id="medico"
                    value=<?php echo"\"$nomeUsuarioLogado\"";?>
                >
            <?php } else { ?>
                <select
                    class="form-select"
                    id="paciente"
                    name="paciente"
                    required
                >
                    <option
                        value=""
                    />

                    <?php
                        foreach($pacientes as $p) {
                            $id = $p['id'];
                            $nome = $p['nome'];

                            $selected = "";

                            if ($id == $paciente) {
                                $selected = "selected";
                            }

                            echo "<option $selected value=$id>$nome</option>";
                        }
                    ?>
                </select>
            <?php } ?>
        </div>

        <?php if ($_SESSION['usuario_nivel'] == 1) { ?>
            <div class="mb-3">
                <label class="form-label" for="sala">Sala</label>
                <select
                    class="form-select"
                    id="sala"
                    name="sala"
                >
                    <option
                        value=""
                    />

                    <?php
                        foreach($salas as $s) {
                            $id = $s[0];
                            $nome = $s[1];

                            $selected = "";

                            if ($id == $sala) {
                                $selected = "selected";
                            }

                            echo "<option $selected value=$id>$nome</option>";
                        }
                    ?>
                </select>
            </div>
        <?php } ?>

        <input class="btn btn-primary w-100 mb-3" type="submit" name="submit" id="submit">
    </form>

<?php include $_SERVER['DOCUMENT_ROOT']."/agendamento/includes/footer.php"; ?>