# Version deployment proof of concept
A proof of concept for a deployment system, based on a versioned system.

The system clones a new copy of the code into the releases folder, symlinks a current link to this folder and a previous
symlink to the last version.

For ease of use the deployment server doesnt need access to the git and uses a mirror on the destination server for
reference data and to speed up git clone on the destination server. 

## to do:-
- write error handling
- write more tests 
- optimise/tidy code, inc templates
- git diff between last and new deployment
- add commands and shared folder use
- test deployment and dont switch current link