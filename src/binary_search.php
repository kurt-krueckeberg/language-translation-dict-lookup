<?php
declare(strict_types=1);
namespace Vocab;

class binary_search { // Enables binary search from Linux command line.

   public static function find(array | \ArrayAccess $arr, mixed $key, callable $comparator)
   {
     $lo = 0;  
     $hi = count($arr) - 1; 
  
      while ($lo <= $hi) {
  
          $mid = $lo + (int) (($hi - $lo) / 2);

          $rc = ($comparator)($arr[$mid], $key);
  
          if ($rc < 0)             

              $lo = $mid + 1;
  
          elseif ($rc > 0)

              $hi = $mid - 1;
  
          else 
              return $mid; 
          
      }
      return -1;
   }

   public function __invoke(array | \ArrayAccess $arr, mixed $key, callable $cmp)
   {
      return binary_search::find($arr, $key, $cmp);
   }
}  
