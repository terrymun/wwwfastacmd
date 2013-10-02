<?php

// Start session
session_start();

// Include important files. Again, double check $path (by echoing it or anything) to be sure
$path = $_SERVER['DOCUMENT_ROOT'];

// If get variables db and id are defined
if(isset($_GET['db']) && isset($_GET['id'])) {
	if(!empty($_GET['db']) && !empty($_GET['id'])) {
		$db = $_GET['db'];
		$id = $_GET['id'];
		$key = 0;
		$out = '';
		if(isset($_GET['type'])) {
			$type = $_GET['type'];
		} else {
			$type = 1;
		}
		
		// Format input for IDs so they're separated by commas
		$id_pattern = array(
			'/ *[\r\n]+/',		// Checks for one or more line breaks
			'/(\w)\s+(\w)/',	// Checks for words separated by one or more spaces
			'/,\s*/',			// Checks for words separated by comma, but with variable spaces
			'/,\s*,/'			// Checks for empty strings (i.e. no words between two commas)
			);
		$id_replace = array(
			',',
			'$1, $2',
			',',
			','
			);

	} elseif(empty($_GET['db'])) {
		$input_err = 'You have not selected a database.';
	} elseif(empty($_GET['id'])) {
		$input_err = 'You have not entered a search ID.';
	} else {
		$input_err = 'You have not specified a database to use and a search ID.';
	}

	// If strand is not specified, use the (+) strand
	if(!empty($_GET['st'])) {
		$st = $_GET['st'];
	} else {
		$st = "+";
	}

	// If starting and ending positions are specified
	if(isset($_GET['from']) && isset($_GET['to'])) {
		$pre_from = intval($_GET['from']);
		$pre_to = intval($_GET['to']);

		// Sanity check for from and to positions if they have been specified by user
		if($pre_from > $pre_to) {
			// If from and to positions are switched, get minus strand ONLY if auto option is on
			$from = $pre_to;
			$to = $pre_from;
			if($st == 'auto') {
				$st = "-";
			}
		} elseif($pre_from == $pre_to && $pre_from !== 0) {
			// If from and to positions are identical
			$input_err = 'Your start and end positions are identical.';
		} else {
			// If from and to positions are in the correct order, get plus strand ONLY if auto option is on
			$from = $pre_from;
			$to = $pre_to;
			if($st == 'auto') {
				$st = "+";
			}
		}
	} else {
		// If from and to positions are not specified
		$from = 0;
		$to = 0;
		if($st == 'auto') {
			$st = "+";
		}
	}

	// If an error flag is raised, write error message to PHP session cookie and redirect to first page
	if(isset($input_err)) {
		$_SESSION['fasta_input_err'] = $input_err;
		session_write_close();
		header('location: fastacmd.php');
		exit();
	}

	// If everything is okay, run the script and assign each new line to a new element in $output array
	// Remember to specify the absolute path to the fastacmd - a requirement of PHP exec() function
	exec('perl /absolute/path/to/fastacmd.cgi'.' '.escapeshellarg($db).' '.escapeshellarg($id).' '.escapeshellarg($from).' '.escapeshellarg($to).' '.escapeshellarg($st), $output);

	// If there is an error with the script output
	if(!empty($output)) {
		if($output[0] == 'ERR') {
			$_SESSION['fasta_err'] = array($output[1], $output[2]);
			session_write_close();
			header('location: fastacmd.php');
			exit();
		}
	} else {
		$_SESSION['fasta_err'] = array("The script has failed to execute. Please contact system administrator.");
		session_write_close();
		header('location: fastacmd.php');
		exit();
	}

	// If all is well, decide action allow script to proceed
	// Parse $output
	$key = 0;
	$out = '';
	$output_id_arr = array();
	$input_id_arr = explode(",", $id);
	$mismatch_count = 0;

	foreach($output as $line) {
		if(stripos($line, 'lcl|')) {
			// Construct accessions obtained from the fastacmd output
			$output_id_arr[] = preg_replace("/>lcl\|([0-9a-z\._]+)\s.*/i", "$1", $line);

			// If current array does not match the item in the $input_id_arr array, remove it from the latter
			if($output_id_arr[count($output_id_arr)-1] != $input_id_arr[count($output_id_arr)-1+$mismatch_count]) {
				$mismatch_count++;
			}

			// Writing the first line for each accession
			// If it is the very first accession on the list, do nothing... if not, close the previous tags
			// And then append the <h3> header
			$out .= ($key == 0 ? '' : '</code></pre>').'<h3 id="accession-'.($key == 0 ? '0' : $key).'">Accession: <code>'.$input_id_arr[$key+$mismatch_count].'</code></h3><ul class="accession-nav"><li class="top"><a href="#wrap" title="Return to top"><span class="pictogram icon-up-open-big"></span>Back to Top</a></li><li class="accession-next"><a href="#accession-'.($key+1).'" title="Go to the next accession"><span class="pictogram icon-down-open-big"></span>Next accession</a></li><li class="accession-prev"><a href="#accession-'.($key-1).'" title="Go to the previous accession"><span class="pictogram icon-up-open-big"></span>Previous accession</a></li></ul><pre><code>'.$line.'<br />';

			// Increase accession count
			$key++;
		} else {
			// Write the rest of the output as usual
			$out .= $line.'<br />';
		}
	}	

	$out .= '</code></pre>';

	// Generate list for accessions whose sequences are not found
	$id_notfound = array_diff($input_id_arr, $output_id_arr);
	foreach($id_notfound as &$notfound_item) {
		$notfound_item = '<li><code>'.$notfound_item.'</code></li>';
	}

}

