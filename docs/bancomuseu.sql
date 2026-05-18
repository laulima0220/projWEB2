-- Remove o schema se existir
DROP SCHEMA IF EXISTS `museu_poemas`;

-- Cria o schema novamente
CREATE SCHEMA IF NOT EXISTS `museu_poemas` DEFAULT CHARACTER SET utf8;
USE `museu_poemas`;

-- Remove tabelas caso existam (ordem importa por causa das FK)
DROP TABLE IF EXISTS `Favorito`;
DROP TABLE IF EXISTS `Poema`;
DROP TABLE IF EXISTS `Usuario`;
DROP TABLE IF EXISTS `Autor`;
DROP TABLE IF EXISTS `Categoria`;

-- Criação da tabela Categoria
CREATE TABLE IF NOT EXISTS `Categoria` (
  `idCategoria` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nomeCategoria` VARCHAR(64) NOT NULL,
  `descricao` VARCHAR(255) NULL,
  PRIMARY KEY (`idCategoria`),
  UNIQUE INDEX `idCategoria_UNIQUE` (`idCategoria` ASC),
  UNIQUE INDEX `nomeCbiografiaategoria_UNIQUE` (`nomeCategoria` ASC)
) ENGINE = InnoDB;

-- Criação da tabela Autor
CREATE TABLE IF NOT EXISTS `Autor` (
  `idAutor` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nomeAutor` VARCHAR(128) NOT NULL,
  `nacionalidade` VARCHAR(64) NULL,
  `biografia` TEXT NULL,
  PRIMARY KEY (`idAutor`),
  UNIQUE INDEX `idAutor_UNIQUE` (`idAutor` ASC)
) ENGINE = InnoDB;

