I don't think my Azure translation free account is the problem casuing createhtml() to famil. I am getting "false" translation result of a sample sentence.
But I don't think it is  because I have exceeded my quota. There I don't think that I have to

1. Switch to pay-as-you-go
2. Contact support
3. Use the code in AzureTranslate. See also ~/temp/Text-Translation-PAI-V3-PHP

The problem may be in the Generators that get returned when `Facade::create_htm;($words, 'output')` is called.

Are the sample sentences in the database not in unicode? Do I need to call `json_encode()` before call Azure translate?

The problem seems to be in FetchSamples.php and its generator code. This is were, it appears,the return values when `false` is encountered!

**Note:** Translations of the sample sentences are not saved in the database. They need therefore to be translated when the html page is built.
