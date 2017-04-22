<?php
/**
 * @package     Redshop
 * @subpackage  Fields
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('JPATH_BASE') or die;

JFormHelper::loadFieldClass('predefinedlist');

/**
 * jQuery UI datepicker field for redbooking.
 *
 * @package     Redshop
 * @subpackage  Fields
 * @since       1.0
 */
class JFormFieldRpublished extends JFormFieldPredefinedList
{
	/**
	 * The form field type.
	 *
	 * @var  string
	 */
	protected $type = 'Rpublished';

	/**
	 * Cached array of the category items.
	 *
	 * @var    array
	 * @since  1.0
	 */
	protected static $options = array();

	/**
	 * The array of values
	 *
	 * @var  string
	 */
	protected $predefinedOptions = array(
		1   => 'JPUBLISHED',
		0   => 'JUNPUBLISHED',
		2   => 'JARCHIVED',
		-2  => 'JTRASHED',
		'*' => 'JALL'
	);

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
			// B/C with statuses options
			if (!isset($this->element['filter']) && isset($this->element['statuses']))
			{
				$this->element['filter'] = (string) $this->element['statuses'];
			}

			static::$options[$type][$hash] = parent::getOptions();
		}

		return static::$options[$type][$hash];
	}
}
