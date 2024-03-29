<?php
declare(strict_types=1);
namespace Vocab;

class WordResultInserter implements InserterInterface {

    private Database $db;

    public function __construct(Database $database)
    {
       $this->db = $database;
    }

    function insert_examples(string $word, SentencesIterator $iter) : bool
    {
       return $this->db->insert_examples($word, $iter);
    }

    function insert_word(WordInterface $wrface) : int
    {
        return $this->db->insert_word($wrface);
    }

    function insert_verb(WordInterface $wrface) : int
    {
        return $this->db->insert_verb($wrface);
    }

    function insert_noun(WordInterface $wrface) : int
    {
        return $this->db->insert_noun($wrface);
    }
    
    function insert_related_verb(WordInterface $wrface) : int
    {
        return $this->db->insert_related_verb($wrface);
    }
}
