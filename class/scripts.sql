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
    fundo VARCHAR(30),
    setX FLOAT,
    setY FLOAT,
    setW FLOAT,
    setH FLOAT;
    PRIMARY KEY(id)
);
CREATE TABLE tbl_curso_nome(
    id INT AUTO_INCREMENT,
    cpf VARCHAR(11) NOT NULL,
    nome VARCHAR(60) NOT NULL,
    termo VARCHAR(60),
    titulo VARCHAR(260),
    cargaHoraria VARCHAR(40),
    curso_id INT DEFAULT '1',
    PRIMARY KEY(id),
    FOREIGN KEY (curso_id)
    REFERENCES tbl_curso;
);

INSERT INTO `tbl_curso` (`codigo`, `modelo`, `curso_nome`, `sigla`, `curso_titulo`, `certificadora`, `datas`, `local`, `fundo`, `setX`, `setY`, `setW`, `setH`) VALUES
(1, 'A', 'evento1', 'I Evento', 'I Simpósio Siscrano de tal', 'pela empresa fulana de tal e pela instituição fulana', 'XX a XX de junho de 2018', 'Nome do Local', 'evento1.jpg', 22, 85, 252.5, 8);
INSERT INTO `tbl_curso_nome` (`id`, `cpf`, `nome`, `termo`, `titulo`, `cargaHoraria`, `curso_id`) VALUES
(1, '72807630065', 'Natan Cardoso', 'participou na condição de MONITOR(A)', '', 'com carga horária de 40 horas', 1),
(2, '72807630065', 'Natan Cardoso', 'participou como ouvinte do minicurso:', 'Criando um emissor de certificados', 'com carga horária de 6 horas', 1);