<?php
declare(strict_types=1);
namespace Vocab;

interface implementer {

    function class() : string;
}

enum ProviderID {

   case  leipzig_de;
   case  leipzig_es;
   case  systran;
   case  azure;
   case  ibm;
   case  deepl;
   case  collins;
   case  pons;
   case  itranslate;
   case  lingua;

    // Fulfills the interface contract.
    public function color(): string
    {
        return match($this) {
            Suit::Hearts, Suit::Diamonds => 'Red',
            Suit::Clubs, Suit::Spades => 'Black',
        };

    }    // Fulfills the interface contract.
    public function class(): string
    {
        return match($this) {
            Provider::leipzig_de => 
            Provider::Clubs, Suit::Spades => 'Black',
        };
    }

}
