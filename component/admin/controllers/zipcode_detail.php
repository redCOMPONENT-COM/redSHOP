<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopControllerZipcode_detail extends RedshopController
{
	public function __construct($default = array())
	{
		parent::__construct($default);
		$this->registerTask('add', 'edit');
	}

	public function edit()
	{
		$this->input->set('view', 'zipcode_detail');
		$this->input->set('layout', 'default');
		$this->input->set('hidemainmenu', 1);

		parent::display();
	}

	public function apply()
	{
		$this->save(1);
	}

	public function save($apply = 0)
	{
		$post = $this->input->post->getArray();

		$city_name         = $this->input->post->get('city_name', '', 'raw');
		$post["city_name"] = $city_name;

		$cid                 = $this->input->post->get('cid', array(0), 'array');
		$post ['zipcode_id'] = $cid [0];
		$model               = $this->getModel('zipcode_detail');

		if ($post["zipcode_to"] == "")
		{
			$row = $model->store($post);
		}
		else
		{
			for ($i = $post["zipcode"]; $i <= $post["zipcode_to"]; $i++)
			{
				$post['zipcode'] = $i;
				$row = $model->store($post);
			}
		}

		if ($row)
		{
			$msg = JText::_('COM_REDSHOP_ZIPCODE_DETAIL_SAVED');
		}
		else
		{
			$msg = JText::_('COM_REDSHOP_ERROR_SAVING_IN_ZIPCODE_DETAIL');
		}

		if ($apply == 1)
		{
			$this->setRedirect('index.php?option=com_redshop&view=zipcode_detail&task=edit&cid[]=' . $row->zipcode_id, $msg);
		}
		else
		{
			$this->setRedirect('index.php?option=com_redshop&view=zipcode', $msg);
		}
	}

	public function cancel()
	{

		$msg = JText::_('COM_REDSHOP_ZIPCODE_DETAIL_EDITING_CANCELLED');
		$this->setRedirect('index.php?option=com_redshop&view=zipcode', $msg);
	}

	public function remove()
	{
		$cid = $this->input->post->get('cid', array(0), 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			throw new Exception(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_DELETE'));
		}

		$model = $this->getModel('zipcode_detail');

		if (!$model->delete($cid))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_ZIPCODE_DETAIL_DELETED_SUCCESSFULLY');
		$this->setRedirect('index.php?option=com_redshop&view=zipcode', $msg);
	}
}
