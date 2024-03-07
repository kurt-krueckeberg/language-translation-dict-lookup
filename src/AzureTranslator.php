<?php
declare(strict_types=1);
namespace Vocab;

class AzureTranslator extends RestApi implements TranslateInterface {

   static private array  $lookup = array('method' => "POST", 'route' => "dictionary/lookup");
   static private array  $examples = array('method' => "POST", 'route' => "dictionary/examples");

   static private array  $languages = array('method' => "GET", 'route' => "languages");
   static private string $from = "from";
   static private string $to = "to";

   // rquired query parameter 
   private $query = array('api-version' => "3.0");
   private $headers = array();
   private $json = array();

   private \Collator $collator;
  
   public function __construct(Config $c)
   {
      parent::__construct($c, ProviderID::Azure);        

      $this->collator = $c->getCollator(); 
   }

   // If no source language is given, it will be auto-detected.
   protected function setLanguages(string $dest_lang, $source_lang="")
   {
      if ($source_lang !== "")  {
          
           $this->query[self::$from] = $source_lang;
      }

      $this->query[self::$to] = $dest_lang; 
   }

   public function getTranslationLanguages() : array
   {
      $this->query['scope'] = 'translation';

      $contents = $this->request(self::$languages['method'], self::$languages['route'],  ['headers' => $this->headers, 'query' => $this->query]);
             
      $arr = json_decode($contents, true);
    
      return $arr["translation"]; 
   } 


   final public function getDictionaryLanguages() : array 
   {
      $this->query['scope'] = 'dictionary';

      $contents = $this->request(self::$languages['method'], self::$languages['route'],  ['headers' => $this->headers, 'query' => $this->query]);
             
      return json_decode($contents, true);    
   }
   
   /*
This is the implmentation fo DictionaryInterface::lookup(). It needs to return a result(s) like that of SystranTranslator::lookup();
that is, it needs to return an \Iterator than when derefderenced returns the Azure equivalent of SystranWord/Noun/Verb/RelatedVerb. 

The easiest way to return an \Iterator is to write a Generator that implements \Iterator) whose yeild expression returns an object
that implmentseither WordInterface/NounInterface/VerbInterface just like SystranWord/Noun/Verb/RelatedVerb do.
 
   final public function lookup(string $word, string $src_lang, string $dest_lang, bool $exact_match=false) : \Iterator
   {      
      $this->setLanguages($dest_lang, $src_lang); 
       
      $this->json = [['Text' => $word]];       
    
      $contents = $this->request(self::$lookup['method'], self::$lookup['route'], ['headers' => $this->headers, 'query' => $this->query, 'json' => $this->json]);

      $obj = json_decode($contents); 
     
      $generator = function (array $translations) {
        
          foreach($translations as $translation) {
              
           echo "Returning " .  $translation->normalizedTarget . " from AzureTranslator generator closure.\n";   

           yield  $translation->normalizedTarget;
          }
      };
              
      return $generator($obj[0]->translations);       
   }
   
   static public function LookupGenerator(array $translations) : \Iterator
   {
        foreach($translations as $translation) { 
           $translation->posTag,????
           yield  $translation->normalizedTarget; //TODO: Needs to return an implementation object for the Word-, Noun- or VerbInterface, as appropriate.
        }
   }

   public static function get_lookup_result(\stdClass $x) : AzureDictResult 
   {
       
      return new AzureDictResult($x->posTag, $x->normalizedTarget);
   }
*/

   /*
     Azure Translation response contents:

       [
           {
               "detectedLanguage": {
                   "language": "en",
                   "score": 1.0
               },
               "translations": [
                   {
                       "text": "สวัสดี",
                       "to": "th",
                       "transliteration": {
                           "script": "Latn",
                           "text": "sawatdi"
                       }
                   }
               ]
           }
       ]
   */
   final public function translate(string $text, string $dest_lang, $source_lang="de") : string 
   {
       static $trans = array('method' => "POST", 'route' => "/translate", 'query' => ['api-version' => '3.0']);
              
       $options = [];
       
       $options['query'] = ['from' => $source_lang, 'to' => $dest_lang, 'api-version' => '3.0'];
       
       /*
        * Input text goes in message body, ie as encoded json.
        */
       
       $options['json'] = [['Text' => json_encode($text)]];;
       
       $options['http_errors'] = false;
       
       $contents = $this->request($trans['method'], $trans['route'], $options);
       
       if ($contents == false)
            throw new \Exception("AzureTranslator::translate($text, 'en', 'de') returned false.\n");
       
       $obj = json_decode($contents);
       
       /*
        * Since Azure text translate returns a quoted result like this: '"translation here within quotes"',
        * we remove the superfulouse quotes.
        */
       $result = \trim($obj[0]->translations[0]->text, '"'); 
       
       return $result;
   }

