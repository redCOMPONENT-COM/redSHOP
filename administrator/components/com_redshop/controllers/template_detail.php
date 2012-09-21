<?php
/**
 * @copyright Copyright (C) 2010 redCOMPONENT.com. All rights reserved.
 * @license GNU/GPL, see license.txt or http://www.gnu.org/copyleft/gpl.html
 * Developed by email@recomponent.com - redCOMPONENT.com
 *
 * redSHOP can be downloaded from www.redcomponent.com
 * redSHOP is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License 2
 * as published by the Free Software Foundation.
 *
 * You should have received a copy of the GNU General Public License
 * along with redSHOP; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */

defined ( '_JEXEC' ) or die ( 'Restricted access' );

jimport ( 'joomla.application.component.controller' );

require_once JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'template.php';

class template_detailController extends JController
{
	function __construct($default = array())
    {
		parent::__construct ( $default );
		$this->registerTask ( 'add', 'edit' );
	}

	function edit()
    {
		JRequest::setVar ( 'view', 'template_detail' );
		JRequest::setVar ( 'layout', 'default' );
		JRequest::setVar ( 'hidemainmenu', 1 );
		parent::display ();
    }

	function apply()
	{
       $this->save(1);
	}

	function save($apply=0)
    {
		$post = JRequest::get ( 'post' );
		$showbuttons = JRequest::getVar('showbuttons');

		$template_desc = JRequest::getVar( 'template_desc', '', 'post', 'string', JREQUEST_ALLOWRAW );
		$post["template_desc"]=$template_desc;

		$option = JRequest::getVar('option');

		$model = $this->getModel ( 'template_detail' );
		$row = $model->store ( $post );
		if ($row) {

			$msg = JText::_('COM_REDSHOP_TEMPLATE_SAVED' );

		} else {

			$msg = JText::_('COM_REDSHOP_ERROR_SAVING_TEMPLATE' );
		}
		if(!$showbuttons)
		{
			if ($apply==1){
				$this->setRedirect ( 'index.php?option='.$option.'&view=template_detail&task=edit&cid[]='.$row->template_id, $msg );
			}else {
				$this->setRedirect ( 'index.php?option='.$option.'&view=template', $msg );
			}
		}
		else
		{ ?>
			<script language="javascript" type="text/javascript">
				window.parent.SqueezeBox.close();
			</script>
	<?php }
	}

	function remove()
    {
        $option = JRequest::getVar('option');

		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );

		if (! is_array ( $cid ) || count ( $cid ) < 1) {
			JError::raiseError ( 500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_DELETE' ) );
		}

		$model = $this->getModel ( 'template_detail' );
		if (! $model->delete ( $cid )) {
			echo "<script> alert('" . $model->getError ( true ) . "'); window.history.go(-1); </script>\n";
		}

		$this->setRedirect ( 'index.php?option='.$option.'&view=template' );
	}

	function publish()
    {
        $option = JRequest::getVar('option');

		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );

		if (! is_array ( $cid ) || count ( $cid ) < 1) {
			JError::raiseError ( 500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_PUBLISH' ) );
		}

		$model = $this->getModel ( 'template_detail' );
		if (! $model->publish ( $cid, 1 )) {
			echo "<script> alert('" . $model->getError ( true ) . "'); window.history.go(-1); </script>\n";
		}

		$this->setRedirect ( 'index.php?option='.$option.'&view=template' );
	}

	function unpublish()
    {
        $option = JRequest::getVar('option');

		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );

		if (! is_array ( $cid ) || count ( $cid ) < 1) {
			JError::raiseError ( 500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_UNPUBLISH' ) );
		}

		$model = $this->getModel ( 'template_detail' );
		if (! $model->publish ( $cid, 0 )) {
			echo "<script> alert('" . $model->getError ( true ) . "'); window.history.go(-1); </script>\n";
		}

		$this->setRedirect ( 'index.php?option='.$option.'&view=template' );
	}

	function cancel()
    {
        $option = JRequest::getVar('option');

		$model = $this->getModel('template_detail');
		$model->checkin();

		$this->setRedirect ( 'index.php?option='.$option.'&view=template' );
	}

	function copy()
    {
        $option = JRequest::getVar('option');

		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );

		$model = $this->getModel ( 'template_detail' );

		if ($model->copy($cid)) {

			$msg = JText::_('COM_REDSHOP_TEMPLATE_COPIED' );

		} else {

			$msg = JText::_('COM_REDSHOP_ERROR_COPYING_TEMPLATE' );
		}

		$this->setRedirect ( 'index.php?option=' .$option. '&view=template', $msg );
	}
}
