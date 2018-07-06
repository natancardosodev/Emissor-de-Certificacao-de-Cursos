# Emissor de Certificação de Cursos e Eventos (EmiCeC)

# Descrição
EmiCeC é um gerenciador que emite certificados em .pdf de um ou mais cursos ou eventos, com possibilidade de visualizar a lista de todos os certificados associados a um CPF e poder abrir ou baixar (fazer o download). Usando técnicas de segurança para proteção dos arquivos gerados, do banco de dados e muito mais. 

O grande benefício desse sistema é que o usuário é quem emitirá o seu próprio certificado. Desta forma, a instituição que realiza o curso ou evento apenas alimentará o banco de dados com os dados dos alunos. Não tendo que gerar todos os certificados e hospedar no sistema para os alunos buscarem. Além disso há um script para fazer a limpeza dos itens antigos, configurados para apagar os com mais de 45 dias.

A vantagem deste sistema é que ele pode emitir certificados de diversos tipos de cursos para a mesma pessoa, contudo por vez. Mas para um mesmo curso ou evento ele pode gerar diversos tipos de certificado exibindo numa lista só. Isso é muito importante para eventos acadêmicos, no qual uma mesma pessoa recebe mais de um certificado.

# Detalhes do sistema
Este sistema é bantante moldável e pode ser adaptado para sua melhor necessidade. Ele utiliza da [FPDF Library](http://www.fpdf.org/), uma biblioteca para PHP que gera os arquivos em pdf. O desenvolvedor pode criar novas soluções implementando com a documentação. O sistema utiliza ainda o framework Twitter Bootstrap 4 e jQuery.

O modelo inserido como demonstração utiliza em sua imagem de fundo um cabeçalho com o título do curso, logotipo da(s) instituição(ões) que realiza(ram) o curso ou evento, além do título certificado. No corpo do certificado vem o termo de certificação, o qual tem partes em comum a todos os tipos de certificado para aquele determinado curso e variáveis que são inseridas de acordo com o banco de dados. No rodapé é inserido as assinaturas dos organizadores pela certificação. 

É ideal que um profissional em design gráfico ou afins elabore o modelo gráfico para o plano de fundo. Uma dica é colocar algum elemento gráfico por detrás da assinatura para dificultar a digitalização da mesma.

# Requisitos (Versões mínimas)
* Apache 2.0.63 
* PHP 5.3.2
* MySQL Server 5.1.44 ou MariaDB 5.5

# Banco de Dados
Deve ser criado uma tabela para armazenar os dados básicos de cada curso/evento, para quando for inserido ou usado a tabela de um curso, colocar apenas o código do curso, que já será inserido por default. Então, através de um relacionamento de tabelas, com INNER JOIN é possível captar os dados de ambas tabelas em um única consulta sql.
```sql
CREATE DATABASE IF NOT EXISTS sistema;
    use sistema;
CREATE TABLE tbl_curso(
    codigo INT AUTO_INCREMENT,
    modelo VARCHAR(2) NOT NULL,
    curso_nome VARCHAR(10) NOT NULL,
    sigla VARCHAR(10) NOT NULL,
    curso_titulo VARCHAR(80) NOT NULL,
    certificadora VARCHAR(200),
    datas VARCHAR(30),
    local VARCHAR(100),
    cidade VARCHAR(40),
    fundo VARCHAR(30),
    setX FLOAT,
    setY FLOAT,
    setW FLOAT,
    setH FLOAT,
    PRIMARY KEY(codigo)
);
```
Modelo é representando por uma ou duas letras que indicará qual modelo será usado, o qual é o modelo (template) em php para emissão. Diversos tipos de cursos/eventos podem usar o mesmo modelo. As características de seu posicionamento são configurados nos campos: setX, setY, setW, setH, que são referenciados para a biblioteca FPDF. setX é a distância em milímetros da esquerda até começar o bloco de texto, já o setY, do topo até alcançar o bloco de texto. setW é a largura deste bloco, e SetH a altura.

Curso_nome é um nome curto da sigla, sem espaços, para ser usado em parte do nome da tabela. curso_titulo é o título por extenso, assim como os demais itens. fundo é o nome do arquivo de imagem que fará o fundo do certificado. Não será necessário esse campo, a não ser que seja feito um sistema para alterar e cadastrar cursos, e então alterar o fundo.

Esta imagem de fundo deve ser feita por um designer gráfico, contendo no cabeçalho o logotipo do evento e dos certificadores, e o termo 'Certificado'. No rodapé deve haver as assinaturas dos organizadores do evento ou coordenadores do curso.

```sql
CREATE TABLE tbl_evento1(
    id INT AUTO_INCREMENT,
    cpf VARCHAR(11) NOT NULL,
    nome VARCHAR(60) NOT NULL,
    termo VARCHAR(60),
    titulo VARCHAR(260),
    cargaHoraria VARCHAR(40),
    alteracao CHAR(1) DEFAULT 'N',
    validacao CHAR(10),
    curso_id INT DEFAULT '1',
    PRIMARY KEY(id),
    FOREIGN KEY (curso_id)
    REFERENCES tbl_curso(codigo)
);
```
Para o registro de cada certificado de um curso/evento é usado uma tabela especifica para a mesma, contendo apenas os dados do certificado. Termo, titulo e carga_horaria deve ser alinhada junto com a comissão organizadora do evento ou coordenação do curso, quais termos utilizar. Pois num evento acadêmico, pode haver diversas atividades, como palestras, minicursos e apresentação de trabalhos, e cada uma tem uma forma de registrar diferente.

# Acesso ao sistema
Insira alguns dados nas tabelas, e então acesse o sistema em localhost/certifica e se inserir este script abaixo no seu banco de dados, coloque no campo CPF, o seguinte número: 72807630065. Por questão de segurança ao fazer deploy, implantação em servidor externo, altere o arquivo de configuração de banco de dados e coloque um usuário que tenha acesso apenas para leitura e coloque uma senha forte. Retire também o arquivo de scripts do banco de dados do servidor.

Exemplo de script SQL:
```sql
use sistema;
INSERT INTO `tbl_curso` (`codigo`, `modelo`, `curso_nome`, `sigla`, `curso_titulo`, `certificadora`, `datas`, `local`, `cidade`, `fundo`, `setX`, `setY`, `setW`, `setH`) VALUES
(1, 'A', 'evento1', 'I Evento', 'I Simpósio Siscrano de tal', 'pela empresa fulana de tal e pela instituição fulana', 'XX a XX de junho de 2018', 'Nome do Local', 'João Pessoa, 15 de maio de 2018', 'evento1.jpg', 22, 85, 252.5, 8);
INSERT INTO `tbl_evento1` (`id`, `cpf`, `nome`, `termo`, `titulo`, `cargaHoraria`, `alteracao`, `validacao`, `curso_id`) VALUES
(1, '72807630065', 'Natan Cardoso', 'participou na condição de MONITOR(A)', '', ', com carga horária de 40 horas', 'N', '', 1),
(2, '72807630065', 'Natan Cardoso', 'participou como ouvinte do minicurso:', 'Criando um emissor de certificados', ', com carga horária de 6 horas', 'S', '', 1);
```

# Sugestões de implementação associada
Pode ser feito um gerenciador de certificados, um CRUD. Com isso a instituição não precisaria sempre que necessitasse de um novo modelo ou mesmo editar um existente solicitar um desenvolvedor. Pois teria um gerenciador com uma interface amigável que exibiria a lista de cursos e eventos, podendo adicionar, excluir e editar os termos de certificação, nome do curso e imagem de fundo. 

O nome do curso citado além do termo por extenso, precisa da abreviação, pois esta que será utilizada como variável ao longo do sistema para a associar a este determinado curso. No momento está sendo inserido dinamicamente com variáveis, mas o ideal é sendo recuperado através do banco de dados. Obs.: Não precisaria de um desenvolvedor, no caso citado da manutenção, se todos os modelos desta instituição seguisse o mesmo padrão de posicionamento de itens, precisando apenas alterar os dados. 

Outra ideia é ter um gerenciador para os dados podendo exibir e buscar nas tabelas do banco de dados com filtros, barra de pesquisa e a posssibilidade de realizar as operações básicas num banco de dados. Entre elas, além do cadastro simples, poder inserir dados em lote, convertidos de uma planilha.

> Obs.: Esta documentação está incompleta, mas será atualizada em breve.