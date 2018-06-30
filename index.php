<?php
require_once("class/class.user.php");
  $tbl_cursos = new USER();
  $stmt = $tbl_cursos->runQuery("SELECT sigla FROM tbl_curso "
  . "ORDER BY curso_nome, codigo");
  $stmt->execute();
  $cursos = $stmt->fetchAll(PDO::FETCH_OBJ);

$tituloPagina = "Emissão de certificados";
require_once("class/header.php");
?>
<div class="container-fluid">

  <div class="row jumbotron">
    <h1 class="display-4 text-center">Emissão de certificados</h1>
    <hr class="my-4">
  </div>

  <div class="row justify-content-center">
    <div class="col-md-8">
      <form class="form-horizontal" action="home.php" method="POST">
        <div class="dropdown">
          <button class="btn btn-secondary btn-lg dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Escolha aqui o curso ou evento
          </button>
          <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
        <?php if(!empty($cursos)):
          foreach($cursos as $curso):?>
            <button class="dropdown-item" name="curso" value="<?=$curso->sigla?>" type="submit"><?=$curso->sigla?></button>
          <?php endforeach;
        endif; ?>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<?php require_once("class/footer.php");