-- Criação da tabela Usuario
CREATE TABLE IF NOT EXISTS `Usuario` (
  `idUsuario` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nomeUsuario` VARCHAR(128) NOT NULL,
  `email` VARCHAR(128) NOT NULL,
  `senha` VARCHAR(255) NOT NULL,
  `admin` TINYINT(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`idUsuario`),
  UNIQUE INDEX `idUsuario_UNIQUE` (`idUsuario` ASC),
  UNIQUE INDEX `email_UNIQUE` (`email` ASC)
) ENGINE = InnoDB;

-- Criação da tabela Poema
CREATE TABLE IF NOT EXISTS `Poema` (
  `idPoema` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `titulo` VARCHAR(128) NOT NULL,
  `conteudo` TEXT NOT NULL,
  `anoPublicacao` YEAR NULL,
  `Autor_idAutor` INT UNSIGNED NOT NULL,
  `Categoria_idCategoria` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`idPoema`),
  UNIQUE INDEX `idPoema_UNIQUE` (`idPoema` ASC),
  INDEX `fk_Poema_Autor_idx` (`Autor_idAutor` ASC),
  INDEX `fk_Poema_Categoria_idx` (`Categoria_idCategoria` ASC),
  CONSTRAINT `fk_Poema_Autor`
    FOREIGN KEY (`Autor_idAutor`)
    REFERENCES `Autor` (`idAutor`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Poema_Categoria`
    FOREIGN KEY (`Categoria_idCategoria`)
    REFERENCES `Categoria` (`idCategoria`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
) ENGINE = InnoDB;

-- Criação da tabela Favorito
CREATE TABLE IF NOT EXISTS `Favorito` (
  `idFavorito` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `Usuario_idUsuario` INT UNSIGNED NOT NULL,
  `Poema_idPoema` INT UNSIGNED NOT NULL,
  `dataFavoritado` DATE NOT NULL,
  PRIMARY KEY (`idFavorito`),
  UNIQUE INDEX `idFavorito_UNIQUE` (`idFavorito` ASC),
  UNIQUE INDEX `usuario_poema_UNIQUE` (`Usuario_idUsuario` ASC, `Poema_idPoema` ASC),
  INDEX `fk_Favorito_Usuario_idx` (`Usuario_idUsuario` ASC),
  INDEX `fk_Favorito_Poema_idx` (`Poema_idPoema` ASC),
  CONSTRAINT `fk_Favorito_Usuario`
    FOREIGN KEY (`Usuario_idUsuario`)
    REFERENCES `Usuario` (`idUsuario`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Favorito_Poema`
    FOREIGN KEY (`Poema_idPoema`)
    REFERENCES `Poema` (`idPoema`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
) ENGINE = InnoDB;

-- Inserção de categorias
INSERT INTO `Categoria` (`idCategoria`, `nomeCategoria`, `descricao`) VALUES
(1, 'Lírico', 'Poemas que expressam sentimentos e emoções do eu lírico'),
(2, 'Épico', 'Poemas que narram feitos heroicos e grandes aventuras'),
(3, 'Satírico', 'Poemas que usam ironia e humor para criticar a sociedade'),
(4, 'Romântico', 'Poemas sobre amor, natureza e idealismo'),
(5, 'Modernista', 'Poemas com linguagem coloquial e ruptura com as formas clássicas');

-- Inserção de autores
INSERT INTO `Autor` (`idAutor`, `nomeAutor`, `nacionalidade`, `biografia`) VALUES
(1, 'Carlos Drummond de Andrade', 'Brasileiro', 'Um dos maiores poetas da literatura brasileira, nascido em Itabira, Minas Gerais, em 1902.'),
(2, 'Fernando Pessoa', 'Português', 'Poeta português considerado um dos maiores escritores do século XX, criador de heterônimos.'),
(3, 'Cecília Meireles', 'Brasileira', 'Poetisa brasileira modernista, nascida no Rio de Janeiro em 1901, reconhecida pela musicalidade de seus versos.'),
(4, 'Vinícius de Moraes', 'Brasileiro', 'Poeta e compositor carioca, um dos criadores da Bossa Nova e autor de sonetos célebres.'),
(5, 'Manuel Bandeira', 'Brasileiro', 'Poeta pernambucano considerado o "São João Batista do Modernismo" brasileiro.');

-- Inserção de usuários (senha: 1234 com hash bcrypt)
INSERT INTO `Usuario` (`idUsuario`, `nomeUsuario`, `email`, `senha`, `admin`) VALUES
(1, 'admin', 'admin@museupoemas.com', '$2b$12$6ixafy0UKZx.A8ujEEDfnO2QH7IonQ/5/5UCqzQ51YvISdSO4VVle', 1),
(2, 'laura_lima', 'lauris@email.com', '$2b$12$6ixafy0UKZx.A8ujEEDfnO2QH7IonQ/5/5UCqzQ51YvISdSO4VVle', 0),
(3, 'luany_dias', 'pslualua@email.com', '$2b$12$6ixafy0UKZx.A8ujEEDfnO2QH7IonQ/5/5UCqzQ51YvISdSO4VVle', 0),
(4, 'maria_fernanda', 'maria@email.com', '$2b$12$6ixafy0UKZx.A8ujEEDfnO2QH7IonQ/5/5UCqzQ51YvISdSO4VVle', 0),
(5, 'frank_iero', 'frank@email.com', '$2b$12$6ixafy0UKZx.A8ujEEDfnO2QH7IonQ/5/5UCqzQ51YvISdSO4VVle', 0);

-- Inserção de poemas
INSERT INTO `Poema` (`idPoema`, `titulo`, `conteudo`, `anoPublicacao`, `Autor_idAutor`, `Categoria_idCategoria`) VALUES
(1, 'No Meio do Caminho', 'No meio do caminho tinha uma pedra\nhavia uma pedra no meio do caminho\nhavia uma pedra\nno meio do caminho tinha uma pedra.', 1928, 1, 5),
(2, 'José', 'E agora, José?\nA festa acabou,\na luz apagou,\no povo sumiu...', 1942, 1, 5),
(3, 'Autopsicografia', 'O poeta é um fingidor.\nFinge tão completamente\nQue chega a fingir que é dor\nA dor que deveras sente.', 1931, 2, 1),
(4, 'Soneto da Fidelidade', 'De tudo ao meu amor serei atento\nAntes, e com tal zelo, e sempre, e tanto\nQue mesmo em face do maior encanto\nDele se encante mais meu pensamento.', 1954, 4, 4),
(5, 'Pneumotórax', 'Febre, hemoptise, dispneia e suores noturnos.\nA vida inteira que podia ter sido e que não foi.\nTosse, tosse, tosse.', 1930, 5, 1),
(6, 'Romanceiro da Inconfidência', 'Ou você ou a morte,\nMinas, alguma vez:\nE eu fico com Minas\nE com a morte também.', 1953, 3, 2),
(7, 'Tabacaria', 'Não sou nada.\nNunca serei nada.\nNão posso querer ser nada.\nÀ parte isso, tenho em mim todos os sonhos do mundo.', 1933, 2, 1);

-- Inserção de favoritos
INSERT INTO `Favorito` (`Usuario_idUsuario`, `Poema_idPoema`, `dataFavoritado`) VALUES
(2, 1, '2024-03-10'),
(2, 4, '2024-03-12'),
(2, 7, '2024-04-01'),
(3, 3, '2024-02-20'),
(3, 5, '2024-02-25'),
(4, 1, '2024-01-15'),
(4, 2, '2024-01-16'),
(4, 6, '2024-03-05');