<?php
declare(strict_types=1);
namespace Vocab;

class Config {

   public string $lookup_file_name = "/home/kurt/vocab-german/vocab.txt";

   public int $sentence_count = 5;
   
   public  array $config = [ 'database' => ['dsn' => 'mysql:dbname=vocab;host=127.0.0.1', 'user' => 'kurt', 'password' => 'kk0457'],
                             'providers' => [ 
                                    'leipzig_de'  => [ 'endpoint' => 'https://api.wortschatz-leipzig.de/ws/sentences/deu_news_2012_1M/sentences/',
                                                       'header' => []
                                                     ],
                                       "deepl"    => [ 'endpoint' => 'https://api-free.deepl.com/v2',
                                                        'header' => ["Authorization" => 'DeepL-Auth-Key 7482c761-0429-6c34-766e-fddd88c247f9:fx']
                                                     ],
                                      "systran"   => [ 'endpoint' => 'https://api-translate.systran.net',
                                                         'header' => ["Authorization" => 'Key bf31a6fd-f202-4eef-bc0e-1236f7e33be4']
                                                     ],
                                      "azure"   =>   [ 'endpoint' => 'https://eastus.api.cognitive.microsoft.com/',
                                                       'region' => 'eastus',
                                                       'header' => ["Authorization" => 'Ocp-Apim-Subscription-Key: 4deaf89075834283a48745ae530dc9d5'] ],
                                      "lingua"  =>   [ 'endpoint' => 'https://petapro-translate-v1.p.rapidapi.com/?query=verdienen&langpair=de-en',
                                                       'header' => [ 'X-RapidAPI-Host' => 'petapro-translate-v1.p.rapidapi.com',
                                                         	     'X-RapidAPI-Key' => '8e66ea6b02msh0e2d6e77c44f0f7p132571jsn1ad710951776']],
                                                     ],
                     'language' =>   [ 'source'   => "English", 'destination' => 'Deutsch', 'locale' => 'de_DE']
           ];
   
   
   public string $namespace = "TranslationTools";

   public function __construct()
   {   
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
 
   function sentence_count() : int
   {
      return $this->sentence_count;
   }
}
