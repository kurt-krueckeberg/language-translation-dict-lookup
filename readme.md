# PHP Translation and Dictionary REST API Classes

This repository contains PHP classes that support:

- Translation of text.
- Dictionary lookup.
- create German sample sentences for a given word uinsg the **German Sentence Corpus** [REST API](http://api.corpora.uni-leipzig.de/ws/swagger-ui.html#/) of the Leipzig University Department of Numerical Linguistics.

## PHP Classes

### Leipzig German Sentence Corpus Sample Sentences Class

[LeipzigSentenceFetcher](src/LeipzigSentenceFetcher.php) implements the [SentenceFetchInterface](src/SentenceFetchInterface.php).

### Translation Classes

These classes implement [TranslateInterface](src/TranslateInterface.php):

- [AzureTranslator](src/AzureTranslator.php) an [Azure Translator API](https://learn.microsoft.com/en-us/azure/cognitive-services/translator/translator-text-apis?tabs=csharp) client.
- [DeeplTranslator](src/DeeplTranslator.php) a [DEEPL translate API](https://www.deepl.com/en/docs-api/) client.
- [SystranTranslator](src/SystranTranslator.php) a [Systran Pro translate API](https://docs.systran.net/translateAPI/en/) client.

### Dictionary Lookup Classes

The classes that implement [DictionaryInterface](src/DictionaryInterface.php):

- [PonsDictionary](src/PonsDictionary.php)
- [CollinsGermanDictionary](src/CollinsGermanDictionary.php)
- [OxfordDictionary](src/OxfordDictionary.php)
- [AzureTranslator](src/AzureTranslator.php)
- [SystranTranslator](src/SystranTranslator.php)

The [CollinsGermanDictionary](src/CollinsGermanDictionary.php) `Lookup($word)` methods return **HTML** (or optionally **XML**) specifically for the Collins Dictionary
website. The many CSS classes used in the HTML represent various kinds of information retured, like part of speech; however, these CSS class are undocumented. The
[PonsDictionary](src/PonsDictionary.php) is a bit better documented, but it is not really adequate, either.

Since both the HTML tags and the many CSS they use are undocumented, one must implement a custom solution to extract the dictionary definitions (and any associated
sample expressions). The `PonsIterator`and `CollinsIterator` classes attempt to do this using `XPath` queries that get the definitions and any
associated sample expressions from the HTML.

## Text and HTML Output Examples

The `BuildHtml` class in `src/BuildHtml.php`, along with the `.css` files (in the `css` directory that is uses, creates attractive **HTML** output. To use it, follow
the example in [html-example.php](html-example.php).

To create text output, follow the example code in [cli-example.php](cli-example.php).

## Further Detailed Reference

- [Configuration file format](docs/config.md)
- [Sample Application](docs/app.md)
- [Code Internals](docs/internals.md)
