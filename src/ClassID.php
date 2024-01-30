<?php
declare(strict_types=1);
namespace Vocab;

enum ClassID implements ClassmapperInterface {

   case  Leipzig;
   case  Systran;
   case  Azure;
   case  Ibm;
   case  Deepl;
   case  Collins;
   case  Pons;
   case  iTranslate;
    
   public function class_name() : string
   {
       return match($this) { // Returns implementation class
           ClassID::Leipzig  => "Vocab\LeipzigSentenceFetcher", 
           ClassID::Systran  => "Vocab\SystranTranslator",
           ClassID::Systran  => "Vocab\DeeplTranslator"
       };
   }

   public function get_provider() : string
   {
       return match($this) { // Returns implementation class's abbreviation used in 'config.xml'
           ClassID::Leipzig  => "leipzig",
           ClassID::Systran  => "systran",
           ClassID::Deepl    => "deepl"
       };
   }
}
