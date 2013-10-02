# wwwfastacmd

## 1. Introduction
The fastacmd tool was developed by Aureliano Bombarely to interface with NCBI's wwwblast directly. However, in my project, I was tasked to implement this tool separately from BLAST. Since I am maintaining an intranet site on an Apache server, I have decided to use PHP to interface with the wwwfastacmd script (`fastacmd.cgi`) directly.

The code has been updated to be HTML5 compliant.

## 2. File list
* fastacmd.php
* fastacmd.cgi
* fastacmd-exec.php
* fastacmd-form.php

You should note that there are no stylesheet provided. You can style the output in any way you want.

## 3. Installation instruction
1. Download the files. You can place the files anywhere on your server.
2. You will have to know where the BLAST executables are installed in your system. You will need the path to the blast executables (`/path/to/blast/bin`) so that you can set the environment in the CGI script.
3. Add the list of databases that you want to include in your fastacmd in `fastacmd-form.php`, for example:

    <select id="seqret-db" name="db">
        <option value="[database_name.fa]">Database description</option>
    </select>