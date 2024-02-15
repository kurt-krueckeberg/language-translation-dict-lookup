# Issues

The new design of DBVerb -- and yet to do DBNoun and DBWord -- has DBVerb implement the \Iterate interface to return each definition (as a DefinitionInterface).
The DefinitionInterface returns expressions. 

The implementation to retrieve definitions and expressions does not differ between nouns and verbs and just ordinary words; howerver, if you want, you can do 
a left join and tack on the expressions in one big querif you want.

