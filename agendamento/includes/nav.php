<?php 
    if(isset($_SESSION['usuario_id'])) { 
        $usuario_id = $_SESSION['usuario_id'];
    }
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
  <div class="container-fluid">
    <a class="navbar-brand" href="/agendamento/modules/home/index.php">Agendamento</a>

    <button
        class="navbar-toggler"
        type="button"
        data-bs-toggle="collapse"
        data-bs-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent"
        aria-expanded="false"
        aria-label="Toggle navigation"
    >
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">

            <?php if ($_SESSION['usuario_nivel'] == 1) { ?>
                <li class="nav-item">
                    <a class="nav-link" href="/agendamento/modules/usuarios/index.php">Usuários</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/agendamento/modules/salas/index.php">Salas</a>
                </li>
            
                <li class="nav-item">
                    <a class="nav-link" href="/agendamento/modules/consultas/index.php">Consultas</a>
                </li>
            <?php } ?>

            <?php if ($_SESSION['usuario_nivel'] == 2 || $_SESSION['usuario_nivel'] == 3) { ?>
                <li class="nav-item">
                    <a class="nav-link" href="/agendamento/modules/consultas/manter.php">Agendar consulta</a>
                </li>
            <?php } ?>

            <?php if ($_SESSION['usuario_nivel'] == 2 || $_SESSION['usuario_nivel'] == 3) { ?>
                <li class="nav-item">
                    <a class="nav-link" href="/agendamento/modules/consultas/index.php">Minhas consultas</a>
                </li>
            <?php } ?>

            <li class="nav-item">
                <a class="nav-link" href=<?php echo "/agendamento/modules/usuarios/manter.php?id=$usuario_id"; ?>>Minha conta</a>
            </li>

            <li class="nav-item">
                <a class="nav-link active btn btn-danger me-5" href="/agendamento/modules/login/sair.php"> Sair </a>
            </li>
        </ul>
    </div>
  </div>
</nav>


