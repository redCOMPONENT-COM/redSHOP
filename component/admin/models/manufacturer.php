<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopModelManufacturer extends RedshopModelForm
{

	/**
	 * Prepare and sanitise the table data prior to saving.
	 *
	 * @param   JTable  $table  A JTable object.
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	protected function prepareTable($table)
	{
		// Reorder the articles within the category so the new article is first
		if (empty($table->id))
		{
			$table->ordering = $table->getNextOrder();
		}

		$order_functions = order_functions::getInstance();
		$plg_manufacturer = $order_functions->getparameters('plg_manucaturer_excluding_category');

		if (count($plg_manufacturer) > 0 && $plg_manufacturer[0]->enabled)
		{
			if (!$table->excluding_category_list)
			{
				$table->excluding_category_list = '';
			}
		}
	}

	/**
	 * Method to save the form data.
	 *
	 * @param   array  $data  The form data.
	 *
	 * @return  boolean  True on success.
	 *
	 * @since   2.0.0.3
	 */
	public function save($data)
	{
		$order_functions = order_functions::getInstance();
		$plg_manufacturer = $order_functions->getparameters('plg_manucaturer_excluding_category');

		if (count($plg_manufacturer) > 0 && $plg_manufacturer[0]->enabled)
		{
			$data['excluding_category_list'] = implode(',', $data['excluding_category_list']);
		}

		return parent::save($data);
	}

	/**
	 * Copy manufacturer
	 *
	 * @param   int  $id  Manufacturer ID
	 *
	 * @return  bool
	 *
	 * @since   2.0.0.3
	 */
	public function copy($id)
	{
		if (!$id)
		{
			return false;
		}

		$table = $this->getTable();

		if ($table->load($id))
		{
			$table->manufacturer_id = null;
			$table->published = 0;
			$table->manufacturer_name = $this->generateNewName($table->manufacturer_name);
		}

		if ($table->check())
		{
			return $table->store();
		}

		return false;

	}

	/**
	 * Get array of template by section
	 *
	 * @return bool|array
	 *
	 * @since  2.0.0.3
	 */
	public function getTemplates()
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query
			->select($db->quoteName('template_id', 'value'))
			->select($db->quoteName('template_name', 'text'))
			->from($db->quoteName('#__redshop_template'))
			->where($db->quoteName('template_section') . '=' . $db->quote('manufacturer_products'))
			->where($db->quoteName('published') . '=' . (int) 1);

		$db->setQuery($query);

		return $db->loadObjectList();
	}

	/**
	 * Get media
	 *
	 * @param   int  $mid  Media ID
	 *
	 * @return  object
	 *
	 * @since   2.0.0.3
	 */
	public function getMedia($mid)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select($db->quoteName('media_id'))
			->select($db->quoteName('media_name'))
			->from($db->quoteName('#__redshop_media'))
			->where($db->quoteName('media_section') . '=' . $db->quote('manufacturer'))
			->where($db->quoteName('section_id') . '=' . (int) $mid);

		$db->setQuery($query);

		return $db->loadObject();
	}

	/**
	 * Method to change the title & alias.
	 *
	 * @param   string  $title  Manufacturer name
	 *
	 * @return  string
	 *
	 * @since   2.0.0.3
	 */
	protected function generateNewName($title)
	{
		// Alter the title & alias
		$table = $this->getTable();

		while ($table->load(array('manufacturer_name' => $title)))
		{
			$title = JString::increment($title);
		}

		return $title;
	}
}
