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

		$mediaSection = $app->getUserState('com_redshop.global.media_section', 'product');

		$currentOptions = parent::getOptions();

		$query = $db->getQuery(true)
			->select(
				array(
					$db->qn($mediaSection . '_id', 'id'),
					$db->qn($mediaSection . '_name', 'title')
				)
			)
			->from($db->qn('#__redshop_' . $mediaSection))
			->where($db->qn('published') . ' = 1')
			->order($db->qn($mediaSection . '_name'));

		$items = $db->setQuery($query)->loadObjectList();

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
