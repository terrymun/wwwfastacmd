# wwwfastacmd

## Introduction
The fastacmd tool was developed by Aureliano Bombarely to interface with NCBI's wwwblast directly. However, in my project, I was tasked to implement this tool separately from BLAST. Since I am maintaining an intranet site on an Apache server, I have decided to use [PHP](http://php.net/manual/en/) to interface with the wwwfastacmd script (`fastacmd.cgi`) directly.

The script relies on using the [exec() function](http://php.net/manual/en/function.exec.php) in PHP to run the fastacmd command and return the output. Therefore, **safe_mode** has to be disabled in your directory. See [installation instructions](#installation-instructions) for more information.

The code has been updated to be HTML5 compliant.

## Requirements
As HTML5 is used as the markup language, you will need to enable support for HTML5 in older browsers, although this has not been a major issue in my implementation, as most of my users are using browsers that support the new `<!doctype html>` declaration. If majority of your users are using antiquated versions of IE, encourage them to upgrade &mdash; if that is not possible, implement [HTML5shiv](https://code.google.com/p/html5shiv/) to allow IE to recognize HTML5 elements.

This project relies on PHP, so ensure that you have it installed on your server. If you are unsure if PHP is installed in your server, create a file with a line `<?php phpinfo(); ?>` and try to load the page in your browser. If a page displaying all the details of your PHP installation appears, it means that PHP has been installed correctly and is running on your server. I recommend using the latest version of PHP. This script has been tested to be working on v5.2 onwards, although most of the extensive testing has been carried out on v5.3.5.

The files can be open, edited and saved with any text editing software out there, although I personally use [Sublime Text](http://www.sublimetext.com/) because of it's extensive support for syntax highlighting in various languages.

## File list
The following files are necessary for fastacmd to work:

* fastacmd.php
* fastacmd.cgi
* fastacmd-exec.php
* fastacmd-form.php

You should note that there are no stylesheet provided. You can style the output in any way you want, although I strongly recommend using a [CSS reset](http://www.cssreset.com/).

## Installation instructions
1. Download the files. You can place the files anywhere on your server.

2. You will have to know where the BLAST executables are installed in your system. You will need the path to the blast executables, **/path/to/blast/bin**, so that you can set the environment in the CGI script.

3. Update the `$db_root` variable in **fastacmd.cgi** so that it points to the directory where your BLAST databases can be found.

4. Add the list of databases that you want to include in your fastacmd in **fastacmd-form.php**, for example:

        <select id="seqret-db" name="db">
            <option value="[database_name.fa]">Database description</option>
        </select>

   The database name should only contain the filename of the database file (typically in `.fa` - FASTA format). The absolute path to the databases are already handled in step 3.

5. Update the absolute path to **fastacmd.cgi** in **fastacmd-exec.php**. The absolute path is required by PHP `exec()` function to run the CGI file.

6. You will also need to disable the **safe_mode** in PHP, or else the PHP `exec()` function will not work. Please contact your system/server administrator for more details.
