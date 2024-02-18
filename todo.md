# Issues

* Faced calls `fetch_samples(WordInterface $wrface) : \Traversable`. Would it be better to have the vocab database `words.id` of the word
to pass to `Database::fetchSamples(...)`? 

* Finish the code related to creating the html page. This code retrieve database words and uses classes like
  DBWord, DBVerb and DBNoun, which have not been tested.

These all use DBWordBase and how it and its parent classes are designed needs to be reviewed.
The DBWord class als is not full designed and implemented.

* Finish the database-insertion code with various input words like kommen, gehen, etc, that have prefix versions \
  also test with nouns and other parts of speech like adjectives and adverbs.


