delete from categoria;
delete from categoria_simples;
delete from super_categoria;
delete from constituida;
delete from fornecedor;
delete from produto;
delete from fornece_sec;
delete from corredor;
delete from prateleira;
delete from planograma;
delete from evento_reposicao;
delete from reposicao;

insert into categoria(nome)
    values
    ('Lacticinios'),
    ('Vegan'),
    ('Mercearia'),
    ('Brinquedos'),
    ('Animais'),
    ('Manteigas'),
    ('Queijos'),
    ('Caes'),
    ('Frutas'),
    ('Soja'),
    ('Gatos');

insert into categoria_simples(nome)
    values
    ('Manteigas'),
    ('Queijos'),
    ('Brinquedos'),
    ('Caes'),
    ('Frutas'),
    ('Soja'),
    ('Gatos');

insert into super_categoria(nome)
    values
    ('Vegan'),
    ('Lacticinios'),
    ('Mercearia'),
    ('Animais');

insert into constituida(super_categoria, categoria)
    values
    ('Mercearia', 'Vegan'),
    ('Mercearia', 'Frutas'),
    ('Vegan', 'Soja'),
    ('Lacticinios', 'Manteigas'),
    ('Lacticinios', 'Queijos'),
    ('Lacticinios', 'Mercearia'),
    ('Animais', 'Caes'),
    ('Animais', 'Gatos');

insert into fornecedor(nif, nome)
    values
    (732792012, 'Sorfimacur'),
    (378753913, 'Carmitofar'),
    (674782364, 'Perlamobutan Ltd.'),
    (438043829, 'Azeite Portugues'),
    (567893213, 'Estabon Supplies'),
    (945678431, 'Bontinente'),
    (489723432, 'Beronimo Bartins'),
    (847238654, 'Lar e Casa'),
    (348787423, 'Simplesmente Simples'),
    (192374489, 'Costa e Costa e Costa'),
    (746317931, 'Alfredo e Jacinto Ltd.'),
    (847319321, 'Solesticio De Enverno'),
    (123456789, 'Baia das Margaridas'),
    (987654321, 'Asmecasso'),
    (389754678, 'Poarjhse Ghutern Ltd.'),
    (213989018, 'Colunas e Linhas Ltd.'),
    (761783123, 'BXS');

insert into produto(ean, design, categoria, forn_primario, data)
    values
    (1, 'Terra Vostra', 'Manteigas', 674782364, '1962-05-21'),
    (2, 'Ola', 'Vegan', 489723432, '2017-05-21'),
    (3, 'Forlectibane', 'Caes', 489723432, '2015-02-28'),
    (4, 'Purpura', 'Gatos', 847238654, '2015-03-01'),
    (5, 'Cebel', 'Queijos', 438043829, '2014-07-05'),
    (6, 'Bibosa', 'Lacticinios', 438043829, '2014-07-05'),
    (7, 'Trocatudo', 'Mercearia', 567893213, '2013-01-01'),
    (8, 'Ping pong', 'Brinquedos', 847238654, '2010-01-01'),
    (9, 'Ovos vegan', 'Vegan', 348787423, '2010-10-01'),
    (10, 'Manteiga Fresca', 'Manteigas', 348787423, '2010-10-01'),
    (11, 'Flamengo', 'Queijos', 348787423, '2010-10-01'),
    (12, 'Rato', 'Brinquedos', 348787423, '2010-10-01'),
    (13, 'Biscas Sacolas', 'Caes', 348787423, '2010-10-01'),
    (14, 'Pedigray', 'Gatos', 348787423, '2010-10-01');

insert into fornece_sec(nif, ean)
    values
    (378753913, 5),
    (489723432, 8),
    (732792012, 1),
    (945678431, 4),
    (761783123, 9),
    (213989018, 9),
    (389754678, 9),
    (987654321, 9),
    (123456789, 9),
    (847319321, 9),
    (746317931, 9),
    (192374489, 9),
    (847238654, 9),
    (489723432, 9),
    (378753913, 9),
    (761783123, 10),
    (213989018, 10),
    (389754678, 10),
    (987654321, 10),
    (123456789, 10),
    (847319321, 10),
    (746317931, 10),
    (192374489, 10),
    (847238654, 10),
    (489723432, 10);

insert into corredor(nro, largura)
    values
    (1, 3),
    (3, 8),
    (2, 12),
    (4, 5),
    (5, 8);

insert into prateleira(nro, lado, altura)
    values
    (1, 1, 1),
    (1, 1, 2),
    (1, 1, 3),
    (1, 2, 1),
    (1, 2, 2),
    (1, 2, 3),
    (2, 1, 1),
    (2, 1, 2),
    (2, 1, 3),
    (2, 2, 1),
    (2, 2, 2),
    (2, 2, 3),
    (3, 1, 1),
    (3, 1, 2),
    (3, 1, 3),
    (3, 2, 1),
    (3, 2, 2),
    (3, 2, 3),
    (4, 1, 1),
    (4, 1, 2),
    (4, 1, 3),
    (4, 2, 1),
    (4, 2, 2),
    (4, 2, 3),
    (5, 1, 1),
    (5, 1, 2),
    (5, 1, 3),
    (5, 2, 1),
    (5, 2, 2),
    (5, 2, 3);

insert into planograma(ean, nro, lado, altura, face, unidades, loc)
    values
    (2, 3, 1, 3, 2, 6, 3),
    (5, 1, 2, 1, 5, 30, 5),
    (1, 1, 2, 2, 5, 39, 5),
    (8, 5, 1, 2, 3, 9, 4);

insert into evento_reposicao(operador, instante)
    values
    ('Jesus', timestamp '2017-11-10 11:11:11'),
    ('Jose', timestamp '2017-11-10 11:11:12'),
    ('Manuel', timestamp '2017-11-10 11:11:13'),
    ('Manuel', timestamp '2017-11-11 11:11:13'),
    ('Edilson', timestamp '2017-11-10 11:11:14'),
    ('Ericlene', timestamp '2017-11-10 11:11:15'),
    ('Antelmo', timestamp '2017-11-10 11:11:16'),
    ('Ericlene', timestamp '2017-11-10 11:11:17');

insert into reposicao(ean, nro, lado, altura, operador, instante, unidades)
    values
    (8, 5, 1, 2, 'Antelmo', timestamp '2017-11-10 11:11:16', 4),
    (5, 1, 2, 1, 'Manuel', timestamp '2017-11-10 11:11:13', 3),
    (5, 1, 2, 1, 'Manuel', timestamp '2017-11-11 11:11:13', 5),
    (2, 3, 1, 3, 'Jesus', timestamp '2017-11-10 11:11:11', 2),
    (2, 3, 1, 3, 'Edilson', timestamp '2017-11-10 11:11:14', 1);
