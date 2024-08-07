<?php 
$config = [];
$config['database']['dsn'] = 'mysql:dbname=vocab;host=127.0.0.1';
$config['database']['user'] = 'kurt';
$config['database']['password'] = 'kk0457';

$config['providers'] = [
  'leipzig'  => [ 'endpoint' => 'https://api.wortschatz-leipzig.de/ws/sentences/deu_news_2012_1M/sentences/'],
  "deepl"    => [ 'endpoint' => 'https://api-free.deepl.com/v2',   'header' => ["Authorization" => 'DeepL-Auth-Key 7482c761-0429-6c34-766e-fddd88c247f9:fx']],
  "systran"  => [ 'endpoint' => 'https://api-translate.systran.net', 'header' => ["Authorization" => 'Key bf31a6fd-f202-4eef-bc0e-1236f7e33be4']]
  ]; 

$config['language']['source'] = "English";
$config['language']['destination'] = 'Deutsch';
$config['language']['destination']['locale'] = 'de_DE';
