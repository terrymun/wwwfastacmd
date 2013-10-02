<form action="seqret-exec.php" method="get" id="seqret-form">
	<label for="seqret-db">Database</label>
	<select id="seqret-db" name="db">
		<!-- The following line is simply a sample of how your <option> entries should look like -->
		<option value="db.fa">Database Description</option>
	</select>

	<label for="seqret-id">Accession / GI</label>
	<textarea id="seqret-id" type="text" name="id" rows="5" placeholder="Enter accession number or GI here"></textarea>
	<small>Enter each accession number or GI on a new line./small>

	<label for="seqret-from" class="col-one">Set subsequence</label>
	<label for="seqret-from">Starting from</label><input type="number" id="seqret-from" name="from" placeholder="Start Position" min="0" />
	<label for="seqret-to">to</label><input type="number" id="seqret-to" name="to" placeholder="End Position" min="0" />
		
	<label for="seqret-strand" class="col-one">Strand</label>
	<select id="seqret-strand" name="st">
		<option value="+">Manual: Plus or (+) or 1</option>
		<option value="-">Manual: Minus or (-) or 2</option>
	</select>

	<input type="submit" value="Retrieve Sequences" />
</form>
