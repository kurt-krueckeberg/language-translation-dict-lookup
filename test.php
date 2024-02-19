<?php
declare(strict_types=1);
use \SplFileObject as File;

use Vocab\{FileReader, Config, Facade};

include 'vendor/autoload.php';
   
 $c = new Config();
         
 if (!file_exists($c->lookup_file())) {
     
     die($c->lookup_file() . " not found.\n");
 }
  
 $fac = new Facade($c);

 $word_list = $fac->db_insert();
 
 /*
  *  TODO: This method does not know about words that were not found in the diciontary and
  *  therefore not saved in the atabase. 
  * 
  *  It should process the subset of only those words found in dictionary
  */
 $fac->create_html($word_list, 'output');
