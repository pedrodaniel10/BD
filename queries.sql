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
