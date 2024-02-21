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
           ClassID::Azure  => "Vocab\AzureTranslator",
           ClassID::Deepl    => "Vocab\DeeplTranslator",
           ClassID::Lingua   => "Vocab\LinguaTranslator"
       };
   }

   public function get_provider() : string
   {
       return match($this) { // Returns implementation class's abbreviation used in 'config.xml'
           ClassID::Leipzig  => "leipzig",
           ClassID::Azure  => "azure",
           ClassID::Systran  => "systran",
           ClassID::Deepl    => "deepl",
           ClassID::Deepl    => "lingua"
       };
   }
}
