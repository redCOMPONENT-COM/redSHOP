<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

class RedshopControllerRating_detail extends RedshopController
{
	public function __construct($default = array())
	{
		parent::__construct($default);
		$this->registerTask('add', 'edit');
	}

	public function edit()
	{
		$this->input->set('view', 'rating_detail');
		$this->input->set('layout', 'default');
		$this->input->set('hidemainmenu', 1);

		$model = $this->getModel('rating_detail');
		$users = $model->getUsers();
		$this->input->set('userslist', $users);

		$product = $model->getProducts();
		$this->input->set('product', $product);

		parent::display();
	}

	public function apply()
	{
		$this->save(1);
	}

	public function save()
	{
		$post            = $this->input->post->getArray();
		$comment         = $this->input->post->get('comment', '', 'raw');
		$post["comment"] = $comment;
		$task = $post['task'];
		$cid = $this->input->post->get('cid', 0);

		$post['rating_id'] = $cid;

		/** @var RedshopModelRating_detail $model */
		$model = $this->getModel('rating_detail');
		$row = $model->store($post);

		if ($row)
		{
			$msg = JText::_('COM_REDSHOP_RATING_DETAIL_SAVED');
		}
		else
		{
			$msg = JText::_('COM_REDSHOP_ERROR_SAVING_RATING_DETAIL');
		}

		if ($task == 'rating_detail.apply') {
			$ratingId = max((int)$cid, (int)$row['rating_id']);

			$this->setRedirect('index.php?option=com_redshop&view=rating_detail&layout=edit&cid=' . $ratingId, $msg);
		} else {
			$this->setRedirect('index.php?option=com_redshop&view=rating', $msg);
		}
	}

	public function remove()
	{
		$cid = $this->input->post->get('cid', array(0), 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			throw new Exception(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_DELETE'));
		}

		/** @var RedshopModelRating_detail $model */
		$model = $this->getModel('rating_detail');

		if (!$model->delete($cid))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_RATING_DETAIL_DELETED_SUCCESSFULLY');
		$this->setRedirect('index.php?option=com_redshop&view=rating', $msg);
	}

	public function cancel()
	{
		$msg = JText::_('COM_REDSHOP_RATING_DETAIL_EDITING_CANCELLED');
		$this->setRedirect('index.php?option=com_redshop&view=rating', $msg);
	}
}
