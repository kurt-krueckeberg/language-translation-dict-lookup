<?php
declare(strict_types=1);
namespace Vocab;

class Config {

   private static string $yml_config = "lang_translation_config.yml";

   public readonly string $namespace;

   public function __construct() 
   {
   $this->config = \yaml_parse_file(__DIR__ . '/' . self::$yml_config);

      $this->namespace = "Vocab";
/*
      $this->config = [ 'database' => ['dsn' => 'mysql:dbname=vocab;host=127.0.0.1', 'user' => 'kurt', 'password' => 'kk0457'],
                        'providers' => [ 
                                       'leipzig_de'  => [ 'endpoint' => 'https://api.wortschatz-leipzig.de/ws/sentences/deu_news_2012_1M/sentences/',
                                                          'header' => []
                                                        ],
                                        "deepl_pro"  => [ 'endpoint' => 'https://api.deepl.com/v2/translate',
                                                       'apikey' => 'ca3e03e3-7377-4601-8ce1-bd5f3af2d660'
                                                        ],
                                        "deepl_free" => [ 'endpoint' => 'https://api-free.deepl.com/v2/translate',
                                                           'apikey' => 'ca3e03e3-7377-4601-8ce1-bd5f3af2d660',
                                                        ],
                                         "systran"   => [ 'endpoint' => 'https://api-translate.systran.net',
                                                            'header' => ["Authorization" => 'Key bf31a6fd-f202-4eef-bc0e-1236f7e33be4']
                                                        ],
                                         "azure"   =>   [  'endpoint' => 'https://api-nam.cognitive.microsofttranslator.com',
                                                           'header' => [ 'Ocp-Apim-Subscription-Key' => '8fd23d4521904848acccec18e8d8ab18', 'Ocp-Apim-Subscription-Region' => 'eastus'],
                                                           'limit' => 2000000
                                                        ],
                                       ],
                        'language' =>   [ 'source'   => "English", 'destination' => 'Deutsch', 'locale' => 'de_DE'] ];
*/
   }

   public function get_config(ProviderID $id) : array
   {
      $provider_name = $id->name; // ProviderID::cases() returns a numeric array of all the names in the ProvideerID enum.
    
      return $this->config['providers'][$provider_name];
   }
 
   public function get_db_credentials() : array // returns: array('dsn' =>, 'user' => . 'password' =>);
   {
     return $this->config['database'];
   }

   public function get_locale() : string
   {
     return $this->config['language']['locale'];
   }

   public function getCollator() : \Collator
   {
     return new \Collator($this->get_locale()); 
   }

   function lookup_file() : string
   {
     return $this->config['lookup_file'];
   }

   function fetch_words() : array
   {
     $words = [];

     $file = new FileReader($this->lookup_file(), "r");
    
      foreach ($file as $word) {
          
         $words[] = $word;
      }
     
      return $words;
   }
 
   function sentence_count() : int
   {
      return $this->config['samples_count'];
   }
}
