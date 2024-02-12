# Issues

I believe I want both the dictionary lookup results and returning existing words stored in the database to be the same interface or same array.

This involves a redesign.

Word {

string $word;
POS    $pos

  definitions() : DefinitionsIterator
}
