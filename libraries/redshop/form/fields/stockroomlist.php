<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Element
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::import('redshop.library');
JFormHelper::loadFieldClass('list');

// Load library language
$lang = JFactory::getLanguage();
$lang->load('com_redshop', JPATH_ADMINISTRATOR);

/**
 * Renders a Stockroom List
 *
 * @since  1.5.0.1
 */
class JFormFieldStockroomlist extends JFormFieldList
{
	/**
	 * Element name
	 *
	 * @access    protected
	 * @var        string
	 */
	protected $type = 'stockroomlist';

	/**
	 * A static cache.
	 *
	 * @var array|null
	 */
	protected static $cache = null;

	/**
	 * Method to get the field options.
	 *
	 * @return  array  The field option objects.
	 */
	protected function getOptions()
	{
		$options = array();

		if (!$this->multiple)
		{
			$options[] = JHTML::_('select.option', '', JText::_('COM_REDSHOP_SELECT_STOCKROOM'), 'value', 'text');
		}

		if (!self::$cache)
		{
			// Get the Stockroom.
			self::$cache = RedshopHelperStockroom::getStockroom();
		}

		// Build the field options.
		if (!empty(self::$cache))
		{
			if ($this->multiple)
			{
				$options[] = JHtml::_('select.optgroup', JText::_('COM_REDSHOP_SELECT_STOCKROOM'));
			}

			foreach (self::$cache as $item)
			{
				$options[] = JHtml::_('select.option', $item->stockroom_id, $item->stockroom_name, 'value', 'text');
			}

			if ($this->multiple)
			{
				$options[] = JHtml::_('select.optgroup', JText::_('COM_REDSHOP_SELECT_STOCKROOM'));
			}
		}

		// Merge any additional options in the XML definition.
		$options = array_merge(parent::getOptions(), $options);

		return $options;
	}
}
