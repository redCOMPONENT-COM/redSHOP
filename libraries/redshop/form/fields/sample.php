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
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 */
	protected function getInput()
	{
		$layoutFile = $this->element['layoutFile'] ? (string) $this->element['layoutFile'] : 'sample';
		$html = RedshopLayoutHelper::render($layoutFile, null, JPATH_SITE . (string) $this->element['basePath']);

		// Style needed for J2.5
		return '<pre style="float: left;width: 100%;">' . htmlentities($html) . '</pre>';
	}
}
