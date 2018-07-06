<?php 

class Funcoes
{
	function gerarNomeArquivo($arquivo){
		// criptografa em md5
		$arquivo = md5($arquivo);
		// Deixa apenas a parte inicial da criptografia
		$arquivo = substr($arquivo, 0, 10);
		// retornar nome do arquivo
		return $arquivo;
	}
}	