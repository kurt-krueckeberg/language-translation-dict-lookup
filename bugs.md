# Bugs

If Leipzig can't find sentences for a word, it will result in Guzzle throwing a 404 error. This error meansthe requested resource was "not found"; i.e., there 
are no sample sentences of this word.

So we need to surround the sample sentence request with a check for 404, and translate that into an error message nad simply continue.

