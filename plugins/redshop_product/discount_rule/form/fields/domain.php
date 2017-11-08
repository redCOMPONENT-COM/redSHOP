<?php
/**
 * @package     Joomla.Libraries
 * @subpackage  Form
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_PLATFORM') or die;

JFormHelper::loadFieldClass('list');

/**
 * Form Field class for the Joomla Framework.
 *
 * @since  3.1
 */
class JFormFieldDomain extends JFormFieldList
{
	/**
	 * A flexible tag list that respects access controls
	 *
	 * @var    string
	 * @since  3.1
	 */
	public $type = 'Domain';

	/**
	 * Flag to work with nested tag field
	 *
	 * @var    boolean
	 * @since  3.1
	 */
	public $isNested = null;

	/**
	 * com_tags parameters
	 *
	 * @var    \Joomla\Registry\Registry
	 * @since  3.1
	 */
	protected $comParams = null;

	/**
	 * Constructor
	 *
	 * @since  3.1
	 */
	public function __construct()
	{
		parent::__construct();

		// Load com_tags config
		$this->comParams = JComponentHelper::getParams('com_tags');
	}

	/**
	 * Method to get the field input for a tag field.
	 *
	 * @return  string  The field input.
	 *
	 * @since   3.1
	 */
	protected function getInput()
	{
		// AJAX mode requires ajax-chosen
		if (!$this->isNested())
		{
			// Get the field id
			$id    = isset($this->element['id']) ? $this->element['id'] : null;
			$cssId = '#' . $this->getId($id, $this->element['name']);

			// Load the ajax-chosen customised field
			JHtml::_('tag.ajaxfield', $cssId, $this->allowCustom());
		}

		if (!is_array($this->value) && !empty($this->value))
		{
			if ($this->value instanceof JHelperTags)
			{
				if (empty($this->value->tags))
				{
					$this->value = array();
				}
				else
				{
					$this->value = $this->value->tags;
				}
			}

			// String in format 2,5,4
			if (is_string($this->value))
			{
				$this->value = explode(',', $this->value);
			}
		}

		return parent::getInput();
	}

	/**
	 * Determine if the field has to be tagnested
	 *
	 * @return  boolean
	 *
	 * @since   3.1
	 */
	public function isNested()
	{
		if (is_null($this->isNested))
		{
			// If mode="nested" || ( mode not set & config = nested )
			if ((isset($this->element['mode']) && $this->element['mode'] == 'nested')
				|| (!isset($this->element['mode']) && $this->comParams->get('tag_field_ajax_mode', 1) == 0))
			{
				$this->isNested = true;
			}
		}

		return $this->isNested;
	}

	/**
	 * Determines if the field allows or denies custom values
	 *
	 * @return  boolean
	 */
	public function allowCustom()
	{
		return !(isset($this->element['custom']) && $this->element['custom'] == 'deny');
	}

	/**
	 * Method to get a list of tags
	 *
	 * @return  array  The field option objects.
	 *
	 * @since   3.1
	 */
	protected function getOptions()
	{
		$options = array();

		if (!empty($this->value))
		{
			foreach ($this->value as $value)
			{
				$option = new stdClass;

				$option->text    = str_replace('#new#', '', $value);
				$option->value   = $value;
				$option->checked = true;

				$options[] = $option;
			}
		}

		return JHelperTags::convertPathsToNames(array_merge(parent::getOptions(), $options));
	}

	/**
	 * Add "-" before nested tags, depending on level
	 *
	 * @param   array  $options  Array of tags
	 *
	 * @return  array            The field option objects.
	 *
	 * @since   3.1
	 */
	protected function prepareOptionsNested(&$options)
	{
		if ($options)
		{
			foreach ($options as &$option)
			{
				$repeat       = (isset($option->level) && $option->level - 1 >= 0) ? $option->level - 1 : 0;
				$option->text = str_repeat('- ', $repeat) . $option->text;
			}
		}

		return $options;
	}
}
