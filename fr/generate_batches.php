<!DOCTYPE html>
<html lang="fr">

<head>
	<meta charset="UTF-8" />
</head>

<body>
	<center>
		<form action="generate_batches.php" method="post">
			<h2>Générer <input type="number" style="width: 4em;" name="words-number" value="1" max="100" /> mots <input type="submit" value="c'est parti"></h2>
			<hr />
			<h3>
				<?php
				if ($wordsNumber > 100) {
					$wordsNumber = 100;
				}
				echo "1 : ";
				include('../common/generate.php');
				echo "<br>";
				if (isset($_POST['words-number'])) {
					$wordsNumber = $_POST['words-number'];
					for ($i = 2; $i <= $wordsNumber; $i++) {
						echo $i . " : ";
						$json_filename = dirname(__FILE__) . '/data/proba_table_2char_FR.json';
						$string = generateWord($json_filename);
						storeWordInDB($string);
						echo $string;
						echo "<br>";
					}
				}
				?>
			</h3>
		</form>
	</center>
</body>

</html>