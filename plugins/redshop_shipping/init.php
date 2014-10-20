<?php

	$glob = glob(__DIR__ . '/*', GLOB_ONLYDIR);

	foreach ($glob AS $dir)
	{
		$local = $dir . '/language/';

		foreach ( array('da-DK', 'en-GB') AS $name)
		{
			$x = $local . $name . '/' . $name . '.plg_redshop_shipping_' . basename($dir) . '';

			#echo $x ."\n";
			foreach(array($x . '.ini', $x . '.sys.ini') As $file)
			{

				$open = file_get_contents($file);

				$cx = explode(".",basename($file));

				$open .= strtoupper($cx[1]) . '_DESC="This plugin enables ' . basename($dir) . ' Shipping"';

				file_put_contents($file, $open);
			}

			echo "\n";
		}
	}
?>