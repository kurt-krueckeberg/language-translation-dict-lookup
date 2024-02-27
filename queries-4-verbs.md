# Verb Queries

## Select the Conjugations, Definitions and any Expressions for a Given Verb

For a given word whose `word.id=:word_id`, select its (possibly shared) conjugation, definitions and any expressions:

```sql
select w.id as w_id,
     w.word as w_word,
     conjs.conjugation as conjugation,
     d.word_id as defns_word_id,
     d.defn as definition,
     e.source,
     e.target
 from words as w
 inner join
  verbs_conjs as vc ON vc.word_id=w.id
inner join
  conjs ON conjs.id=vc.conj_id
inner join
  defns as d ON w.id=d.word_id
left join
  exprs as e ON e.defn_id=d.id
 where w.id=:word_id;
```

## Selecting the Prefix Verb Families

### Select those `verbs_conjs.conj_id`'s that are part of prefix verb families

Select only those `conj_id` in  `verbs_conjs.conj_id`'s where `count(conj_id) > 1`:

```sql
select vc.conj_id
 from 
     words as w
 inner join verbs_conjs as vc
     on w.id=vc.word_id
 inner join conjs
     on conjs.id=vc.conj_id
 group by vc.conj_id having count(vc.conj_id) > 1
``` 

Note: We can also include the count:

```sql
select vc.conj_id, count(vc.conj_id) as cnt_greater_than_one
 from 
     words as w
 inner join verbs_conjs as vc
     on w.id=vc.word_id
 inner join conjs
     on conjs.id=vc.conj_id
 group by vc.conj_id having cnt_greater_than_one > 1
``` 

### Select words that are verbs and show their conjugations:


```sql
select w.id as w_id,
       w.word as w_word,
       vc.conj_id
 from 
     words as w
 inner join verbs_conjs as vc
     on w.id=vc.word_id
 inner join conjs
     on conjs.id=vc.conj_id
 where w.id=:word_id
```

### Get all verb families


Joining the two queries will give all verbs in prefix verb families:

```sql
select * FROM
(select vc.conj_id as x_conj_id,
       count(vc.conj_id) as cnt_greater_than_one
 from 
     words as w
 inner join verbs_conjs as vc
     on w.id=vc.word_id
 inner join conjs
     on conjs.id=vc.conj_id
 group by vc.conj_id having cnt_greater_than_one > 1) as X
INNER JOIN
(select w.id as w_id,
       w.word as w_word,
       vc.conj_id as y_conj_id
 from 
     words as w
 inner join verbs_conjs as vc
     on w.id=vc.word_id
 inner join conjs
     on conjs.id=vc.conj_id) as Y
 ON x_conj_id=y_conj_id
```

// Left definitions and expression out of the above query

