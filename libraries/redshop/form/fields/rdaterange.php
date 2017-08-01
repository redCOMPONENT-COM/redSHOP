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

/**
 * Text field.
 *
 * @package     Redshop
 * @subpackage  Fields
 * @since       2.0.3
 */
class JFormFieldRdaterange extends JFormField
{
	/**
	 * Element name
	 *
	 * @var   string
	 */
	protected $type = 'Rdaterange';

	/**
	 * The autocomplete state for the form field.  If 'off' element will not be automatically
	 * completed by browser.
	 *
	 * @var    mixed
	 * @since  3.2
	 */
	protected $autocomplete = 'false';

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   11.1
	 */
	public function getInput()
	{
		$format     = (isset($this->element['format'])) ? (string) $this->element['format'] : 'DD/MM/YYYY';
		$firstDay   = (isset($this->element['first_day'])) ? (int) $this->element['first_day'] : 1;
		$autoApply  = (isset($this->element['auto_apply'])) ? (boolean) $this->element['auto_apply'] : true;
		$showButton = (isset($this->element['show_button'])) ? (boolean) $this->element['show_button'] : true;
		$class      = (isset($this->element['class'])) ? (string) $this->element['class'] : '';
		$onChange   = (isset($this->element['onChange'])) ? (string) $this->element['onChange'] : '';
		$phpFormat  = (isset($this->element['phpFormat'])) ? (string) $this->element['phpFormat'] : 'd/m/Y';

		JHtml::script('com_redshop/moment.js', false, true);
		JHtml::script('com_redshop/daterangepicker.js', false, true);
		JHtml::stylesheet('com_redshop/daterangepicker.css', false, true);

		return RedshopLayoutHelper::render(
			'field.date_range',
			array(
				'format'     => $format,
				'firstDay'   => $firstDay,
				'autoApply'  => $autoApply,
				'field'      => $this,
				'value'      => $this->value,
				'class'      => $class,
				'showButton' => $showButton,
				'onChange'   => $onChange,
				'phpFormat'  => $phpFormat
			)
		);
	}
}
