<?php
declare(strict_types=1);
namespace Vocab;

class CreateDBWordResultIterator { 

    private \Iterator $iter;
 
    protected static $sql_noun = "select n.gender as gender, n.plural as plural from
     words as w
join
    nouns_data as n on w.id=n.word_id
where w.id=:word_id";

    protected static $sql_verb = "select w.id as word_id, w.word, w.pos, tenses.conjugation as conjugation from
     words as w
join 
    verbs_conjs as v  on w.id=v.word_id
join
    conjs as tenses on tenses.id=v.conj_id
where w.id=:word_id";

    protected static $sql_verb_family = "select w_id, w_word, conjugation FROM
(select vc.conj_id as outer_conj_id, vc.word_id as outer_word_id from verbs_conjs as vc where vc.word_id=:word_id) as Y
inner join
(select w.id as w_id,
     w.word as w_word,
     conjs.conjugation as conjugation,
     vc.conj_id as inner_conj_id     
 from words as w
 inner join
  verbs_conjs as vc ON vc.word_id=w.id
 inner join
  conjs ON conjs.id=vc.conj_id
) as X
on Y.outer_conj_id=X.inner_conj_id
order by w_id ASC";
    
    static private $stmts = array();

    static int $word_id = -1;  

    use get_stmt_trait;

    public function __construct(\PDO $pdo, Pos $pos, int $word_id)
    {
       if ($pos !== Pos::Verb && $pos !== Pos::Noun)
            $this->iter = CreateDBWordResultIterator(CreateDBWordResultIterator::SingleWordResult);
    
       $rows = match ($pos) {
          
          Pos::Verb => $this->fetchRows($pdo, 'sql_verb_family', $word_id), 
          Pos::Noun => $this->fetchRows($pdo, 'sql_noun', $word_id)
       };
    
       /*
       if (count($rows) > 1) {
           
         // Return an iterator with more than one match if row count > 1. 
    
         if ($pos == Pos::Verb) { // We assume this is a verb family. The main verb is in row 0. 
           
           if (// Test if row[1] also has Pos of Verb? and do strpos($row[1]['word'], $row[0]['word']) != 0 or false  {
    
	       $this->iter = CreateDBWordResultIterator::VerbFamilyGenerator($matches, $this->main_verb_index); 

       } else {

	   $this->iter = CreateDBWordResultIterator::SimpleDictionaryResultsGenerator($matches); 
       }    
       } else {
    
          $this->iter = CreateDBWordResultIterator::SingleWordResultGenerator($matches);
       }
     }
        * 
        */
       $debug = 0;
    }
   
    function bind(\PDOStatement $stmt, string $str) : void
    {
        $stmt->bindParam(':word_id', self::$word_id, \PDO::PARAM_INT);
    }

    function fetchRows(\PDO $pdo, string $sql, int $word_id) : array
    {
       $stmt = $this->get_stmt($pdo, $sql);
       
       self::$word_id = $word_id;
       
       $rc = $stmt->execute();
       
       if ($rc === false)
           die ("fatal error\n");
       
       return $stmt->fetchAll();
    }

    function fetchNoun(int $id)
    {


    }

    public static function VerbFamilyGenerator(array $row, int $main_verb_index) : \Iterator 
    {
        //--yield new DBVerb(???);
        
        foreach($matches as $index => $current) {          
          
          if ($index == $main_verb_index) {
             
             continue;
    
          } else {
             
             yield new DBRelatedVerbResult($current);
          }
       }
    }   

    /*
    public static function SingleWordResultGenerator(array $arr) : \Iterator
    {
        yield new DBWord(???);
    }
     * 
     */
   
}
