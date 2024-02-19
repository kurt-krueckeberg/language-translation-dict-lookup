# Issues

* Use a common technique for saving static PDOPreparedStatement's. Perhaps make it a trait. Use this for 
the new FetchWord and FetchSamples code. Prepared statements are not intended to be recreated repeatedly.

This is done using `Database::get_table(string) : mixed`. The classes it returns do not have base classes.
This is the different from cases where the base class `get_stmt(string) : ??` contain a template method 
that calls `get_sql` and then calls `bind(\PDOStatment, \PDO`. This is a more general technique.

* Have `fetch_samples(WordInterface $wrface) : \Traversable` -- return Traversable not SentenceIterator? 

Test each of theese: DBWord, DBVerb and DBNoun

These all use DBWordBase and how it and its parent classes are designed needs to be reviewed.

* Finish the database-insertion code with various input words like kommen, gehen, etc, that have prefix versions \
  also test with nouns and other parts of speech like adjectives and adverbs.


