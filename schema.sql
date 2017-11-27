drop table if exists categoria cascade;
drop table if exists categoria_simples cascade;
drop table if exists super_categoria cascade;
drop table if exists constituida cascade;
drop table if exists produto cascade;
drop table if exists fornecedor cascade;
drop table if exists fornece_sec cascade;
drop table if exists corredor cascade;
drop table if exists prateleira cascade;
drop table if exists planograma cascade;
drop table if exists evento_reposicao cascade;
drop table if exists reposicao cascade;


---------------------------------------------
-- Table Creation
---------------------------------------------

create table categoria
  (nome varchar(50) not null unique,
   constraint pk_categoria primary key(nome));

create table categoria_simples
  (nome varchar(50) not null unique,
   constraint fk_categoria_simples_categoria foreign key(nome) references categoria(nome)
      on delete cascade on update cascade);

create table super_categoria
  (nome varchar(50) not null unique,
   constraint fk_super_categoria_categoria foreign key(nome) references categoria(nome)
      on delete cascade on update cascade);

create table constituida
  (super_categoria varchar(50) not null,
   categoria varchar(50) not null unique,
   constraint fk_constituida_super_categoria foreign key(super_categoria) references super_categoria(nome)
      on delete cascade on update cascade,
   constraint fk_constituida_categoria foreign key(categoria) references categoria(nome)
      on delete cascade on update cascade);

create table produto
  (ean int not null unique,
   design varchar(120) not null,
   categoria varchar(50) not null,
   forn_primario varchar(50) not null,
   data date not null,
   constraint pk_produto primary key(ean),
   constraint fk_produto_categoria foreign key(categoria) references categoria(nome)
      on delete cascade on update cascade,
   contrainst fk_produto_fornecedor foreign key(forn_primario) references fornecedor(nif)
      on delete cascade on update cascade);

create table fornecedor
  (nif int not null unique,
   nome varchar(50) not null,
   constraint pk_fornecedor primary key(nif));

create table fornece_sec
  (nif int not null unique,
   ean int not null unique,
   constraint fk_fornece_sec_fornecedor foreign key(nif) references fornecedor(nif)
      on delete cascade on update cascade,
   constraint fk_fornece_sec_produto foreign key(ean) references produto(ean)
      on delete cascade on update cascade);

create table corredor
  (nro int not null unique,
   largura int not null,
   constraint pk_corredor primary key(nro));

create table prateleira
  (nro int not null,
   lado int not null unique,
   altura int not null unique,
   constraint pk_prateleira primary key(lado, altura),
   constraint fk_prateleira_corredor foreign key(nro) references corredor(nro)
      on delete cascade on update cascade);
