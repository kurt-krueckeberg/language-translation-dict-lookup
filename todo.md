I don't think my Azure translation free account is the problem casuing createhtml() to fail. I am getting "false" translation result of a sample sentence.

The problem may be in the Generators that get returned when `Facade::create_htm;($words, 'output')` is called.

**Note:** Translations of the sample sentences are saved in the database. IThey need therefore to be translated when the html page is built.


todo: change FetchSamples to get the 'target' column, too. So we don'tn eed to tranlste.
