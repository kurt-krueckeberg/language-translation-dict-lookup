<?php
declare(strict_types=1);
namespace Vocab;

enum Gender : string { 
   case Mas = 'm'; 
   case Fem = 'f'; 
   case Neu = 'n';
   case Unknown = 'u';   
}