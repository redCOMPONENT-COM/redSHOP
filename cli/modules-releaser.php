<?php
/*
 * IMPORTANT NOTE: this script remove folders, so don't use unless you know what are you doing
 * Command line script to remove all folders that are not for the release.
 */

// Add here the modules that have changed in the last release:
// You can use: git log --oneline --after={2013-09-2} --no-merges --name-only | grep 'modules\/.*' | cut -d/ -f3 | sort | uniq
$releasing_modules = array(
	'mod_icetabs',
	'mod_redcategoryscroller',
	'mod_redfeaturedproduct',
	'mod_redmanufacturer',
	'mod_redproducts3d',
	'mod_redproductscroller',
	'mod_redproducttab',
	'mod_redshop_categories',
	'mod_redshop_category_scroller',
	'mod_redshop_currencies',
	'mod_redshop_discount',
	'mod_redshop_lettersearch',
	'mod_redshop_products',
	'mod_redshop_products_slideshow',
	'mod_redshop_search',
	'mod_redshop_shoppergroup_category',
	'mod_redshop_shoppergroup_product',
	'mod_redshop_shoppergrouplogo',
	'mod_redshop_who_bought',
	'mod_redshop_wishlist',
);

$dir = opendir('./site/');

/**
 * Removes all contents of a folder and the foder itself
 *
 * @param   stdclass  $dir  The folder to remove
 *
 * @return void
 */
function deleteDirectory($dir)
{
	$iterator = new RecursiveIteratorIterator(
		new RecursiveDirectoryIterator($dir),
		RecursiveIteratorIterator::CHILD_FIRST
	);

	foreach ($iterator as $path)
	{
		if ($path->isDir())
		{
			rmdir($path->__toString());
		}
		else
		{
			unlink($path->__toString());
		}
	}

	rmdir($dir);
}

$path = realpath('./site/');

$objects = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path), RecursiveIteratorIterator::SELF_FIRST);

foreach($objects as $name => $object)
{
	if ($object->isDir())
	{
		$module_name = end(explode('/',$name));

		if (!in_array($module_name, $releasing_modules))
		{
			echo "deleting $name\n";
			deleteDirectory($object);
		}
	}
}

?>/