<?php
declare(strict_types=1);
namespace Vocab;

class CreateDBWordResultIterator { 

    protected static $sql_noun = "select w.word as word, w.pos as pos, n.gender as gender, n.plural as plural from
     words as w
join
    nouns_data as n on w.id=n.word_id
where w.id=:word_id";

    protected static $sql_verb = "select w.id as word_id, w.pos as pos, w.word as word, tenses.conjugation as conjugation from
     words as w
join 
    verbs_conjs as v  on w.id=v.word_id
join
    conjs as tenses on tenses.id=v.conj_id
where w.id=:word_id";

    protected static $sql_verb_family = "select word_id, word, pos, conjugation FROM
(select vc.conj_id as outer_conj_id, vc.word_id as outer_word_id from verbs_conjs as vc where vc.word_id=:word_id) as Y
inner join
(select w.id as word_id,
     w.word as word,
     w.pos as pos,
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
    
    private $rows = array();

    static int $word_id = -1;  
    
    private \Iterator $iter;
 
    private DBWordBase $word_result; 
    
    use get_stmt_trait;
 
    public function __construct(\PDO $pdo, array $row)
    {
       if ($row['pos'] !== Pos::Verb && $row['pos'] !== Pos::Noun) {

            $this->iter = CreateDBWordResultIterator::SingleWordResult(new DBWord($pdo, $row));
    
       } else if ($row['pos'] == Pos::Noun) {     

           $rows = $this->fetchRows($pdo, 'sql_noun', $word_id);
            
           $this->iter = CreateDBWordResultIterator::SingleWordResult(new DBNoun($pdo, $row));

       } else {// verb

          $rows = $this->fetchRows($pdo, 'sql_verb_family', $word_id), 

          $this->iter = CreateDBWordResultIterator::VerbGenerator($row);
       }
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

    /*
     * The main verb -- if this is a verb family (and if it is not) -- will be in the first row
     * because the sql result was returned 'order by words.id ASC' and the main verb always has the smallest
     * primary key.
     */
    public static function VerbGenerator(array $rows) : \Iterator 
    {
       foreach($rows as $index => $current) {
            
         if ($index == 0) {
             
            yield new DBVerb();
    
         } else {
             
            yield new DBRelatedVerbResult($current);
         }
       }
    }   
    
    function getIterator() : \Iterator
    {
        if ($this->pos == Pos::Verb)
            
            return CreateDBWordResultIterator::VerbGenerator ($this->rows);
        
        else if ($this->pos == Pos::Noun) {
            
        } else {
            
        }
    }

    public static function SingleNounResultGenerator(DBWord $dbword) : \Iterator
    {
        yield $dbword;
    }   
}
