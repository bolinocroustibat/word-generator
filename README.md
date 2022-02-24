# Word Generator

Generates random words that don't exist in the dictionnary, but look and sound like them!
- in English
- in French
- in Spanish (because it's a language easy to make fun of)
- in Italian

Written in PHP. You read it right, this is some Natural Language Processing in PHP.
I also wrote the same in Python (you're relieved, huh?), with more advanced NLP functionalities.


## How to use it

Just run it under as static web pages under a web server with PHP 7.x, and optionnally a MySQL database.

API endpoints:
- `/en/` (*json*): returns a random English-sounding word, along with its type and tense if applicable
- `/fr/` (*json*): returns a random French-sounding word, along with its type, gender, tense and conjugation if applicable
- `/es/` (*json*): returns a random Spanish-sounding word
- `/it/` (*json*): returns a random Italian-sounding word

You can build/rebuid the probability data using the following scripts, can be run as web pages:
- `/en/build.php`
- `/fr/build.php`
- `/es/build.php`
- `/it/build.php`


## Database

It will also save the generated word in a DB, if it can connect to a valid one. Add a `common/connect.php` PHP file with a `databaseConnect()` DB connection function sending back a PDO DB instance. For example:
```php
<?php
function databaseConnect(){
	try {
		$db = new PDO('mysql:host=localhost;port=8889;dbname=words', 'root', 'root');
		$db->exec("SET CHARACTER SET utf8");
	}
	catch(Exception $e) {
		die('Error : '.$e->getMessage());
	}
	return $db;
}
?>
```
For French words, it will associate the saved ones with their *presumed* type, gender, tense and conjugation.
