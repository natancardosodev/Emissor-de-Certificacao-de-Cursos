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

  <div class="row jumbotron mx-auto d-block text-center">
    <h1 class="display-2">Certificados</h1>
    <hr class="my-4">
    <h3 class="display-4">Cursos e eventos</h3>
  </div>

  <div class="row mx-auto d-block text-center">
    <div class="col-md-12">
      <form class="form-horizontal" action="home.php" method="POST">
        <h4>Escolha o curso ou evento:</h4>
        <div class="dropdown">
          <button class="btn btn-secondary btn-lg dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Clique aqui para escolher 
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