# Database Retrievel of Stored Words

## Nouns

If words.pos = 'noun', then select all this (for words.id=24):

```sql
select w.id as w_id, w.word as w_word, w.pos as words_pos, nd.gender as nd_gender,nd.plural as nd_plural, d.word_id as defns_word_id, d.defn as definition, e.defn_id as expressions_defn_id, e.source FROM words as w
inner JOIN
nouns_data as nd
on w.id = nd.word_id
inner join
defns as d on w.id=d.word_id
left JOIN
exprs as e on e.defn_id=d.id
where d.word_id=24
```

```php
/*
 * In general:
 */

$sql_noun_select = "select w.id as w_id,    
    w.word as w_word,    
    w.pos as words_pos,    
    nd.gender as nd_gender,    
    nd.plural as nd_plural,    
    d.word_id as defns_word_id,    
    d.defn as definition,    
    e.defn_id as expressions_defn_id,    
    e.source FROM words as w
inner JOIN
  nouns_data as nd ON w.id = nd.word_id
inner join
  defns as d ON w.id=d.word_id
left JOIN
  exprs as e ON e.defn_id=d.id
where d.word_id=:word_id";
```

## Verb Queries

### Select the Conjugations, Definitions and any Expressions for a Given Verb

```sql
select w.id as w_id,
     w.word as w_word,
     conjs.conjugation as conjugation,
     d.word_id as defns_word_id,
     d.defn as definition,
     e.defn_id as expressions_defn_id,
     e.source FROM words as w
inner JOIN
  verbs_conjugations as vc ON vc.word_id=w.id
inner Join
  conjugations as conjs ON conjs.id = vc.conj_id
inner join
  defns as d ON w.id=d.word_id
left JOIN
  exprs as e ON e.defn_id=d.id
where d.word_id=1
```

### Selecting the Prefix Verb Families

To select the entire verb prefix family for a verb whose `word.id=:word_id`, we use two queries

#### Select those `verbs_conjs.conj_id` s that are verb families

This query selects `verbs_conjs.conj_id` s where `count(verbs_conjs.conj_id) > 1`, that is, where `verbs_conjs.conj_id` appears more than once:

```sql
select vc.conj_id,
       count(vc.conj_id) as cnt_greater_than_one
 from 
     words as w
 inner join verbs_conjs as vc
     on w.id=vc.word_id
 inner join conjs
     on conjs.id=vc.conj_id
 group by vc.conj_id having cnt_greater_than_one > 1
``` 

#### Select words that are verbs and show their conjugations:

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
 order by w_id asc
```

#### Get all verb families


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



### Alternative Database Tables

Have conjugations stored in each verb. 
Have table of `verb_families`: 


Should verbs that have no prefix versions also be in this table? They are a "family" of one verb.

```sql
create table if not exists verb_families
main_verb_id
related_verb_id
foreign key(main_verb_id) references words(id),
foreign key(related_verb_id) references words(id)
```


