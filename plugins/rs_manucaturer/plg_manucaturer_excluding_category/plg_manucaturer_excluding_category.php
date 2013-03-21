<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

// Import library dependencies
jimport('joomla.plugin.plugin');

class plgrs_manufacaturerplg_manucaturer_excluding_category extends JPlugin
{
	/**
	 * Constructor
	 *
	 * For php4 compatability we must not use the __constructor as a constructor for
	 * plugins because func_get_args ( void ) returns a copy of all passed arguments
	 * NOT references.  This causes problems with cross-referencing necessary for the
	 * observer design pattern.
	 */
	function plgrs_manufacaturerplg_manucaturer_excluding_category(&$subject)
	{
		parent::__construct($subject);

		// load plugin parameters
//	    $this->_plugin = JPluginHelper::getPlugin( 'rs_manufacaturer', 'onPrepareProduct' );
//	    $this->_params = new JRegistry( $this->_plugin->params );
	}
}

?>