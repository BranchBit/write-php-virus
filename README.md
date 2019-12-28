# Write a php virus for fun
This is a steps to write a php virus taken from Ben Dechrai talk at linux.conf.au [here](https://www.youtube.com/watch?v=2Ra1CCG8Guo)


## Step 1  
The `boom.php` reads all php files and add `<?php // FILE INFECTED ?>` line to the begining of each file. 


## Step 2  
The `boom.php` reads itself and copy the virus code and inject it into all php files. 

## Step 3  
The `boom.php` reads itself and encrypt the encode itself using base64 then injects itselft into all php files. 

In all cases the virus infects a file multiple times. 

## Step 4  
Same as step3, but this time it check first for the filename hash in the first line of a php file as a marker of an infected file. If not infected then it add the filename hash to the first line then do as in step3. 



