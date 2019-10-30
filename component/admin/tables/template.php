<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Table
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Table Template
 *
 * @package     RedSHOP.Backend
 * @subpackage  Table
 * @since       2.0.7
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
	 * Temporary variable for store/load template content;
	 *
	 * @var  string
	 */
	public $templateDesc;

	/**
	 * @var integer
	 */
	public $id;

	/**
	 * @var string
	 */
	public $file_name;

	/**
	 * @var string
	 */
	public $name;

	/**
	 * @var string
	 */
	public $section;

	/**
	 * Method to load a row from the database by primary key and bind the fields
	 * to the JTable instance properties.
	 *
	 * @param   mixed   $keys    An optional primary key value to load the row by, or an array of fields to match.  If not
	 *                           set the instance property value is used.
	 * @param   boolean $reset   True to reset the default values before loading the new row.
	 *
	 * @return  boolean          True if successful. False if row not found.
	 */
	protected function doLoad($keys = null, $reset = true)
	{
		if (!parent::doLoad($keys, $reset))
		{
			return false;
		}

		if ($this->id && !empty($this->file_name))
		{
			$file = JPath::clean(JPATH_REDSHOP_TEMPLATE . '/' . $this->section . '/' . $this->file_name . '.php');

			if (JFile::exists($file))
			{
				$this->templateDesc = (string) file_get_contents($file);
			}
		}

		return true;
	}

	/**
	 * Method to bind an associative array or object to the JTable instance.This
	 * method only binds properties that are publicly accessible and optionally
	 * takes an array of properties to ignore when binding.
	 *
	 * @param   mixed  $src     An associative array or object to bind to the JTable instance.
	 * @param   mixed  $ignore  An optional array or space separated list of properties to ignore while binding.
	 *
	 * @return  boolean         True on success.
	 *
	 * @throws  InvalidArgumentException
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
	 * Do the database store.
	 *
	 * @param   boolean $updateNulls True to update null values as well.
	 *
	 * @return  boolean
	 *
	 * @throws  Exception
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

		$isNew = !$this->id;

		$this->setOption('content', $this->templateDesc);
		unset($this->templateDesc);

		if (!parent::doStore($updateNulls))
		{
			return false;
		}

		if ($isNew || empty($this->file_name))
		{
			$fileName = $this->generateTemplateFileName($this->id, $this->name);

			$db    = $this->getDbo();
			$query = $db->getQuery(true)
				->update($db->qn('#__redshop_template'))
				->where($db->qn('id') . ' = ' . $this->id)
				->set($db->qn('file_name') . ' = ' . $db->quote($fileName));
			$db->setQuery($query)->execute();
		}
		else
		{
			$fileName = $this->file_name;
		}

		// Write template file
		JFile::write(
			JPath::clean(JPATH_REDSHOP_TEMPLATE . '/' . $this->section . '/' . $fileName . '.php'),
			$this->getOption('content', '')
		);

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
		if (!parent::doDelete($pk))
		{
			return false;
		}

		$templatePath = JPath::clean(JPATH_REDSHOP_TEMPLATE . '/' . $this->section . '/' . $this->file_name . '.php');

		if (JFile::exists($templatePath))
		{
			return JFile::delete($templatePath);
		}

		return true;
	}

	/**
	 * Method for make template name safe
	 *
	 * @param   integer  $id    Template ID
	 * @param   string   $name  Template name
	 *
	 * @return  string
	 *
	 * @since   2.0.7
	 */
	public function generateTemplateFileName($id = 0, $name = '')
	{
		return str_replace('-', '_', JFilterOutput::stringURLSafe($id . ' - ' . strtolower($name)));
	}

	/**
	 * Checks that the object is valid and able to be stored.
	 *
	 * This method checks that the parent_id is non-zero and exists in the database.
	 * Note that the root node (parent_id = 0) cannot be manipulated with this class.
	 *
	 * @return  boolean  True if all checks pass.
	 */
	protected function doCheck()
	{
		if (empty($this->name))
		{
			return false;
		}

		if (empty($this->section))
		{
			return false;
		}

		return parent::doCheck();
	}
}
