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

class container_detailController extends JController
{
	public function __construct($default = array())
	{
		parent::__construct($default);
		$this->registerTask('add', 'edit');
	}

	public function edit()
	{
		$model = $this->getModel('container_detail');
		$stockroom_data = $model->stockroom_data($id = 0);
		JRequest::setVar('stockroom_data', $stockroom_data);
		JRequest::setVar('view', 'container_detail');
		JRequest::setVar('hidemainmenu', 1);

		parent::display();
	}

	public function addcontainer()
	{
		$conid = JRequest::getVar('cid', array(0), 'post', 'array');

		JRequest::setVar('conid', $conid);
		JRequest::setVar('cid', array(0));

		parent::display();
	}

	public function saveanddisplay()
	{
		$post = JRequest::get('get');

		$model = $this->getModel('container_detail');

		$container_id = $model->saveanddisplay($post);

		$this->setRedirect('index.php?tmpl=component&option=com_redshop&view=container_detail&layout=products&rand_id='
			. time() . '&task=edit&cid[]=' . $container_id
		);
	}

	public function deleteProduct()
	{
		$post = JRequest::get('get');

		$model = $this->getModel('container_detail');

		$model->deleteProduct($post);

		$container_id = $post['container_id'];

		$this->setRedirect('index.php?tmpl=component&option=com_redshop&view=container_detail&layout=products&task=edit&cid[]=' . $container_id);
	}

	public function apply()
	{
		$this->save(1);
	}

	public function save($apply = 0)
	{
		$post = JRequest::get('post');

		$container_desc = JRequest::getVar('container_desc', '', 'post', 'string', JREQUEST_ALLOWRAW);

		$post["container_desc"] = $container_desc;

		$option = JRequest::getVar('option');

		$post ['creation_date'] = strtotime($post ['creation_date']);

		$model = $this->getModel('container_detail');

		if ($row = $model->store($post))
		{
			$msg = JText::_('COM_REDSHOP_CONTAINER_DETAIL_SAVED');
		}
		else
		{
			$msg = JText::_('COM_REDSHOP_ERROR_SAVING_CONTAINER_DETAIL');
		}

		if (isset($post['showbuttons']))
		{
			?>
        <script language="javascript" type="text/javascript">
				<?php
				if (isset($post['showbuttons']))
				{
					$link = 'index.php?option=' . $option . '&view=stockroom_detail&task=edit&cid[]=' . $post['stockroom_id'];
				}

				?>
            window.parent.document.location = '<?php echo $link; ?>';
        </script>
		<?php
			exit;
		}

		if ($apply == 1)
		{
			$this->setRedirect('index.php?option=' . $option . '&view=container_detail&task=edit&cid[]=' . $row->container_id, $msg);
		}

		else
		{
			$this->setRedirect('index.php?option=' . $option . '&view=container', $msg);
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

		$model = $this->getModel('container_detail');

		if (!$model->delete($cid))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_CONTAINER_DETAIL_DELETED_SUCCESSFULLY');
		$this->setRedirect('index.php?option=' . $option . '&view=container', $msg);
	}

	public function publish()
	{
		$option = JRequest::getVar('option');

		$cid = JRequest::getVar('cid', array(0), 'post', 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			JError::raiseError(500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_PUBLISH'));
		}

		$model = $this->getModel('container_detail');

		if (!$model->publish($cid, 1))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_CONTAINER_DETAIL_PUBLISHED_SUCCESFULLY');
		$this->setRedirect('index.php?option=' . $option . '&view=container', $msg);
	}

	public function unpublish()
	{
		$option = JRequest::getVar('option');

		$cid = JRequest::getVar('cid', array(0), 'post', 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			JError::raiseError(500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_UNPUBLISH'));
		}

		$model = $this->getModel('container_detail');

		if (!$model->publish($cid, 0))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_CONTAINER_DETAIL_UNPUBLISHED_SUCCESFULLY');
		$this->setRedirect('index.php?option=' . $option . '&view=container', $msg);
	}

	public function cancel()
	{
		$option = JRequest::getVar('option');
		$model = $this->getModel('container_detail');

		$model->cancel();
		$msg = JText::_('COM_REDSHOP_CONTAINER_DETAIL_EDITING_CANCELLED');
		$this->setRedirect('index.php?option=' . $option . '&view=container', $msg);
	}
}
