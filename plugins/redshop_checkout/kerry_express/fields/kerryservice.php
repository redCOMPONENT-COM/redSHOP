<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Element
 *
 * @copyright   Copyright (C) 2005 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JFormHelper::loadFieldClass('list');

/**
 * enders Kerry express city List
 *
 * @since  1.1
 */
class JFormFieldKerryservice extends JFormFieldList
{
	/**
	 * A flexible category list that respects access controls
	 *
	 * @var    string
	 * @since  1.6
	 */
	public $type = 'kerryservice';

	/**
	 * Method to get the field options.
	 *
	 * @return  array  The field option objects.
	 *
	 * @since   3.7.0
	 */
	protected function getOptions()
	{
		$list   = array();
		$i      = 0;
		$handle = $this->getServiceData();

		while ($result = fgetcsv($handle, null, ',', '"'))
		{
			if (!is_numeric($result[0]))
			{
				continue;
			}

			$list[$result[1]]['value'] = $result[0];
			$list[$result[1]]['text']  = $result[1];
			$i++;
		}

		return array_merge(parent::getOptions(), $list);
	}

	/**
	 * get Kerry Service List
	 *
	 * @return resource
	 */
	public function getServiceData()
	{
		$path   = JPATH_PLUGINS . '/redshop_checkout/kerry_express/data/service.csv';
		$handle = fopen($path, 'r');

		return $handle;
	}
}
