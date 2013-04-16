<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

class barcodeController extends JController
{
	public function getsearch()
	{
		$post = JRequest::get('post');

		if (strlen($post['barcode']) != 13)
		{
			$msg = 'Invalid Barcode';
			JError::raiseWarning(0, $msg);
			parent::display();
		}
		else
		{
			$model = $this->getModel('barcode');
			$barcode = $post['barcode'];
			$barcode = substr($barcode, 0, 12);

			$user = JFactory::getUser();
			$uid = $user->get('id');
			$row = $model->checkorder($barcode);

			if ($row)
			{
				$post['search_date'] = date("y-m-d H:i:s");
				$post['user_id'] = $uid;
				$post['order_id'] = $row->order_id;

				if ($model->save($post))
				{
					$msg = JText::_('COM_REDSHOP_THANKS_FOR_YOUR_REVIEWS');
				}
				else
				{
					$msg = JText::_('COM_REDSHOP_ERROR_PLEASE_TRY_AGAIN');
				}

				$this->setRedirect('index.php?option=com_redshop&view=barcode&order_id=' . $row->order_id, $msg);
			}
			else
			{
				$msg = 'Invalid Barcode';
				JError::raiseWarning(0, $msg);
				parent::display();
			}
		}
	}

	public function changestatus()
	{
		$post = JRequest::get('post');

		if (strlen($post['barcode']) != 13)
		{
			$msg = 'Invalid Barcode';
			JError::raiseWarning(0, $msg);
			$this->setRedirect('index.php?option=com_redshop&view=barcode&layout=barcode_order');
		}

		else
		{
			$model = $this->getModel('barcode');
			$barcode = $post['barcode'];
			$barcode = substr($barcode, 0, 12);

			$row = $model->checkorder($barcode);

			if ($row)
			{
				$model->updateorderstatus($barcode, $row->order_id);
				$this->setRedirect('index.php?option=com_redshop&view=barcode&layout=barcode_order', JText::_('ORDER_STATUS_CHANGED_TO_SHIPPED'));
			}
			else
			{
				$msg = 'Invalid Barcode';
				JError::raiseWarning(0, $msg);
				$this->setRedirect('index.php?option=com_redshop&view=barcode&layout=barcode_order');
			}
		}
	}
}
