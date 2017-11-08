<?php
/**
 * @package     Redshop
 * @subpackage  Plugin.redshop_product
 *
 * @copyright   Copyright (C) 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JLoader::import('redshop.library');

/**
 * Form Field class to show Sample Invoice PDF template
 * Supports a one line text field.
 *
 * @since  1.5
 */
class JFormFieldSample extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 */
	protected $type = 'Sample';

	/**
	 * Method to get the field label markup.
	 *
	 * @return  string  The field label markup.
	 *
	 * @since   11.1
	 */
	protected function getLabel()
	{
		$layoutFile = $this->element['layoutFile'] ? (string) $this->element['layoutFile'] : 'sample';
		$html = RedshopLayoutHelper::render($layoutFile, null, JPATH_SITE . (string) $this->element['basePath']);

		// Style needed for J2.5
		// @todo: remove style in redSHOP 1.6
		return '<pre style="float: left;width: 100%;">' . htmlentities($html) . '</pre>';
	}

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   11.1
	 */
	protected function getInput()
	{
		return '';
	}
}
