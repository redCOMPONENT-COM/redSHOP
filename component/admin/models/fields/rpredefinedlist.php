<?php
/**
 * @package     Redcore
 * @subpackage  Field
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('JPATH_PLATFORM') or die;

JFormHelper::loadFieldClass('list');

/**
 * Form Field to load a list of predefined values
 *
 * @package     Redcore
 * @subpackage  Field
 * @since       1.0
 */
abstract class JFormFieldRpredefinedList extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  1.0
	 */
	protected $type = 'RpredefinedList';

	/**
	 * Cached array of the category items.
	 *
	 * @var    array
	 * @since  1.0
	 */
	protected static $options = array();

	/**
	 * Available predefined options
	 *
	 * @var  array
	 * @since  1.0
	 */
	protected $predefinedOptions = array();

	/**
	 * Translate options labels ?
	 *
	 * @var  boolean
	 * @since  1.0
	 */
	protected $translate = true;

	/**
	 * Method to get the options to populate list
	 *
	 * @return  array  The field option objects.
	 *
	 * @since   1.0
	 */
	protected function getOptions()
	{
		// Hash for caching
		$hash = md5($this->element);
		$type = strtolower($this->type);

		if (!isset(static::$options[$type][$hash]) && !empty($this->predefinedOptions))
		{
			static::$options[$type][$hash] = parent::getOptions();

			$options = array();

			// Allow to only use specific values of the predefined list
			$filter = isset($this->element['filter']) ? explode(',', $this->element['filter']) : array();

			foreach ($this->predefinedOptions as $value => $text)
			{
				if (empty($filter) || in_array($value, $filter))
				{
					$text = $this->translate ? JText::_($text) : $text;

					$options[] = (object) array(
						'value' => $value,
						'text'  => $text
					);
				}
			}

			static::$options[$type][$hash] = array_merge(static::$options[$type][$hash], $options);
		}

		return static::$options[$type][$hash];
	}
}
