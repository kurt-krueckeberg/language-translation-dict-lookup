# Issues

Finding prefix verbs with shared conjugations:

```sql
SELECT s.conj_id, count(word_id) as cnt FROM `shared_conjugations` as s 
group by conj_id having cnt > 1;
```

```sql
select sc.word_id from shared_conjugations as sc
inner join (SELECT s.conj_id, count(word_id) as cnt FROM `shared_conjugations` as s 
group by conj_id having cnt > 1) as X
on X.conj_id=sc.conj_id;
```
