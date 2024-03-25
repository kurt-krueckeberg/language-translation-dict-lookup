<?php
declare(strict_types=1);
namespace Vocab;

class CreateDBWordResultIterator implements \IteratorAggregate { 

    protected static $sql_noun = "select w.word as word, w.id as word_id, w.pos as pos, n.gender as gender, n.plural as plural from
     words as w
join
    nouns_data as n on w.id=n.word_id
where w.id=:word_id";

    protected static $sql_verb = "select w.id as word_id, w.pos as pos, w.word as word, verb_conjs.conjugation as conjugation from
     words as w
join 
    verb_conjs  on w.id=verb_conjs.word_id
where w.id=:word_id";

    static private $stmts = array();
    
    private $rows = array();

    static int $word_id = -1;  
    
    private \Iterator $iter;
 
    private DBWordBase $word_result; 
    
    use get_stmt_trait;
 
    public function __construct(\PDO $pdo, array $row)
    {
       $this->iter = match(Pos::from($row['pos'])) {

           Pos::noun => $this->get_noun_iterator($pdo, $row),
           Pos::verb => CreateDBWordResultIterator::VerbGenerator($pdo, $this->fetchRows($pdo, 'sql_verb', $row['word_id'])),
           default => CreateDBWordResultIterator::SingleResultGenerator(new DBWord($pdo, $row))
       };
    }
    
    function get_noun_iterator(\PDO $pdo, array $row) : \Iterator
    {
        $rows = $this->fetchRows($pdo, 'sql_noun', $row['word_id']);
        
        return CreateDBWordResultIterator::SingleResultGenerator(new DBNoun($pdo, $rows[0]));
    }
   
    function bind(\PDOStatement $stmt) : void
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
    public static function VerbGenerator(\PDO $pdo, array $rows) : \Iterator 
    {
       foreach($rows as $index => $current) {
            
          yield new DBVerb($pdo, $current); 
       }
    }   
    
    function getIterator() : \Iterator
    {
        return $this->iter;
    }

    public static function SingleResultGenerator(DBWord $dbword) : \Iterator
    {
        yield $dbword;
    }   
}
