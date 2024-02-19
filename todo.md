# Issues

Fix this bug

```php
verstehen results:
verstehen saved to database.
se verstehen saved to database.
erwähren has no definitions
PHP Warning:  Trying to access array offset on value of type bool in /home/kurt/temp/new-vocab-german/src/FetchWord.php on line 41
PHP Fatal error:  Uncaught TypeError: Vocab\Pos::fromString(): Argument #1 ($pos) must be of type string, null given, called in /home/kurt/temp/new-vocab-german/src/FetchWord.php on line 41 and defined in /home/kurt/temp/new-vocab-german/src/Pos.php:20
Stack trace:
#0 /home/kurt/temp/new-vocab-german/src/FetchWord.php(41): Vocab\Pos::fromString()
#1 /home/kurt/temp/new-vocab-german/src/Database.php(109): Vocab\FetchWord->__invoke()
#2 /home/kurt/temp/new-vocab-german/src/Facade.php(102): Vocab\Database->fetch_word()
#3 /home/kurt/temp/new-vocab-german/test.php(20): Vocab\Facade->create_html()
#4 {main}
  thrown in /home/kurt/temp/new-vocab-german/src/Pos.php on line 20
```
Test each of theese: DBWord, DBVerb and DBNoun

These all use DBWordBase and how it and its parent classes are designed needs to be reviewed.

* Finish the database-insertion code with various input words like kommen, gehen, etc, that have prefix versions \
  also test with nouns and other parts of speech like adjectives and adverbs.


