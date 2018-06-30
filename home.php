<?php
$date = strtotime('-45 day', time());
// tipo limpeza de cache do servidor
// deleta arquivos com mais de 45 dias
foreach(glob('arquivos/*.pdf') as $file){
  $filetime = filemtime($file);
  if( $date > $filetime ){
    unlink($file);
  }
}
require_once("class/class.user.php");
  $cursoSigla = (isset($_POST['curso'])) ? $_POST['curso'] : '';
  $tbl_cursos = new USER();
  $stmt = $tbl_cursos->runQuery("SELECT modelo, curso_nome "
  . "FROM tbl_curso WHERE sigla = '{$cursoSigla}'");
  $stmt->execute();
  $cursos = $stmt->fetchAll(PDO::FETCH_OBJ);

$tituloPagina = "Emissão de certificados do ".$cursoSigla;
require_once("class/header.php");
?>
<div class="container-fluid">

  <div class="row jumbotron">
    <h1 class="display-3">Emissão de certificados</h1>
    <hr class="my-4">
  </div>
  
  <div class="row justify-content-center">
    <div class="col-md-6">
<?php foreach($cursos as $curso):?>
      <form class="form-horizontal" action="gerar/modelo<?=$curso->modelo?>.php" method="post" id="geraCertificado">
        <fieldset>
          <p><strong>Evento/curso selecionado: </strong><?php echo $cursoSigla; ?></p>
          <div class="form-group" style="margin-bottom: 30px; margin-left: 0px; margin-right: 0px">
            <label class="control-label" for="cpf">CPF</label>
            <div class="input-group">
              <span class="input-group-addon"><i class="glyphicon glyphicon-barcode"></i></span>
              <input name="cpf" id="cpf" placeholder="Informe o CPF" class="form-control" type="text" maxlength="14" onkeypress="formatar('###.###.###-##', this);" style="height: 45px;">
              <div class="input-group-addon"><button class="btn btn-danger btn-sm" onclick="limparCpf()">Limpar</button></div>
            </div>
              <input type="hidden" name="curso_nome" value="<?=$curso->curso_nome?>">
          </div>
          <div class="form-group" style="margin-left: 0px; margin-right: 0px">
            <a class="btn btn-info" href="index.php" target="_self" role="button"><span class="glyphicon glyphicon-chevron-left"></span> Trocar evento/curso</a>
            <button type="submit" id="btnLoad" data-loading-text="Processando..." class="btn btn-primary btn-lg float-right">Emitir Certificado(s) <span class="glyphicon glyphicon-download-alt"></span></button>
          </div>
        </fieldset>
      </form>
<?php endforeach; ?>
    </div>
  </div>
</div>
<?php require_once("class/footer.php");