// If the user is accessing the page directly
else {
	$_SESSION['fasta_input_err'] = "Direct access to script is forbidden. Please complete the following form prior to submission.";
	session_write_close();
	header('location: fastacmd.php');
	exit();
}

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
        <p>The <a href="https://github.com/aubombarely/wwwfastacmd">wwwfastacmd project</a> developed by Aureliano Bombarely.</p>
    </header>

	<section>
		<p>Sequence retrieval from the database has been successful. We have retrieved <?php echo $key; ?> FASTA <?php echo pl($key, 'sequence','sequences'); ?> with your search query. You may <a href="fastacmd.php">return and search again</a> should you wish to.</p>

		<h2><a href="#" title="Repeat your search, or retrieve more sequences">Retrieve more sequences</a></h2>
		<p>You can repeat your search, or retrieve more sequences if you have any other accessions/gis.</p>
		<?php include('seqret-form.php'); ?>

		<h2>User settings</h2>
		<ul>
			<li>Database: <code><?php echo $db; ?></code></li>
			<li>Strand: <code><?php echo $st; ?></code></li>
			<?php if($from !== $to) {
				echo "<li>Range: from <code>".$from."</code> to <code>".$to."</code></li>";
			} else {
				echo "<li>Range: <code>All</code></li>";
			}?>
			<li><?php echo "Retrieved sequences for ".count($output_id_arr)." ".pl(count($output_id_arr), 'accession', 'accessions').':'; ?>:
				<ol>
					<?php
						// Generate accession list
						$id_count = 0;
						foreach($output_id_arr as $id_item) {
							$id_item = '<li><a href="#accession-'.$id_count.'"><code>'.$id_item.'</code></a></li>';
							echo $id_item;

							$id_count++;
						}
					?>
				</ol>
			</li>
		</ul>

		<?php
			if(count($id_notfound) > 0) {
		?>
		<h2>Accessions that failed to return results</h2>
		<p>Unfortunately, we are unable to retrieve the <?php echo pl(count($id_notfound),"sequence","sequences");?> for the following accession <?php echo pl(count($id_notfound),"number","numbers");?>:</p>
		<ol class="accession-list">
			<?php echo implode($id_notfound); ?>
		</ol>
		<?php
			}
		?>

		<div>
			<?php echo $out; ?>
		</div>
	</section>

	<footer>
		<!-- Insert footer content -->
	</footer>
</body>
</html>
