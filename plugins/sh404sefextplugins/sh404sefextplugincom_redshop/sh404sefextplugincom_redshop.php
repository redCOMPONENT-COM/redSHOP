<?php
/**
 * SEF module for redSHOP!
 *
 * @package     RedSHOP
 * @subpackage  Plugin.Sh404SEFExtPlugins
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 *
 * @since       1.0.0
 */

defined('_JEXEC') or die();

/**
 * Sample sh404SEF extension plugin.
 * This is a standard Joomla! plugin. Install it using
 * Joomla! installer. It will be loaded in the sh404sefextplugins group.
 *
 * Upon upgrading sh404SEF to a new version, this plugin will be
 * preserved, because it is independant from sh404SEF. This means you
 * can use this method to provide plugins for extension that do not
 * have support for sh404SEF, or just as well override one of the
 * plugins that come with sh404SEF, should you have a need for customized SEF URLs
 *
 * Note that the same mechanism applies for meta ext plugin. See the
 * source code file for the Sh404sefClassBaseextplugin class
 *
 * Note also that:
 *    - you do not need to include or require the Sh404sefClassBaseextplugin class file,
 *    sh404SEF autoloading mechanism takes care of everything
 *    - likewise, this plugin does not need to be published, it will
 *    be used even if unpublished. To deactivate it, just uninstall it
 *
 * @since  2.1
 */
class Sh404sefExtpluginCom_Redshop extends Sh404sefClassBaseextplugin
{
	/**
	 * Extension name.
	 *
	 * @var  string
	 * @deprecated  3.0
	 */
	protected $_extName = 'com_redshop';

	/**
	 * Standard constructor don't change
	 *
	 * @param   object  $option  Component name.
	 * @param   array   $config  Config settings.
	 */
	public function __construct($option, $config)
	{
		parent::__construct($option, $config);
		$this->_pluginType = Sh404sefClassBaseextplugin::TYPE_SH404SEF_ROUTER;
	}

	/**
	 * Adjust returned path to your own plugin. This method will be used to find the exact
	 * and full path to your plugin main file. The location used below is just a sample.
	 * Your plugin can be stored anywhere, and use as many files as you need. sh404SEF only
	 * needs to know about the main entry point.
	 *
	 * @param   array  $nonSefVars  An array of key=>values representing the non-sef vars of the url
	 *                              we are trying to SEFy. You can adjust the plugin used depending on the
	 *                              request being made (or other elements). For instance, you could use
	 *                              a different plugin based on the currently installed version of the extension
	 *
	 * @return  mixed
	 */
	protected function _findSefPluginPath($nonSefVars = array())
	{
		$this->_sefPluginPath = JPATH_ROOT . '/plugins/sh404sefextplugins/sh404sefextplugincom_redshop/redshop/sef.php';
	}

	/**
	 * Adjust returned path to your own plugin. This method will be used to find the exact
	 * and full path to your plugin main file. The location used below is just a sample.
	 * Your plugin can be stored anywhere, and use as many files as you need. sh404SEF only
	 * needs to know about the main entry point.
	 *
	 * @param   array  $nonSefVars  An array of key=>values representing the non-sef vars of the url
	 *                              we are trying to SEFy. You can adjust the plugin used depending on the
	 *                              request being made (or other elements). For instance, you could use
	 *                              a different plugin based on the currently installed version of the extension
	 *
	 * @return  mixed
	 */
	protected function _findMetaPluginPath($nonSefVars = array())
	{
		$this->_metaPluginPath = JPATH_ROOT . '/plugins/sh404sefextplugins/sh404sefextplugincom_redshop/redshop/meta.php';
	}
}