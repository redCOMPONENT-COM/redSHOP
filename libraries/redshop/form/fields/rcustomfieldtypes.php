<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Field
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

JLoader::import('joomla.form.formfield');

JFormHelper::loadFieldClass('list');

/**
 * Economic account group select list for redSHOP
 *
 * @package     RedSHOP.Backend
 * @subpackage  Field
 *
 * @since       1.0
 */
class JFormFieldRCustomfieldTypes extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 */
	protected $type = 'RCustomfieldTypes';

	/**
	 * Get the select options
	 *
	 * @return  array  Options to populate the select field
	 */
	public function getOptions()
	{
		// Initialize variables.
		$options = RedshopHelperTemplate::getFieldTypeSections();

		// Get other options inserted in the XML file
		$parentOptions = parent::getOptions();

		return array_merge($parentOptions, $options);
	}
}
