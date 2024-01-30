<?php
declare(strict_types=1);
namespace Vocab;

enum ProviderID implements ProviderInterface {

   case  Leipzig_de;
   case  Leipzig_es;
   case  Systran;
   case  Azure;
   case  Ibm;
   case  Deepl;
   case  Collins;
   case  Pons;
   case  iTranslate;
   
   public function get_provider() : string
   {
       return match($this) { // Returns implementation class's abbreviation used in 'config.xml'
           ProviderID::Leipzig_de  => "leipzig_de",
           ProviderID::Leipzig_es  => "leipzig_es", 
           ProviderID::Systran  => "systran",
           ProviderID::Azure    => "azure",
           ProviderID::Ibm      => "ibm",
           ProviderID::Deepl    => "deepl",
           ProviderID::Collins  => "collins",
           ProviderID::Pons     => "pons",
           ProviderID::iTranslate  => "itranslate"
       };
   }
}
