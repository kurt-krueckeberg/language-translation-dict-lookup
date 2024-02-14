# Issues

This does a left join that shows the particular word key, a particular defnition id and definition
on the right all the word's associated expressions:

```sql
select words.id as words_key, words.word, defns.id as defn_id, defns.defn, exprs.expr from
   words
join
     defns
on words.id=defns.word_id
 left join
     exprs
         on exprs.defn_id=defns.id 
where defns.word_id=1
  order by defn_id asc;
```  
  
To get a count of the number of expressions for each definition that has associated expressions do:


```sql
select defns.id as defns_id, count(*) as expressions_count from
defns   
    join
 exprs
    on exprs.defn_id=defns.id 
  group by defns.id having defns.id>=1 AND defns.id<=9
    order by defn_id asc;
```
