<?php
/**
 * $ModDesc
 *
 * @version        $Id: helper.php $Revision
 * @package        modules
 * @subpackage     $Subpackage
 * @copyright      Copyright (C) May 2010 LandOfCoder.com <@emai:landofcoder@gmail.com>. All rights reserved.
 * @website    htt://landofcoder.com
 * @license        GNU General Public License version 2
 */
// no direct access
defined('_JEXEC') or die;

class JFormFieldRedshopcategoryremove extends JFormField
{

	/**
	 * @access private
	 */
	var $_name = 'redshopcategoryremove';

	function getInput()
	{
		$db = JFactory::getDbo();
		if (!is_dir(JPATH_ADMINISTRATOR . '/components/com_redshop')) return JText::_('COM_REDSHOP_REDSHOP_IS_NOT_INSTALLED');
		if (!is_array($this->value))
		{
			$this->value  = array('' => '1');
		}
		else
		{
			foreach ($this->value as $_k => $tmpV)
			{
				$this->value[$tmpV] = $tmpV;
			}
		}
		$option = JRequest::getInt('option');
		if ($option != 'com_redshop')
		{
			require_once JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/redshop.cfg.php';
			JLoader::import('loadhelpers', JPATH_SITE . '/components/com_redshop');
			JLoader::load('RedshopHelperAdminConfiguration');
			$Redconfiguration = new Redconfiguration;
			$Redconfiguration->defineDynamicVars();
		}

		JLoader::load('RedshopHelperAdminCategory');
		$product_category = new product_category;
		ob_start();
		$output = $product_category->list_all('' . $this->name . '[]', '', ($this->value), 10, true, true);
		ob_end_clean();

		return $output;
	}

}