   /* Azure Translator lookup response body:
     [ 
      {
          "normalizedSource":"fly",
          "displaySource":"fly",
          "translations":[        <-- array
              {
                  "normalizedTarget":"volar",
                  "displayTarget":"volar",
                  "posTag":"VERB",
                  "confidence":0.4081,
                  "prefixWord":"",
                  "backTranslations":[
                      {"normalizedText":"fly","displayText":"fly","numExamples":15,"frequencyCount":4637},
                      {"normalizedText":"flying","displayText":"flying","numExamples":15,"frequencyCount":1365},
                      {"normalizedText":"blow","displayText":"blow","numExamples":15,"frequencyCount":503},
                      {"normalizedText":"flight","displayText":"flight","numExamples":15,"frequencyCount":135}
                  ]
              },
              {
                  "normalizedTarget":"mosca",
                  "displayTarget":"mosca",
                  "posTag":"NOUN",
                  "confidence":0.2668,
                  "prefixWord":"",
                  "backTranslations":[
                      {"normalizedText":"fly","displayText":"fly","numExamples":15,"frequencyCount":1697},
                      {"normalizedText":"flyweight","displayText":"flyweight","numExamples":0,"frequencyCount":48},
                      {"normalizedText":"flies","displayText":"flies","numExamples":9,"frequencyCount":34}
                  ]
              },
              //
              // ...list abbreviated for documentation clarity
              //
          ]
      }
     ]
   */

   /* 
    * Returns an array of example phrases for input of word plus d particular definition:
    
     Input: $word and its associated $definitions array. 
    * 
     Output: Array of examples where $examples[$index]['examples'] has the example phrases for 
             $defintions[$index].

    The returned Azure Tranbslator repsonse body for example input of [ {"Text":"fly", "Translation":"volar"} ] is:

    [
       {
           "normalizedSource":"fly",
           "normalizedTarget":"volar",
            "examples":[
               {
                   "sourcePrefix":"They need machines to ",
                   "sourceTerm":"fly",
                    "sourceSuffix":".",
                   "targetPrefix":"Necesitan máquinas para ",
                    "targetTerm":"volar",
                   "targetSuffix":"."
                },      
               {
                    "sourcePrefix":"That should really ",
                   "sourceTerm":"fly",
                    "sourceSuffix":".",
                   "targetPrefix":"Eso realmente debe ",
                    "targetTerm":"volar",
                   "targetSuffix":"."
             },
            //...snip
            ]
       }
    ]
   */
   final public function examples(string $word, ResultsIterator $definitions) : ResultsIterator
   {
      if (count($definitions) == 0) 
          throw new \Exception("AzureTranslotor::example(word, definitions) cannot be called with empty definitions array.");
      
      $input = array();

      foreach($definitions as $index => $definition)  {
              
            if ($index == 10) break; // There is a limit of 10 in the input array
 
            $input[] = ['Text' => $word, 'Translation' => $definition['definition']]; 
      }

      $contents = $this->request(self::$examples['method'], self::$examples['route'], ['headers' => $this->headers, 'query' => $this->query, 'json' => $input]);

      $obj = json_decode($contents); 
      
      return ExamplesGenerator($obj['examples']);
   }
   
   /*
    * Returns an array with 'source' as the source language example  and 'target' as its translation.
    */
   function ExamplesGenerator(array $examples) : \Iterator
   {   
       foreach($examples as $example) {
          
          yield ['source' => $example->sourcePrefix . $example->sourceTerm . $example->sourceSuffix, 'target' => $target = $example->targetPrefix . $example->targetTerm . $example->targetSuffix];
       }     
   }   
}
