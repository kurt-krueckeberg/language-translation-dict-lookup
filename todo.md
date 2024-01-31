# Issues

Make the design configuration driven by:

* Add file name of words to lookup 
* Add the number of example sentences

## Summary of prefix verb issues:

* For prefix verbs, there are lookup results returned with a tilde, like 'an~kommen', and a non-tilde version 'ankommen'. Only 'ankommen' has the conjugation. Their definitions are sometimes repeated.

* A few such prefix verbs, like herein~kommen have a non tilde versions, hereinkommen, but hereinkommen does not have a conjugation.

* Some tilde prefix verbs don't have a non-tilde version. Some are obvious, like rein~kommend and raus~kommen, because they are spoken versions. Others just don't have a non-tilde version.

## Plan

For paired tilde and non-tilde versions:

1. Merge definitions of tilde versions that have a non-tilde pair, which has a conjugation, only save the non-tilde version after merging definitions.
2. For those tilde versions with a pair that has no conjugation, do likewise and merge definition, but share the main verb's conjugation.

For tilde versions without a pair:

Save it as a separate verb and use the main verbs conjugation.

## Summary of What to Do

Thus we have this to do:

1. Save the verb and definitions as follows:
- merge the tilde definitions into the non-tilde version, and remove the tilde version. <-- DONE
- if no non-tilde pair, simply save the tilde version (without the tilde). <--DONE

2. conjugation() function is:

  if (empty($match['source']['inflection'])
       use main verb's conjugation
  else
       use the conjugation we have
