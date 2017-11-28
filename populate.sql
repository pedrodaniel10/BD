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
    ('Gatos');

insert into categoria_simples(nome)
    values
    ('Vegan'),
    ('Manteigas'),
    ('Queijos'),
    ('Brinquedos'),
    ('Caes'),
    ('Gatos');

insert into super_categoria(nome)
    values
    ('Lacticinios'),
    ('Mercearia'),
    ('Animais');

insert into constituida(super_categoria, categoria)
    values
    ('Mercearia', 'Vegan'),
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
    (847238654, 'Lar e Casa');

insert into produto(ean, design, categoria, forn_primario, data)
    values
    (095347657289045763897, 'Terra Vostra', 'Manteigas', 674782364, '1962-05-21'),
    (489236487678932749984, 'Ola', 'Vegan', 489723432, '2017-05-21'),
    (980128635779841948013, 'Forlectibane', 'Caes', 489723432, '2015-02-28'),
    (435678908764234823987, 'Purpura', 'Gatos', 847238654, '2015-03-01'),
    (536721389231893209309, 'Cebel', 'Queijo', 438043829, '2014-07-05'),
    (889473298749382748321, 'Bibosa', 'Lacticinios', 438043829, '2014-07-05'),
    (476847637826487786243, 'Trocatudo', 'Mercearia', 567893213, '2013-01-01'),
    (234567898736489490478, 'Ping pong', 'Brinquedos', 847238654, '2010-01-01');

insert into fornece_sec(nif, ean)
    values
    (378753913, 536721389231893209309),
    (489723432, 234567898736489490478),
    (732792012, 095347657289045763897),
    (945678431, 435678908764234823987);

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
    (489236487678932749984, 3, 1, 3, 2, 6, 3),
    (536721389231893209309, 1, 2, 1, 5, 30, 5),
    (095347657289045763897, 1, 2, 2, 5, 39, 5),
    (234567898736489490478, 5, 1, 2, 3, 9, 4);

insert into evento_reposicao(operador, instante)
    values
    ('Jesus', timestamp '2017-11-10 11:11:11'),
    ('Jose', timestamp '2017-11-10 11:11:12'),
    ('Manuel', timestamp '2017-11-10 11:11:13'),
    ('Edilson', timestamp '2017-11-10 11:11:14'),
    ('Ericlene', timestamp '2017-11-10 11:11:15'),
    ('Antelmo', timestamp '2017-11-10 11:11:16'),
    ('Ericlene', timestamp '2017-11-10 11:11:17');

insert into reposicao(ean, nro, lado, altura, operador, instante, unidades)
    values
    (234567898736489490478, 5, 1, 2, 3, 9, 4, 'Antelmo', timestamp '2017-11-10 11:11:16', 4),
    (536721389231893209309, 1, 2, 1, 5, 30, 5, 'Manuel', timestamp '2017-11-10 11:11:13', 3),
    (489236487678932749984, 3, 1, 3, 2, 6, 3, 'Jesus', timestamp '2017-11-10 11:11:11', 2);
