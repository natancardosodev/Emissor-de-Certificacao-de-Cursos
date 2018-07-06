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

  <div class="row jumbotron mx-auto d-block text-center">
    <h1 class="display-2">Certificados</h1>
    <hr class="my-4">
    <h3 class="display-4">Cursos e eventos</h3>
  </div>
  
  <div class="row justify-content-center">
    <div class="col-md-6">
<?php foreach($cursos as $curso): ?>
      <h2>Selecionado: <?php echo $cursoSigla; ?> <a class="btn btn-info btn-sm" href="index.php" target="_self" role="button"><i class="fas fa-sync"></i> Trocar</a></h2>
        <div style="margin: 2em 0">
          <hr> 
        </div>
      <form class="form-horizontal" action="gerar/modelo<?=$curso->modelo?>.php" method="post" id="geraCertificado">
        <fieldset>
          <h2>Emissão de certificados</h2>
          <div class="form-group" style="margin-bottom: 30px; margin-left: 0px; margin-right: 0px">
            <label class="control-label" for="cpf">Digite abaixo os números de seu CPF:</label>
            <div class="input-group">
              <span class="input-group-addon"><i class="glyphicon glyphicon-barcode"></i></span>
              <input name="cpf" id="cpf" placeholder="CPF" class="form-control" type="text" maxlength="14" onkeypress="formatar('###.###.###-##', this);" style="height: 45px;">
              <div class="input-group-addon"><button class="btn btn-danger btn-sm" onclick="limparCpf()">Limpar</button></div>
            </div>
              <input type="hidden" name="curso_nome" value="<?=$curso->curso_nome?>">
          </div>
          <div class="form-group" style="margin-left: 0px; margin-right: 0px">
            <button type="submit" id="btnLoad" data-loading-text="Processando..." class="btn btn-primary btn-lg float-right">Emitir <i class="fas fa-check-circle"></i></button>
          </div>
        </fieldset>
      </form>
        <div style="margin: 2em 0">
          <hr> 
        </div>
      <form class="form-horizontal" action="gerar/validacao.php" method="post" id="validaCertificado">
        <div class="form-group">
          <h2>Validação de certificado</h2>
          <label for="codigo" class="control-label">Digite abaixo o código do certificado para verificar sua autenticidade:</label>
          <div class="input-group">
            <input name="codigo" id="codigo" placeholder="Código de certificação" class="form-control" type="text" maxlength="10">
            <input type="hidden" name="curso_nome" value="<?=$curso->curso_nome?>">
            <input type="hidden" name="sigla" value="<?=$cursoSigla?>">
          </div>  
        </div>
        <div class="form-group" style="margin-left: 0px; margin-right: 0px">
          <button type="submit" id="btnLoad" data-loading-text="Processando..." class="btn btn-primary btn-lg float-right">Validar <i class="fas fa-check-circle"></i></button>
        </div>
      </form>
<?php endforeach; ?>
    </div>
  </div>
</div>
<?php require_once("class/footer.php");
