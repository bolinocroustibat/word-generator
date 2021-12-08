# Word Generator

Generates random words that don't exist in the dictionnary, but look and sound like them!
- in English
- in French

Written in PHP. You read it right, this is some Natural Language Processing in PHP.
I also wrote the same in Python (you're relieved, huh?), with more advanced NLP functionalities.


## How to use it

Just run it under as static web pages under a web server with PHP 7.x, and optionnally a MySQL database.

API endpoints:
- `/en/get_word.php` (*json*): returns a random English-sounding word
- `/fr/get_word.php` (*json*): returns a random French-sounding word

You can build/rebuid the probability data using the following scripts, to be run as web pages:
- `/en/build.php`
- `/fr/build.php`


## Database

It will also save the generated word in a DB, if it can connect to a valid one.
For French words, it will associate them with their *presumed* gender, tense and conjugation.
