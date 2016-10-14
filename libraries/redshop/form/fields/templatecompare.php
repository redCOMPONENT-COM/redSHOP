<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Element
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JFormHelper::loadFieldClass('list');

/**
 * Renders a template Form
 *
 * @package        Joomla
 * @subpackage     Banners
 * @since          1.5
 */
class JFormFieldTemplatecompare extends JFormFieldList
{
	/**
	 * Element name
	 *
	 * @access    protected
	 * @var        string
	 */
	public $type = 'Templatecompare';

	protected function getInput()
	{
		$db = JFactory::getDBO();
		$query = $db->getQuery(true)
			->select($db->qn('template_id'))
			->select($db->qn('template_name'))
			->from($db->qn('#__redshop_template'))
			->where($db->qn('published') . ' = 1')
			->where($db->qn('template_section') . ' = ' . $db->q('compare_product'));

		$items = $db->setQuery($query)->loadObjectList();
		$options = array();

		if (count($items) > 0)
		{
			foreach ($items as $item)
			{
				$option = JHTML::_('select.option', $item->template_id, $item->template_name);
				$options[] = $option;
			}
		}

		$options = array_merge(parent::getOptions(), $options);

		$attr = '';

		// Initialize some field attributes.
		$attr .= $this->element['class'] ? ' class="' . (string) $this->element['class'] . '"' : '';
		$attr .= $this->element['size'] ? ' size="' . (int) $this->element['size'] . '"' : '';
		$attr .= $this->element['multiple'] ? ' multiple' : '';

		// Initialize JavaScript field attributes.
		$attr .= $this->element['onchange'] ? ' onchange="' . (string) $this->element['onchange'] . '"' : '';

		return JHTML::_('select.genericlist', $options, $this->name, trim($attr), 'value', 'text', $this->value, $this->id);

	}
}
