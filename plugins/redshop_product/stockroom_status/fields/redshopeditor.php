<?php
/**
 * @package     Redshop
 * @subpackage  Plugin.redshop_product
 *
 * @copyright   Copyright (C) 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_PLATFORM') or die;

JFormHelper::loadFieldClass('editor');

/**
 * Form Field class to show redSHOP editor
 *
 * @since  1.5
 */
class JFormFieldRedshopEditor extends JFormFieldEditor
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 */
	public $type = 'redshopeditor';

	/**
	 * Method to get the field input markup for the editor area
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   11.1
	 */
	protected function getInput()
	{
		// Style needed for J2.5
		return '<div style="float:left;width: 100%;">' . parent::getInput() . '</div>';
	}
}
