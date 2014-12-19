<?php
/**
 * @package     Joomla.Platform
 * @subpackage  HTML
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_PLATFORM') or die;

/**
 * Utility class for creating HTML Grids
 *
 * @package     RedSHOP.Platform
 * @subpackage  HTML
 * @since       1.5
 */
abstract class JHtmlRedshopSelect extends JHtmlSelect
{
	/**
	 * Default values for options. Organized by option group.
	 *
	 * @var     array
	 * @since   1.5
	 */
	static protected $optionDefaults = array(
		'option' => array('option.attr' => null, 'option.disable' => 'disable', 'option.id' => null, 'option.key' => 'value',
			'option.key.toHtml' => true, 'option.label' => null, 'option.label.toHtml' => true, 'option.text' => 'text',
			'option.text.toHtml' => true, 'option.class' => 'class', 'option.onclick' => 'onclick'));

	/**
	 * Generates an HTML selection list.
	 *
	 * @param   array   $data     An array of objects, arrays, or scalars.
	 * @param   string  $name     The value of the HTML name attribute.
	 * @param   mixed   $attribs  Additional HTML attributes for the <select> tag.
	 *
	 * @return  string  HTML for the select list.
	 *
	 * @since   1.5
	 */
	public static function search($data, $name, $attribs = null)
	{
		JHtml::$formatOptions['select2.ajaxOptions'] = array(
			'limit' => 10,
			'quietMillis' => 300,
			'typeField' => '',
			'url' => JURI::base() . 'index.php?option=com_redshop&task=search.search&format=json'
		);

		// Set default options
		$options = array_merge(JHtml::$formatOptions, static::$optionDefaults['option'], array('format.depth' => 0, 'id' => false));

		if (is_array($attribs))
		{
			if (is_array($attribs['select2.ajaxOptions']))
			{
				$options['select2.ajaxOptions'] = array_merge($options['select2.ajaxOptions'], $attribs['select2.ajaxOptions']);
				unset($attribs['select2.ajaxOptions']);
			}

			$options = array_merge($options, $attribs);
		}

		$initSelection = array();
		$value = array();

		if (is_array($data))
		{
			foreach ($data as $key => $val)
			{
				$object = new stdClass;
				$object->id = $val->$options['option.key'];
				$object->text = $val->$options['option.text'];
				$initSelection[] = $object;
				$value[] = $val->$options['option.key'];
			}
		}
		elseif (is_object($data))
		{
			$object = new stdClass;
			$object->id = $data->$options['option.key'];
			$object->text = $data->$options['option.text'];
			$initSelection[] = $object;
			$value[] = $data->$options['option.key'];
		}

		$options['select2.options'] = array(
			'placeholder' => '',
			'minimumInputLength' => 2,
			'width' => 'resolve',
			'multiple' => 'false',
			'allowClear' => 'true',
			'ajax' => '{ // instead of writing the function to execute the request we use Select2\'s convenient helper
				url: "' . $options['select2.ajaxOptions']['url'] . '",
				dataType: "json",
				type: "post",
				quietMillis: ' . $options['select2.ajaxOptions']['quietMillis'] . ',
				data: function (term, page) {
					return {
						input: term, // search term
						"' . JSession::getFormToken() . '": 1,
						limit: ' . $options['select2.ajaxOptions']['limit'] . ',
						page: page // page number
						' . $options['select2.ajaxOptions']['typeField'] . '
					};
				},
				results: function (data, page) {
					var more = (page * ' . $options['select2.ajaxOptions']['limit'] . ') < data.total;
					// whether or not there are more results available
					// notice we return the value of more so Select2 knows if more results can be loaded

					return {results: data.result, more: more};
				}
			}',
			'initSelection' => 'function(element, callback) {
				callback(' . json_encode($initSelection) . ');
			}'
		);

		if (is_array($attribs))
		{
			if (is_array($attribs['select2.options']))
			{
				$options['select2.options'] = array_merge($options['select2.options'], $attribs['select2.options']);
			}
		}

		$id = $options['id'] !== false ? $options['id'] : $name;
		$id = str_replace(array('[', ']'), '', $id);

		JHtml::_('redshopjquery.select2', '#' . $id, $options['select2.options']);

		JArrayHelper::toInteger($value);
		$value = implode(',', $value);

		if ($value == 0)
		{
			$value = '';
		}

		$attribs = '';

		if (isset($options['list.attr']))
		{
			if (is_array($options['list.attr']))
			{
				$attribs = JArrayHelper::toString($options['list.attr']);
			}
			else
			{
				$attribs = $options['list.attr'];
			}

			if ($attribs != '')
			{
				$attribs = ' ' . $attribs;
			}
		}

		return '<input type="text" name="' . $id . '" id="' . $id . '" value="' . $value . '"' . $attribs . ' />';
	}
}
