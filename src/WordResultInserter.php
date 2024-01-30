<?php
declare(strict_types=1);
namespace Vocab;

class WordResultInserter implements InserterInterface {

    private Database $db;

    public function __construct(Database $database)
    {
       $this->db = $database;
    }

    function insert_word(WordResultInterface $wrface) : int
    {
        return $this->db->insert_word($wrface);
    }

    function insert_verb(WordResultInterface $wrface) : int
    {
        return $this->db->insert_verb($wrface);
    }

    function insert_noun(WordResultInterface $wrface) : int
    {
        return $this->db->insert_noun($wrface);
    }
    
    function insert_related_verbs(WordResultInterface $wrface) : int
    {
        return $this->db->insert_related_verbs($wrface);
    }
}
