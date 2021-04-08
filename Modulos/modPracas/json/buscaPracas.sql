select p.CODPRACA,
       p.PRACA,
       r.numregiao,
       r.regiao
from kokar.pcpraca p inner join kokar.pcregiao r on p.numregiao = r.numregiao
where p.codpraca <> 80
  and p.situacao like 'A';
