# RoT : Rot on Time

This is an extension of a tutorial by:

-- Ben Dechrai
-- "Writing Viruses for Fun, not Profit"
-- Posted by linux.conf.au on YOUTUBE


## 1st script
A simple, uncontrolled, replication instrument written in PHP
It prepends a PHP infection block to every .php file in the cwd

## 2nd script
Adds to the first, and it writes all code between '// VIRUS:START'
and '// VIRUS:END' and places it in that block. In this example, its a form 
of self-replication that wont overwrite itself (through the use of signatures).

## 3rd script 
Takes the code from the 2nd and implements a symmetric encryption mechanism.
Using PHP's MCRYPT module, it will encrypt the code between '// VIRUS:START'
and '// VIRUS:END' then write that string, with code to use an individualized key
to decrypt that encrypted code when it runs.

## 4th script
A variant of the 3rd script that uses PHP's OPENSSL modules (which
are more likely to be installed on a system) instead of MCRYPT.


## r0t.php: 	==(Work In Progress)==
 -- My personal variant of the 4th script, in which the program will produce a number 
 -- of functions based on the options it has planted, which could include:
	+ Reverse Shell
	+ Bind Shell
	+ p0wny Shell
	+ uncontrolled replication (Drive fill)
	+ Dank Memez
	+ Memory eating (playing with initmem levels)
	+ uncontrolled service creation (fork bomb? reboot repeat?)
	+ etc...
