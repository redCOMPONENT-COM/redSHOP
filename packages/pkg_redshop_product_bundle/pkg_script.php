<?php
/**
 * @package    RedSHOP.Installer
 *
 * @copyright  Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Script file of redSHOP Package
 *
 * @package  RedSHOP.Installer
 *
 * @since    1.2
 */
class pkg_Redshop_Product_BundleInstallerScript
{
	/**
	 * Method to install the component
	 *
	 * @param   object  $parent  Class calling this method
	 *
	 * @return  void
	 */
	public function install($parent)
	{
		$this->enablePlugin('bundle', 'redshop_product_type');
		$this->enablePlugin('bundle', 'redshop_product');
		$this->enablePlugin('redshop_product_bundle', 'system');
	}

	/**
	 * Method for enable plugins
	 *
	 * @param   string  $extName   Plugin name
	 * @param   string  $extGroup  Plugin group
	 * @param   int     $state     State of plugins
	 *
	 * @return mixed
	 */
	protected function enablePlugin($extName, $extGroup, $state = 1)
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->update($db->qn("#__extensions"))
			->set("enabled = " . (int) $state)
			->where('type = ' . $db->quote('plugin'))
			->where('element = ' . $db->quote($extName))
			->where('folder = ' . $db->quote($extGroup));

		return $db->setQuery($query)->execute();
	}
}
