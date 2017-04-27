<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Field
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

JLoader::import('joomla.form.formfield');
JFormHelper::loadFieldClass('list');

use Joomla\Utilities\ArrayHelper;

/**
 * redSHOP section id list
 *
 * @package     RedSHOP.Backend
 * @subpackage  Field.MediaSectionId
 *
 * @since       2.0.4
 */
class JFormFieldMediaSectionId extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var   string
	 */
	protected $type = 'MediaSectionId';

	/**
	 * Method to get the field options.
	 *
	 * @return  array  Options to populate the select field
	 */
	public function getOptions()
	{
		$app = JFactory::getApplication();
		$db  = JFactory::getDbo();

		$mediaSection = $app->getUserState('com_redshop.global.media.section', 'product');

		if (!in_array($mediaSection, array('product', 'category', 'manufacturer', 'property', 'subproperty')))
		{
			return array();
		}

		$tableSuffix = $mediaSection;

		switch ($mediaSection)
		{
			case 'property':
				$mediaSection = 'attribute';
				$tableSuffix = 'product_attribute';
				break;
			case 'subproperty':
				$mediaSection = 'property';
				$tableSuffix = 'product_attribute_property';
				break;
			default:
				break;
		}

		$items = array();

		try
		{
			$columnPrefix = $mediaSection . '_';

			$config = JFactory::getConfig();
			$tablePreFix = $config->get('dbprefix');
			$dbName = $config->get('db');

			$query = $db->getQuery(true);
			$query->select($db->qn('COLUMN_NAME'))
				->from($db->qn('information_schema.COLUMNS'))
				->where($db->qn('TABLE_SCHEMA') . ' = ' . $db->q($dbName))
				->where($db->qn('TABLE_NAME') . ' = ' . $db->q($tablePreFix . 'redshop_' . $tableSuffix))
				->where($db->qn('COLUMN_NAME') . ' = ' . $db->q($columnPrefix . 'id'));

			if (!$db->setQuery($query)->loadObject())
			{
				$columnPrefix = '';
			}

			$currentOptions = parent::getOptions();

			$query->clear()
				->select(
					array(
						$db->qn('m.' . $columnPrefix . 'id', 'id'),
						$db->qn('m.' . $columnPrefix . 'name', 'title')
					)
				)
				->from($db->qn('#__redshop_' . $tableSuffix, 'm'))
				->order($db->qn('m.' . $columnPrefix . 'name'));

			$items = $db->setQuery($query)->loadObjectList();
		}
		catch (Exception $e)
		{
			// Do nothing
		}

		// Clean up the options
		$options = array();

		if (!empty($items))
		{
			foreach ($items as $item)
			{
				$options[] = JHtml::_('select.option', $item->id, $item->title);
			}
		}

		if (!empty($currentOptions))
		{
			$options = array_merge($currentOptions, $options);
		}

		return $options;
	}
}
