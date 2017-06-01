<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Element
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JFormHelper::loadFieldClass('list');

/**
 * Renders a Productfinder Form
 *
 * @package     RedSHOP.Backend
 * @subpackage  Element
 * @since       1.5
 * @deprecated  1.6  Use JFormFieldExtraFields (extrafields) instead.
 */
class JFormFieldExtraFieldShipping extends JFormFieldList
{
	/**
	 * Element name
	 *
	 * @access    protected
	 * @var        string
	 */
	public $type = 'extrafieldshipping';

	/**
	 * Get Extra field info as an option
	 *
	 * @return  array  Extra Field list
	 */
	protected function getOptions()
	{
		// Initialiase variables.
		$db    = JFactory::getDbo();

		// Create the base select statement.
		$query = $db->getQuery(true)
			->select('name as value, title as text')
			->from($db->qn('#__redshop_fields'))
			->where($db->qn('published') . ' = 1')
			->where($db->qn('show_in_front') . ' = 1')
			->where($db->qn('section') . ' = 19')
			->order($db->qn('ordering') . ' ASC');

		// Set the query and load the result.
		$db->setQuery($query);

		return array_merge(
			parent::getOptions(),
			$db->loadObjectList()
		);
	}
}
