<?php
declare(strict_types=1);
namespace Vocab;

readonly class Config {

   public string $lookup_file_name;

   public int $sentence_count;
   
   public  array $config; 
   
   public string $namespace;

   public function __construct() 
   {
      $this->lookup_file_name = "vocab.txt";

      $this->sentence_count = 5;

      $this->config = [ 'database' => ['dsn' => 'mysql:dbname=vocab;host=127.0.0.1', 'user' => 'kurt', 'password' => 'kk0457'],
                        'providers' => [ 
                                       'leipzig_de'  => [ 'endpoint' => 'https://api.wortschatz-leipzig.de/ws/sentences/deu_news_2012_1M/sentences/',
                                                          'header' => []
                                                        ],
                                          "deepl"    => [ 'endpoint' => 'https://api-free.deepl.com/v2',
                                                           'header' => ["Authorization" => 'DeepL-Auth-Key ca3e03e3-7377-4601-8ce1-bd5f3af2d660']
                                                        ],
                                         "systran"   => [ 'endpoint' => 'https://api-translate.systran.net',
                                                            'header' => ["Authorization" => 'Key bf31a6fd-f202-4eef-bc0e-1236f7e33be4']
                                                        ],
                                         "azure"   =>   [  'endpoint' => 'https://api-nam.cognitive.microsofttranslator.com',
                                                           'header' => [ 'Ocp-Apim-Subscription-Key' => '8fd23d4521904848acccec18e8d8ab18', 'Ocp-Apim-Subscription-Region' => 'eastus'] 
                                                        ],
                                       ],
                        'language' =>   [ 'source'   => "English", 'destination' => 'Deutsch', 'locale' => 'de_DE'] ];

      $this->namespace = "Vocab";
   }

   public function get_config(ProviderID $id) : array
   {
      $provider_name = $id->get_provider();
    
      $r = [];
 
      $r['base_uri'] = $this->config['providers'][$provider_name]['endpoint'];
      
      $r['headers'] = $this->config['providers'][$provider_name]['header'];

      return $r;
   }
 
   public function get_db_credentials() : array // returns: array('dsn' =>, 'user' => . 'password' =>);
   {
     return $this->config['database'];
   }

   public function get_locale()
   {
     return $this->config['language']['locale'];
   }

   public function getCollator() : \Collator
   {
     return new \Collator($this->get_locale()); 
   }

   function lookup_file() : string
   {
     return $this->lookup_file_name;
   }

   function fetch_words() : array
   {
     $words = [];

     $file = new FileReader($this->lookup_file(), "r");
    
      foreach ($file as $word) 
         $words[] = $word;
     
      return $words;
   }
 
   function sentence_count() : int
   {
      return $this->sentence_count;
   }
}
