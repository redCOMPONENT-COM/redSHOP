<?php
/**
 * @package     RedShop
 * @subpackage  Report
 * @copyright   Copyright (C) 2012 - 2014 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

$logFileExist = true;

// Check if System Test Log file exist
if (!file_exists('../logs/junit.xml'))
{
	$logFileExist = false;
}
else
{
	$xmlinfo = new SplFileInfo('../logs/junit.xml');

	// Check XML is empty
	if (!$xmlinfo->getSize() > 0)
	{
		$logFileExist = false;
	}
	else
	{
		$xml = simplexml_load_file('../logs/junit.xml');
	}
}


?>
<!DOCTYPE html>
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
	<body>
		<?php if (!$logFileExist) : ?>
			<h1>Please execute System Test and wait until the execution has finished to view the report</h1>
			<h2>For more details about how to run system tests please read here
				<a href="https://redweb.atlassian.net/wiki/display/RSBTB/2013/12/06/Running+System+Tests%2C+Automation+Testing+for+RedshopB2B"> System Test</a>
			</h2>
		<?php else : ?>
		<table class="table table-striped table-hover">
			<thead>
				<tr>
					<th>Name</th>
					<th>Assertions</th>
					<th>Time</th>
					<th>Tests</th>
					<th>Failures</th>
					<th>Errors</th>
				</tr>
			</thead>
			<tbody>
			<?php foreach ($xml->xpath('//testsuite') as $testsuite) : ?>
				<tr>
					<td>
						<?php echo $testsuite->attributes()->name; ?> (<?php echo $testsuite->attributes()->tests; ?> tests)
					</td>
					<td>
						<?php echo $testsuite->attributes()->assertions; ?>
					</td>
					<td>
						<?php echo round($testsuite->attributes()->time); ?> seconds
					</td>
					<td>
						<?php foreach ($testsuite->testcase as $testcase) : ?>
							<?php echo $testcase->attributes()->name; ?><br/>
						<?php endforeach; ?>
					</td>
					<td>
						<?php echo $testsuite->attributes()->failures; ?>
					</td>
					<td>
						<?php echo $testsuite->attributes()->errors; ?>
					</td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
		<?php endif; ?>
	</body>
</html>
