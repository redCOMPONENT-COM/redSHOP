<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Controller Country Detail
 *
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 * @since       [version> [<description>]
 */

class RedshopControllerCountry extends RedshopController
{
	/**
	 * Construct class
	 * 
	 * @param   array  $default  param to construct class
	 *
	 * @since 1.x
	 */

	public function __construct($default = array())
	{
		parent::__construct($default);
		$this->registerTask('add', 'edit');
	}

	/**
	 * Edit country detail
	 *
	 * @return void
	 *
	 * @since 1.x
	 */

	public function edit()
	{
		JRequest::setVar('view', 'country');
		JRequest::setVar('layout', 'default');
		JRequest::setVar('hidemainmenu', 1);

		parent::display();
	}

	/**
	 * Apply country detail
	 *
	 * @return void
	 *
	 * @since 1.x
	 */

	public function apply()
	{
		$this->save(1);
	}

	/**
	 * Edit country detail
	 *
	 * @param   int  $apply  Flag to know save or apply  
	 * 
	 * @return  void
	 *
	 * @since   1.x
	 */

	public function save($apply = 0)
	{
		$post = JRequest::get('post');

		$countryName = JRequest::getVar('country_name', '', 'post', 'string', JREQUEST_ALLOWRAW);
		$post["country_name"] = $countryName;

		$cid = JRequest::getVar('cid', array(0), 'post', 'array');
		print_r($cid);
		$post['id'] = $cid [0];
		$model = $this->getModel('country');
		$row = $model->store($post);

		if ($row)
		{
			$msg = JText::_('COM_REDSHOP_COUNTRY_DETAIL_SAVED');
		}
		else
		{
			$msg = JText::_('COM_REDSHOP_ERROR_SAVING_COUNTRY_DETAIL');
		}

		if ($apply == 1)
		{
			$this->setRedirect('index.php?option=com_redshop&view=country&task=edit&cid[]=' . $row->id, $msg);
		}
		else
		{
			$this->setRedirect('index.php?option=com_redshop&view=countries', $msg);
		}
	}

	/**
	 * Cancel country detail
	 *
	 * @return void
	 *
	 * @since 1.x
	 */

	public function cancel()
	{
		$msg = JText::_('COM_REDSHOP_COUNTRY_DETAIL_EDITING_CANCELLED');
		$this->setRedirect('index.php?option=com_redshop&view=countries', $msg);
	}

	/**
	 * Remove country detail
	 *
	 * @return void
	 *
	 * @since 1.x
	 */

	public function remove()
	{
		$cid = JRequest::getVar('cid', array(0), 'post', 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			throw new Exception(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_DELETE'));
		}

		$model = $this->getModel('country');

		if (!$model->delete($cid))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_COUNTRY_DETAIL_DELETED_SUCCESSFULLY');
		$this->setRedirect('index.php?option=com_redshop&view=country', $msg);
	}
}
