<?php
header( 'Content-Type: text/javascript;' );
if( file_exists( dirname(__FILE__).'/../../configuration.php' )) {
	require( dirname(__FILE__).'/../../configuration.php' );
} elseif( file_exists( dirname(__FILE__).'/../../../configuration.php' )) {
	require_once(dirname(__FILE__).'/../../../configuration.php');
} else {
	die();
}

if( isset( $mosConfig_live_site ) ) {
	// We are in Joomla! 1.0.x
	$icon_path = $mosConfig_live_site . '/modules/tigratree/icons';
	
} else {
	// We are in Joomla! 1.5+
	
	// Define necessary constants
	define('_JEXEC', 1);
	define('JPATH_BASE', dirname(__FILE__) . '/../../../');
	define('DS', DIRECTORY_SEPARATOR);
	
	// Initialize the framework
	require_once ( JPATH_BASE . DS . 'includes' . DS . 'defines.php' );
	require_once ( JPATH_BASE . DS . 'includes' . DS . 'framework.php' );
	
	// Get a $mainframe
	$mainframe = & JFactory::getApplication( 'site' );

	// Get the appropriate url	
	$url = $mainframe->isAdmin() ? $mainframe->getSiteURL() : JURI::base();
	$mosConfig_live_site = substr_replace($url, '', -1, 1);
	$icon_path = $mosConfig_live_site . '/icons';
}

?>
/*
	Feel free to use your custom icons for the tree. Make sure they are all of the same size.
	User icons collections are welcome, we'll publish them giving all regards.
*/

var TREE_TPL = {
	'target'  : '_self',	// name of the frame links will be opened in
							// other possible values are: _blank, _parent, _search, _self and _top

	'icon_e'  : '<?php echo $icon_path ?>/empty.gif', // empty image
	'icon_l'  : '<?php echo $icon_path ?>/line.gif',  // vertical line

        'icon_32' : '<?php echo $icon_path ?>/base.gif',   // root leaf icon normal
        'icon_36' : '<?php echo $icon_path ?>/base.gif',   // root leaf icon selected

	'icon_48' : '<?php echo $icon_path ?>/base.gif',   // root icon normal
	'icon_52' : '<?php echo $icon_path ?>/base.gif',   // root icon selected
	'icon_56' : '<?php echo $icon_path ?>/base.gif',   // root icon opened
	'icon_60' : '<?php echo $icon_path ?>/base.gif',   // root icon selected
	
	'icon_16' : '<?php echo $icon_path ?>/folder.gif', // node icon normal
	'icon_20' : '<?php echo $icon_path ?>/folderopen.gif', // node icon selected
	'icon_24' : '<?php echo $icon_path ?>/folderopen.gif', // node icon opened
	'icon_28' : '<?php echo $icon_path ?>/folderopen.gif', // node icon selected opened

	'icon_0'  : '<?php echo $icon_path ?>/page.gif', // leaf icon normal
	'icon_4'  : '<?php echo $icon_path ?>/page.gif', // leaf icon selected
	
	'icon_2'  : '<?php echo $icon_path ?>/joinbottom.gif', // junction for leaf
	'icon_3'  : '<?php echo $icon_path ?>/join.gif',       // junction for last leaf
	'icon_18' : '<?php echo $icon_path ?>/plusbottom.gif', // junction for closed node
	'icon_19' : '<?php echo $icon_path ?>/plus.gif',       // junctioin for last closed node
	'icon_26' : '<?php echo $icon_path ?>/minusbottom.gif',// junction for opened node
	'icon_27' : '<?php echo $icon_path ?>/minus.gif'       // junctioin for last opended node
};
