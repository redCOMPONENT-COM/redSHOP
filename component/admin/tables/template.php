<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Table
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Table Template
 *
 * @package     RedSHOP.Backend
 * @subpackage  Table
 * @since       __DEPLOY_VERSION__
 */
class RedshopTableTemplate extends RedshopTable
{
	/**
	 * The table name without the prefix. Ex: cursos_courses
	 *
	 * @var  string
	 */
	protected $_tableName = 'redshop_template';

	/**
	 * The table key column. Usually: id
	 *
	 * @var  string
	 */
	protected $_tableKey = 'template_id';

	/**
	 * Method to load a row from the database by primary key and bind the fields
	 * to the JTable instance properties.
	 *
	 * @param   mixed   $keys    An optional primary key value to load the row by, or an array of fields to match.  If not
	 *                           set the instance property value is used.
	 * @param   boolean $reset   True to reset the default values before loading the new row.
	 *
	 * @return  boolean  True if successful. False if row not found.
	 */
	protected function doLoad($keys = null, $reset = true)
	{
		if (!parent::doLoad($keys, $reset))
		{
			return false;
		}

		$templateDesc = $this->template_desc;

		$this->template_desc = RedshopHelperTemplate::readTemplateFile($this->template_section, $this->template_name, true);
		$this->template_desc = empty($this->template_desc) ? $templateDesc : $this->template_desc;

		return true;
	}

	/**
	 * Method to bind an associative array or object to the JTable instance.This
	 * method only binds properties that are publicly accessible and optionally
	 * takes an array of properties to ignore when binding.
	 *
	 * @param   mixed $src    An associative array or object to bind to the JTable instance.
	 * @param   mixed $ignore An optional array or space separated list of properties to ignore while binding.
	 *
	 * @return  boolean         True on success.
	 *
	 * @throws  \InvalidArgumentException
	 */
	protected function doBind(&$src, $ignore = array())
	{
		if (!empty($src['order_status']) && !is_array($src['order_status']))
		{
			$src['order_status'] = explode(',', $src['order_status']);
		}
		else
		{
			unset($src['order_status']);
			$this->order_status = '';
		}

		if (!empty($src['payment_methods']) && !is_array($src['payment_methods']))
		{
			$src['payment_methods'] = explode(',', $src['payment_methods']);
		}
		else
		{
			unset($src['payment_methods']);
			$this->payment_methods = '';
		}

		if (!empty($src['shipping_methods']) && !is_array($src['shipping_methods']))
		{
			$src['shipping_methods'] = explode(',', $src['shipping_methods']);
		}
		else
		{
			unset($src['shipping_methods']);
			$this->shipping_methods = '';
		}

		return parent::doBind($src, $ignore);
	}

	/**
	 * Called before store(). Overriden to send isNew to plugins.
	 *
	 * @param   boolean $updateNulls True to update null values as well.
	 * @param   boolean $isNew       True if we are adding a new item.
	 * @param   mixed   $oldItem     null for new items | JTable otherwise
	 *
	 * @return  boolean  True on success.
	 */
	protected function beforeStore($updateNulls = false, $isNew = false, $oldItem = null)
	{
		if (!parent::beforeStore($updateNulls, $isNew, $oldItem))
		{
			return false;
		}

		if ($isNew)
		{
			return true;
		}

		$oldItem->template_name = $this->safeTemplateName($oldItem->template_name);

		if ($oldItem->template_name !== $this->template_name || $oldItem->template_section !== $this->template_section)
		{
			$this->setOption('oldFile', RedshopHelperTemplate::getTemplateFilePath($oldItem->template_section, $oldItem->template_name, true));
		}

		return true;
	}

	/**
	 * Do the database store.
	 *
	 * @param   boolean $updateNulls True to update null values as well.
	 *
	 * @return  boolean
	 */
	protected function doStore($updateNulls = false)
	{
		if (!empty($this->payment_methods) && is_array($this->payment_methods))
		{
			$this->payment_methods = implode(',', $this->payment_methods);
		}

		if (!empty($this->shipping_methods) && is_array($this->shipping_methods))
		{
			$this->shipping_methods = implode(',', $this->shipping_methods);
		}

		if (!empty($this->order_status) && is_array($this->order_status))
		{
			$this->order_status = implode(',', $this->order_status);
		}

		$this->template_name = $this->safeTemplateName($this->template_name);

		if (!parent::doStore($updateNulls))
		{
			return false;
		}

		// Write template file
		JFile::write(
			RedshopHelperTemplate::getTemplateFilePath($this->template_section, $this->template_name, true),
			$this->template_desc
		);

		// Delete old file if necessary
		$oldFile = $this->getOption('oldFile', null);

		if (null !== $oldFile && JFile::exists($oldFile))
		{
			JFile::delete($oldFile);
		}

		return true;
	}

	/**
	 * Delete one or more registers
	 *
	 * @param   string /array  $pk  Array of ids or ids comma separated
	 *
	 * @return  boolean  Deleted successfully?
	 */
	protected function doDelete($pk = null)
	{
		$templatePath = RedshopHelperTemplate::getTemplateFilePath($this->template_section, $this->template_name, true);

		if (!parent::doDelete($pk))
		{
			return false;
		}

		if (JFile::exists($templatePath))
		{
			JFile::delete($templatePath);
		}

		return true;
	}

	/**
	 * Method for make template name safe
	 *
	 * @param   string  $templateName  Template name
	 *
	 * @return  string
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	protected function safeTemplateName($templateName = '')
	{
		return str_replace('-', '_', JFilterOutput::stringURLSafe(strtolower($templateName)));
	}
}
