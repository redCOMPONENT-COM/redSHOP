<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die ('Restricted access');

jimport('joomla.application.component.controller');

class giftcard_detailController extends JController
{
	public function __construct($default = array())
	{
		parent::__construct($default);
		$this->registerTask('add', 'edit');
	}

	public function edit()
	{
		JRequest::setVar('view', 'giftcard_detail');
		JRequest::setVar('layout', 'default');
		JRequest::setVar('hidemainmenu', 1);
		parent::display();
	}

	public function apply()
	{
		$this->save(1);
	}

	public function save($apply = 0)
	{
		$post = JRequest::get('post', JREQUEST_ALLOWRAW);

		$giftcard_desc = JRequest::getVar('giftcard_desc', '', 'post', 'string', JREQUEST_ALLOWRAW);
		$post["giftcard_desc"] = $giftcard_desc;

		$showbuttons = JRequest::getVar('showbuttons');

		$option = JRequest::getVar('option');

		$model = $this->getModel('giftcard_detail');
		$row = $model->store($post);

		if ($row)
		{
			$msg = JText::_('COM_REDSHOP_GIFTCARD_SAVED');
		}
		else
		{
			$msg = JText::_('COM_REDSHOP_ERROR_SAVING_GIFTCARD');
		}

		if (!$showbuttons)
		{
			if ($apply == 1)
			{
				$this->setRedirect('index.php?option=' . $option . '&view=giftcard_detail&task=edit&cid[]=' . $row->giftcard_id, $msg);
			}
			else
			{
				$this->setRedirect('index.php?option=' . $option . '&view=giftcard', $msg);
			}
		}
		else
		{
			?>
        <script language="javascript" type="text/javascript">
            window.parent.SqueezeBox.close();
        </script>
		<?php
		}
	}

	public function remove()
	{
		$option = JRequest::getVar('option');

		$cid = JRequest::getVar('cid', array(0), 'post', 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			JError::raiseError(500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_DELETE'));
		}

		$model = $this->getModel('giftcard_detail');

		if (!$model->delete($cid))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$this->setRedirect('index.php?option=' . $option . '&view=giftcard');
	}

	public function publish()
	{
		$option = JRequest::getVar('option');

		$cid = JRequest::getVar('cid', array(0), 'post', 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			JError::raiseError(500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_PUBLISH'));
		}

		$model = $this->getModel('giftcard_detail');

		if (!$model->publish($cid, 1))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$this->setRedirect('index.php?option=' . $option . '&view=giftcard');
	}

	public function unpublish()
	{
		$option = JRequest::getVar('option');

		$cid = JRequest::getVar('cid', array(0), 'post', 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			JError::raiseError(500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_UNPUBLISH'));
		}

		$model = $this->getModel('giftcard_detail');

		if (!$model->publish($cid, 0))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$this->setRedirect('index.php?option=' . $option . '&view=giftcard');
	}

	public function cancel()
	{
		$option = JRequest::getVar('option');

		$this->setRedirect('index.php?option=' . $option . '&view=giftcard');
	}

	public function copy()
	{
		$option = JRequest::getVar('option');

		$cid = JRequest::getVar('cid', array(0), 'post', 'array');

		$model = $this->getModel('giftcard_detail');

		if ($model->copy($cid))
		{
			$msg = JText::_('COM_REDSHOP_GIFTCARD_COPIED');
		}
		else
		{
			$msg = JText::_('COM_REDSHOP_ERROR_COPYING_GIFTCARD');
		}

		$this->setRedirect('index.php?option=' . $option . '&view=giftcard', $msg);
	}
}
