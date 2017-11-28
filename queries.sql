-- a)
select nome
from (select nif
      from ((select nif, categoria from produto)
            union
           (select nif, categoria from fornece_sec
             natural join
             (select nif, categoria from produto)))
      group by nif))
      having count(nif) >= all(select count(nif)
                               from ((select nif, categoria from produto)
                                    union
                                    ((select nif, categoria from fornece_sec)
                                      natural join
                                     (select nif, categoria from produto)))
                              group by nif))
    natural join fornecedor;

-- b)
select nif, nome
from (select nif, nome as categoria
      from produto
      group by nif
      having count(distinct categoria) = (select count(nome)
                                          from categoria_simples))
     natural join
     fornecedor;

-- c)
select ean
from produto
where ean not in (select ean
                  from reposicao);

-- d)
select ean
from fornece_sec
group by ean
having count(nif) > 10;

-- e)
select ean
from(select distinct ean, operador
     from reposicao)
group by ean
having count(operador) = 1;
