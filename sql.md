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

Those verbs that share conjutation are prefix verbs of a main verb. Does it matter? We can get the entire family of main plus prefix- or reflexive verbs, but 
we don't know which is the main verb.

If verb,...

1.  select just this verb

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

2.  select all prefix verbs, if applicable:



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


