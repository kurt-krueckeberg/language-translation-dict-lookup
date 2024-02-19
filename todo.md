# Issues

Translations of the sample sentences are not saved and therefore not retrieved:
* add translated samples to db.sql, drop.sql and add code to Database::save_samples()

Test each of theese: DBWord, DBVerb and DBNoun

These all use DBWordBase and how it and its parent classes are designed needs to be reviewed.

* Finish the database-insertion code with various input words like kommen, gehen, etc, that have prefix versions \
  also test with nouns and other parts of speech like adjectives and adverbs.


