# Issues

When a verb like kommen whose dicionary results include all prefix-verb version of kommen, then it is better to just
loop through the `Dataase::word_prim_keys` when building the .html output rather than looking up the words again.

Looking up words in the database it best done with a separate method.

## Common Word classes

I believe I want both the dictionary lookup results and returning existing words stored in the database to be the same interface or same array.

This involves a redesign.

Word {

string $word;
POS    $pos

  definitions() : DefinitionsIterator
}
