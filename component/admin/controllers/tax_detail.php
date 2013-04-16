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

class tax_detailController extends JController
{
	public function __construct($default = array())
	{
		parent::__construct($default);
		$this->registerTask('add', 'edit');
	}

	public function edit()
	{
		JRequest::setVar('view', 'tax_detail');
		JRequest::setVar('layout', 'default');
		JRequest::setVar('hidemainmenu', 1);

		parent::display();
	}

	public function save()
	{
		$post = JRequest::get('post');

		$option = JRequest::getVar('option');
		$tax_group_id = JRequest::getVar('tax_group_id');
		$model = $this->getModel('tax_detail');

		if ($model->store($post))
		{
			$msg = JText::_('COM_REDSHOP_TAX_DETAIL_SAVED');
		}
		else
		{
			$msg = JText::_('COM_REDSHOP_ERROR_SAVING_TAX_DETAIL');
		}

		if (isset($post['tmpl']) && $post['tmpl'] == "component")
		{
			?>
        <script>
            //window.parent.location.reload();
            window.parent.document.getElementById('installform').substep.value = 4;
            window.parent.document.getElementById('installform').submit();
            window.parent.SqueezeBox.close();
        </script>
		<?php
		}
		else
		{
			$this->setRedirect('index.php?option=' . $option . '&view=tax&tax_group_id=' . $tax_group_id, $msg);
		}
	}

	public function remove()
	{
		$option = JRequest::getVar('option');

		$tax_group_id = JRequest::getVar('tax_group_id');
		$cid = JRequest::getVar('cid', array(0), 'post', 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			JError::raiseError(500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_DELETE'));
		}

		$model = $this->getModel('tax_detail');

		if (!$model->delete($cid))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_TAX_DETAIL_DELETED_SUCCESSFULLY');

		$this->setRedirect('index.php?option=' . $option . '&view=tax&tax_group_id=' . $tax_group_id, $msg);
	}

	public function removefromwizrd()
	{
		$option = JRequest::getVar('option');
		$cid = JRequest::getVar('cid', array(0), 'request', 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			JError::raiseError(500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_DELETE'));
		}

		$model = $this->getModel('tax_detail');

		if (!$model->delete($cid))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_TAX_DETAIL_DELETED_SUCCESSFULLY');

		$this->setRedirect('index.php?option=' . $option . '&step=4', $msg);
	}

	public function cancel()
	{
		$option = JRequest::getVar('option');
		$tax_group_id = JRequest::getVar('tax_group_id');
		$msg = JText::_('COM_REDSHOP_TAX_DETAIL_EDITING_CANCELLED');
		$this->setRedirect('index.php?option=' . $option . '&view=tax&tax_group_id=' . $tax_group_id, $msg);
	}
}
