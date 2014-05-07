<?php
/**
 * @package     RedShop
 * @subpackage  Report
 * @copyright   Copyright (C) 2012 - 2014 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
?>

<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1\">
		<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
		<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap-theme.min.css">
		<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
	</head>
</html>
<?php
$attributes = array("name", "tests", "assertions", "failures", "errors");

if (file_exists('../logs/junit.xml'))
{
	$xml = simplexml_load_file('../logs/junit.xml');
?>
	<table class="table table-striped">
		<thead>
			<tr>
				<th>Name</th>
				<th>Tests</th>
				<th>Assertions</th>
				<th>Failures</th>
				<th>Errors</th>
			</tr>
		</thead>
		<tbody>
<?php
	if (isset($xml->testsuite[0]->testsuite[0]))
	{
		$count = $xml->testsuite[0]->children()->count();
	}
	else
	{
		$count = 1;
	}

	for ($i = 0; $i < $count; $i++)
	{
		echo "<tr>";

		if (isset($xml->testsuite[0]->testsuite[$i]))
		{
			foreach ($xml->testsuite[0]->testsuite[$i]->attributes() as $a => $b)
			{
				if (in_array($a, $attributes))
				{
					echo "<td>";
					echo $b;
					echo "</td>";
				}
			}
		}
		else
		{
			foreach ($xml->testsuite[0]->attributes() as $a => $b)
			{
				if (in_array($a, $attributes))
				{
					echo "<td>";
					echo $b;
					echo "</td>";
				}
			}
		}

		echo "</tr>";
	}

	echo "</tbody>";
	echo "</table>";
}
else
{
?>
	<h1>Please execute System Test! to view the report</h1>
	<h2>For more details about how to run system tests please read here
		<a href="https://redweb.atlassian.net/wiki/display/RSBTB/2013/12/06/Running+System+Tests%2C+Automation+Testing+for+RedshopB2B"> System Test</a>
	</h2>
<?php
}
