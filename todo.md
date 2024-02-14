# Issues

To select the word, its , part of speech, conjugation joined with all its definitions:

```sql
select words.id as words_key, words.word, words.pos, X.conjugation, defns.defn
from 
  words 
inner JOIN
  defns
on words.id=defns.word_id   
inner JOIN
 (SELECT w.id, w.word, w.pos, shared.conj_id as shared_conj_id, conj.conjugation as conjugation  
from 
  words as w
inner join
  shared_conjugations as shared
  on shared.word_id=w.id
inner JOIN
  conjugations as conj
  on conj.id=shared.conj_id) as X
  ON X.id=words.id
 where words.id=1;
```
 
 
