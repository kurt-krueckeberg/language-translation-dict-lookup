Is my Azure translation account free and I am getting "false" translation results because I have exceeded 
my quota?

Do I: 
1. Switch to pay-as-you-go
2. Contact support
3. Use the code in AzureTranslate. See also ~/temp/Text-Translation-PAI-V3-PHP

Thought as of March 3 2024:

The problem may be in the Generators that get return when `Facade::create_htm;($words, 'output')` is called.

Are they timing out? Something seems to be timing out. `AzureTransate::translate()` should be be called at this point, either, as all the 
translations have already been saved in the datbase.

Look into FetchSamples.php and its generator code and return values when `false` is encountered!

**Note:** Translations of the sample sentences are not save in the database. Thus they need ot be translated when the html page is build that
has the sample in German and its tranlation in English.
