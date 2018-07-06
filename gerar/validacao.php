<?php
require('../class/class.user.php');

if (isset($_REQUEST['codigo']) && $_REQUEST['codigo'])
{
    $curso_nome = (isset($_POST['curso_nome'])) ? $_POST['curso_nome'] : '';
    $codigo     = (isset($_POST['codigo'])) ? $_POST['codigo'] : '';
    $sigla      = (isset($_POST['sigla'])) ? $_POST['sigla'] : '';

	$tab_alunos = new USER();
	$sql = "SELECT `sistema`.`tbl_{$curso_nome}`.*,
		`sistema`.`tbl_curso`.* FROM `tbl_{$curso_nome}`
		INNER JOIN `tbl_curso` 
		ON `tbl_{$curso_nome}`.`curso_id` = `tbl_curso`.`codigo`
        WHERE validacao LIKE :codigo";

	$stmt = $tab_alunos->runQuery($sql);
	$stmt->bindValue(':codigo', $codigo.'%');
	$stmt->execute();
	$alunos = $stmt->fetchAll(PDO::FETCH_OBJ);
}

$tituloPagina = "Validação de certificados do ".$sigla;
require_once("../class/header.php");
?>
<div class="container-fluid">
	<div class="row">
		<div class="col-md-12" style="margin-top: 15px">
        <?php if(!empty($alunos)): 
    foreach ($alunos as $aluno)
    { ?>
<div class="alert alert-success" role="alert">
  <h2 class="alert-heading">Validado!</h2>
  <hr>
  <p><strong>Nome: </strong><?=$aluno->nome?></p>
  <p><strong>Curso/Evento: </strong><?=$aluno->curso_titulo?></p>
  <p><strong>Data: </strong><?=$aluno->datas?></p>
  <p><strong>Termo: </strong><?=$aluno->termo.$aluno->titulo?></p>
  <p><strong>Carga horária: </strong><?php $ch=$aluno->cargaHoraria; echo substr($ch,23); ?></p>

  <a class="btn btn-info float-right" href="../index.php" target="_self" role="button"><i class="fas fa-sync"></i> Voltar a página inicial dos certificados</a>
</div>            
    <?php } else: ?>
<div class="alert alert-danger" role="alert">
  <h2 class="alert-heading">Não validado!</h2>
  <hr>
  <p>Não foi encontrado certificado de <?php echo $sigla; ?> com essse código: <?php echo $codigo;?>.</p>

  <a class="btn btn-info float-right" href="../index.php" target="_self" role="button"><i class="fas fa-sync"></i> Voltar a página inicial dos certificados</a>
</div> 
    <?php endif; 
require_once("../class/footer.php"); ?>