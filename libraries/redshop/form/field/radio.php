<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Form.Field
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

require_once JPATH_LIBRARIES . '/redshop/library.php';

JFormHelper::loadFieldClass('radio');

/**
 * Radio field override
 *
 * @since  1.0
 */
class RedshopFormFieldRadio extends JFormFieldRadio
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  1.0
	 */
	public $type = 'Radio';

	/**
	 * @var  string
	 */
	protected $labelLayout = 'field.label';

	/**
	 * @var  string
	 */
	protected $inputLayout = 'field.radio';

	/**
	 * Enable debug mode for field
	 *
	 * @return  boolean
	 */
	protected function debugEnabled()
	{
		return !empty($this->element['debug']) ? ((string) $this->element['debug'] === 'true') : false;
	}

	/**
	 * Method to get the radio button field input markup.
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   1.0
	 */
	protected function getInput()
	{
		return RedshopLayoutHelper::render($this->getInputLayout(), $this->getLayoutData());
	}

	/**
	 * Method to get the radio button field input markup.
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   1.0
	 */
	protected function getInputLayout()
	{
		return !empty($this->element['input-layout']) ? (string) $this->element['input-layout'] : $this->inputLayout;
	}

	/**
	 * Get the active label layout
	 *
	 * @return  string
	 *
	 * @since   1.0
	 */
	protected function getLabelLayout()
	{
		return !empty($this->element['label-layout']) ? (string) $this->element['label-layout'] : $this->labelLayout;
	}

	/**
	 * Get the layout information
	 *
	 * @return  array
	 */
	protected function getLayoutData()
	{
		// Label preprocess
		$label = $this->element['label'] ? (string) $this->element['label'] : (string) $this->element['name'];
		$label = $this->translateLabel ? JText::_($label) : $label;

		// Description preprocess
		$description = !empty($this->description) ? $this->description : null;
		$description = !empty($description) && $this->translateDescription ? JText::_($description) : $description;

		$alt = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $this->fieldname);

		return array(
			'autocomplete' => $this->autocomplete,
			'autofocus'    => $this->autofocus,
			'class'        => $this->class,
			'description'  => $description,
			'disabled'     => $this->disabled,
			'element'      => $this->element,
			'field'        => $this,
			'group'        => $this->group,
			'hidden'       => $this->hidden,
			'hint'         => $this->translateHint ? JText::alt($this->hint, $alt) : $this->hint,
			'id'           => $this->id,
			'label'        => $label,
			'labelclass'   => $this->labelclass,
			'multiple'     => $this->multiple,
			'name'         => $this->name,
			'onchange'     => $this->onchange,
			'onclick'      => $this->onclick,
			'options'      => $this->getOptions(),
			'pattern'      => $this->pattern,
			'readonly'     => $this->readonly,
			'repeat'       => $this->repeat,
			'required'     => (bool) $this->required,
			'size'         => $this->size,
			'spellcheck'   => $this->spellcheck,
			'validate'     => $this->validate,
			'value'        => $this->value
		);
	}
}
