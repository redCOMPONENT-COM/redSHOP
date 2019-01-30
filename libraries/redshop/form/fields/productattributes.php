<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Element
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JFormHelper::loadFieldClass('list');

/**
 * Renders product attribute name list
 *
 * @since  2.1.2
 */
class JFormFieldProductattributes extends JFormFieldList
{
	/**
	 * A flexible category list that respects access controls
	 *
	 * @var    string
	 * @since  2.1.2
	 */
	public $type = 'productattributes';

	public $product_ids = array();

	/**
	 * Set select list options
	 *
	 * @return  array  select list options
	 */
	public function getOptions()
	{
		$db       = JFactory::getDbo();
		$subQuery = "SELECT a.product_id, ap.attribute_id, a.attribute_name, ap.property_name 
					FROM #__redshop_product_attribute_property ap LEFT JOIN #__redshop_product_attribute a 
					ON a.attribute_id = ap.attribute_id WHERE a.attribute_published = 1 AND a.product_id > 0 ";

		if (!empty($this->product_ids))
		{
			$subQuery .= " AND a.product_id IN (" . implode(',', $this->product_ids) . ") ";
		}

		$query = "SELECT DISTINCT uniq_att.attribute_name AS text, uniq_att.attribute_name AS value FROM (" . $subQuery . ") AS uniq_att";

		$attributes = $db->setQuery($query)->loadObjectList();

		return array_merge(parent::getOptions(), $attributes);
	}

	/**
	 * Method to get a control group with label and input.
	 *
	 * @param   array $options Options to be passed into the rendering of the field
	 *
	 * @return  string  A string containing the html for the control group
	 *
	 * @since   3.7.3
	 */
	public function renderField($options = array())
	{
		if (!empty($options) && isset($options['product_ids']))
		{
			$this->product_ids = $options['product_ids'];
		}

		return parent::renderField($options);
	}
}
