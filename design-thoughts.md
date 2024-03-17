# ToDo

* Change the exception handling according to what is in ~/v/guzele-exeptions-design.md and the comments in ~/v/main.php.

* Sign up for Azure text translation pay-as-go.

* The `AzureTranslator::lookup()` does not return the expected result. It should return a result(s) like `SystranTranslator::lookup()`, namely
a `SystranNoun/Verb/RelatedVerb/Word`. There might be a way to make a generic Word/Noun/Verb object that is
comfigured to implement the respective `WordInterface/NounInterface/VerbInterface`.

## Alternate Database Schema

This is just an idea that needs to be based on D.J. Date readings. It is the SSQL chnage below:

The Database scheme change below will make all tables dependent on `words`, so when it is
deleted, all other tables likewise get deleted. It seems to make the sql 

As a result of the table alteration and the introduction of a new table, I will need to:

1. Change db.sql and drop.sql
2. Change the code so it inserts into these new tables instead of the old. 
3. And I need to change the SQL query that selects conjugations from the old scheme to
use the new scheme.

```sql
# -- The actual verb conjs are in this table
# -- prefix and reflexive verbs share the conjuation of the 
# -- main verb. Thus, the conjugation of ankommen is kommen's
# -- conjugation.
create table if not exists conjs (
  id int not null auto_increment primary key,
  conjugation varchar(75) not null
  verb_id int not null,
  foreign key(verb_id) references words(id) on delete cascade
);

# -- main verb is main_id. Prefix or reflexsive that shared its conjugation
# -- shared_id. Example: kommen, say, has words.id of 1 and ankommen has
# -- words.id of 2, then we have row with: {1, 2}
create table if not exists shared_conjs (
  shared_id int not null primary key,
  main_id int not null primary key,
  unique (main_id, shared_id),
  foreign key(main_id) references words(id) on delete cascade,
  foreign key(shared_id) references words(id) on delete cascade
);
```