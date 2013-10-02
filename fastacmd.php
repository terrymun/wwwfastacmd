<?php
	// Start session
	session_start();
	
	// Please check if the path is correct — may be problematic if you are installing your site in a sub-directory instead of in root
	$path = $_SERVER['DOCUMENT_ROOT'];
?>
<!doctype html>
<html lang="en">
<head>
	<title>wwwfastacmd Tool</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
</head>

<body class="fastacmd">
    <header>
        <h1>wwwfastacmd Tool</h1>
    </header>

	<section>
		<h2>Sequence Retrieval Tool (SeqRet)</h2>
		<p>This tool allows you to extract sequences by providing the corresponding identification information. This tool is built upon <a href="https://github.com/aubombarely/wwwfastacmd">wwwfastacmd project</a> developed by Aureliano Bombarely.</p>
		<?php
		
		// If an input error is detected (i.e. user input failed server-side validation
		if(isset($_SESSION['fasta_input_err'])) {
			echo '<p class="user-message warning fasta-input-err">'.$_SESSION['fasta_input_err'].'</p>';
			unset($_SESSION['fasta_input_err']);
		}
		
		// If there is an error with executing the fastacmd command itself
		if(isset($_SESSION['fasta_err'])) {
			$err = $_SESSION['fasta_err'][0];
			if(preg_match("/no sequence was found/i", $err)) {
				$err = str_replace("<code>", "<br /><code>", $err);
				$err = str_replace(",", "</code><code>", $err);
			}
			echo '<p class="user-message warning fasta-err">'.$err.'</p>';
			unset($_SESSION['fasta_err']);
		}

		// Include form
		include('seqret-form.php');

		?>
	</section>

	<footer>
	    <!-- Insert footer content -->
	</footer>
</body>
</html>
