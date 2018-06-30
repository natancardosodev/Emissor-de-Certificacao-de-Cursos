<?php
setlocale( LC_ALL, 'pt_BR', 'pt_BR.iso-8859-1', 'pt_BR.utf-8', 'portuguese' );
date_default_timezone_set( 'America/Recife' );
ob_start();
require('../class/fpdf/alphapdf.php');
require('../class/class.user.php');
require('../class/functions.php');

if (isset($_REQUEST['cpf']) && $_REQUEST['cpf']) {

	$curso_nome = (isset($_POST['curso_nome'])) ? $_POST['curso_nome'] : '';
    $cpfx = trim($_REQUEST['cpf']);
    $cpf = str_replace(array('.','-'), '', $cpfx);
	$tab_alunos = new USER();
	$sql = "SELECT `sistema`.`tbl_{$curso_nome}`.*,
	`sistema`.`tbl_curso`.* FROM `tbl_{$curso_nome}`
	INNER JOIN `tbl_curso` 
	ON `tbl_{$curso_nome}`.`curso_id` = `tbl_curso`.`codigo`
	WHERE cpf LIKE :cpf
	ORDER BY `tbl_{$curso_nome}`.`titulo` ASC";
	$stmt = $tab_alunos->runQuery($sql);
	$stmt->bindValue(':cpf', $cpf.'%');
	$stmt->execute();
	$alunos = $stmt->fetchAll(PDO::FETCH_OBJ);
}
	$count = $stmt->rowCount();

foreach ($alunos as $aluno) {

$texto = utf8_decode("Certificamos para os devidos fins que ".$aluno->nome.", ".$aluno->termo.$aluno->titulo.", no ".$aluno->sigla." - ".$aluno->curso_titulo.", promovido ".$aluno->certificadora.", realizado nos dias ".$aluno->datas.", no CCHLA/UFPB".$aluno->cargaHoraria.".");

$arquivox = $aluno->cpf."_".$aluno->id;
$arquivox = gerarNomeArquivo($arquivox);
$certificado = "../arquivos/".$arquivox.".pdf";

if (!file_exists($certificado)) {

	$pdf = new AlphaPDF();

	$pdf->AddPage('L');

	$pdf->SetLineWidth(1);

	$pdf->Image("../modelos/$aluno->curso_nome.png",0,0,297);

	$pdf->SetAlpha(1);

	$pdf->SetFont('Arial', '', 14);

	$pdf->SetXY($aluno->setX,$aluno->setY);
	$pdf->MultiCell($aluno->setW, $aluno->setH, $texto, '', 'J', 0);

	$pdf->Output($certificado,'F');
}
$nomeAluno   = $aluno->nome;
$cursoSigla  = $aluno->sigla;
$cursoTitulo = $aluno->curso_titulo;
$cursoDatas  = $aluno->datas;
}//endforeach
ob_end_flush();

$tituloPagina = "Emissão de certificados do ".$cursoSigla;
require_once("../class/header.php");
?>
<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			<div class="jumbotron" style="margin-top: 15px;">
			<?php if(!empty($alunos)): ?>
				<h1>Olá, <?=$nomeAluno?>!</h1>
				<p>Obrigado por ter participado do <?=$cursoSigla?> - <?=$cursoTitulo?> realizado nos dias <?=$cursoDatas?>. Confira abaixo o(s) seu(s) certificado(s). Havendo algum erro, favor entrar em contato.</p>
			<?php else: ?>
				<h1>Desculpe, não foi encontrado certificado com o CPF informado para este curso/evento.</h1>
				<p>Entre em contato conosco para resolvermos seu caso.</p>
			<?php endif; ?>
				<p><a class="btn btn-primary btn-lg" href="http://localhost/contato/" target="_blank" role="button"><span class="glyphicon glyphicon-envelope"></span> Fale Conosco</a>
			 	<a class="btn btn-info btn-lg" href="../index.php" target="_self" role="button"><span class="glyphicon glyphicon-chevron-left"></span> Voltar à página inicial de emissão</a></p>
			</div>
			<legend><h1>Lista de seu(s) certificado(s) do <?=$cursoSigla?></h1></legend>

			<?php if(!empty($alunos)): ?>
			<div class="table-responsive">
				<table class="table table-striped table-hover" id="tabela">
					<tr class='active'>
						<th>Ações com o certificado</th>
						<th>Termo de Certificação</th>
						<th>Título</th>
						<th>Carga Horária</th>
					</tr>
					<?php foreach($alunos as $aluno):
						$arquivo = $aluno->cpf."_".$aluno->id;
						$arquivo = gerarNomeArquivo($arquivo) . ".pdf";
						?>
						<tr>
							<td>
								<a href="../class/baixar.php?arquivo=<?=$arquivo?>" class="btn btn-primary" title="Download" alt="Download"><span class="glyphicon glyphicon-download-alt"></span> Baixar</a>
								
								<button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal<?=$aluno->id?>" title="Abrir" alt="Abrir"> <span class="glyphicon glyphicon-resize-full"></span> Abrir</button>
							</td>
							<td><?=$aluno->termo?></td>
							<td><?=$aluno->titulo?></td>
							<td><?=$aluno->cargaHoraria?></td>
						</tr>
					<!-- Modal -->
					<div class="modal fade bs-example-modal-lg" id="modal<?=$aluno->id?>" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
					  <div class="modal-dialog modal-lg" role="document">
					    <div class="modal-content">
					      <div class="modal-header">
					        <h3 class="modal-title text-center" id="myModalLabel">Certificação de <?=$aluno->nome?></h4>
					        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
					          <span aria-hidden="true">&times;</span>
					        </button>
					      </div>
					      <div class="modal-body">
							<div class="embed-responsive embed-responsive-4by3">
							  <iframe class="embed-responsive-item" src="../arquivos/<?=$arquivo?>"></iframe>
							</div>
					      </div>
					    </div>
					  </div>
					</div>
					<?php endforeach;?>
				</table>
			</div>

			<?php else:
				$error[] = "Desculpe, não foi encontrado nenhum registro.";
			endif;

			if(isset($error))
			{ ?>
				<div class="jumbotron" style="background: none !important">
			 	<?php foreach($error as $error)
			 	{ ?>

                     	<div class="alert alert-danger">
                        	<i class="glyphicon glyphicon-warning-sign"></i> &nbsp; <?php echo $error; ?>
                     	</div>
                <?php } ?>
            	</div>
			<?php }
			?>

<?php require_once("../class/footer.php");