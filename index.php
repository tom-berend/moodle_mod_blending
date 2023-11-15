<?php

namespace Blending;


// this is only used for starting the debugger.  of course you can't
// use any MOODLE functions because Moodle isn't in the environment.

$GLOBALS['isTesting'] = true;        // set to false for production
$GLOBALS['cmid'] = 99;

echo '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">';
echo "<h1>Running DEBUGGER - moodle ID =999, session = 'abc'</h1>";
session_start();

// we don't use the FORMS API for this plugin, so we need these two values

require_once('source/controller.php');
$c = new Controller();
$content =  $c->controller('introduction','','');

echo $content;

echo '<br>all done';
return;













