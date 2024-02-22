# Issues

Find those verbs that are prefix-family verbs:

```sql
SELECT s.conj_id, count(word_id) as cnt FROM `verbs_conjugations` as vc 
group by conj_id having cnt > 1;
```

Get only the prefix- or reflexive-family verbs, but not the other non-prefix, non-reflexsive verbs.

```sql
select sc.word_id from verbs_conjugations as vcc
inner join (SELECT s.conj_id, count(word_id) as cnt FROM `verbs_conjugations` as vc 
group by conj_id having cnt > 1) as X
on X.conj_id=sc.conj_id;
```

Get a verb and any prefix versions:

```sql
SELECT  s.word_id, c.id as conjugation_id, c.conjugation FROM conjugations as c
inner join 
verbs_conjugations as vc
on s.conj_id=c.id;
```
