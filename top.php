<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>UVM Sailing Event Submissions</title>
    <meta name="author" content="Ryan Potter">
    <meta name="description" content="UVM Sailing Regatta Submissions">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="images/burgee.png">
    <meta name="description" content="A database of regatta location descriptions from past events">

    <link rel="stylesheet" type="text/css"
          href="venue.css?version=<?php print time(); ?>">
    <link rel="stylesheet" type="text/css" media="(max-width: 800px)"
          href="mobile-venue.css?version=<?php print time(); ?>">
    <link rel="stylesheet" type="text/css" media="(max-width: 600px)"
          href="mobile-venue.css?version=<?php print time(); ?>">
</head>

<?php
print '<body>';
print PHP_EOL;
include 'connect-DB.php';
print PHP_EOL;
include 'header.php';
print PHP_EOL;
include 'nav.php';
print PHP_EOL;
?>
