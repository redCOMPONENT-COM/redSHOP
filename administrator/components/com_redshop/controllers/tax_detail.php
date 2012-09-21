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

class tax_detailController extends JController
{
	function __construct($default = array())
    {
		parent::__construct ( $default );
		$this->registerTask ( 'add', 'edit' );
	}

	function edit()
	{
		JRequest::setVar ( 'view', 'tax_detail' );
		JRequest::setVar ( 'layout', 'default' );
		JRequest::setVar ( 'hidemainmenu', 1 );

		parent::display();
	}

	function save()
	{
		$post = JRequest::get ( 'post' );

		$option = JRequest::getVar ('option');
		$tax_group_id = JRequest::getVar ('tax_group_id');
		$model = $this->getModel ( 'tax_detail' );

		if ($model->store ( $post )) {

			$msg = JText::_('COM_REDSHOP_TAX_DETAIL_SAVED' );

		} else {

			$msg = JText::_('COM_REDSHOP_ERROR_SAVING_TAX_DETAIL' );
		}

		if(isset($post['tmpl']) && $post['tmpl'] == "component" ){
			//$this->setRedirect ( 'index.php?option=com_redshop&step=3', $msg );
			?>
			<script>
				//window.parent.location.reload();
				window.parent.document.getElementById( 'installform' ).substep.value = 4;
				window.parent.document.getElementById( 'installform' ).submit();
				window.parent.SqueezeBox.close();
			</script>
			<?php
		}else{
			$this->setRedirect ( 'index.php?option=' . $option . '&view=tax&tax_group_id='.$tax_group_id, $msg );
		}
	}

	function remove()
    {
        $option = JRequest::getVar ('option');

		$tax_group_id = JRequest::getVar ('tax_group_id');
		$cid = JRequest::getVar ( 'cid', array (0), 'post', 'array' );


		if (! is_array ( $cid ) || count ( $cid ) < 1) {
			JError::raiseError ( 500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_DELETE' ) );
		}

		$model = $this->getModel ( 'tax_detail' );
		if (! $model->delete ( $cid )) {
			echo "<script> alert('" . $model->getError ( true ) . "'); window.history.go(-1); </script>\n";
		}
		$msg = JText::_('COM_REDSHOP_TAX_DETAIL_DELETED_SUCCESSFULLY' );

		$this->setRedirect ( 'index.php?option='.$option.'&view=tax&tax_group_id='.$tax_group_id,$msg );
	}

    function removefromwizrd()
    {
        $option = JRequest::getVar ('option');

		//$tax_group_id = JRequest::getVar ('tax_group_id');
		$cid = JRequest::getVar ( 'cid', array (0), 'request', 'array' );


		if (! is_array ( $cid ) || count ( $cid ) < 1) {
			JError::raiseError ( 500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_DELETE' ) );
		}

		$model = $this->getModel ( 'tax_detail' );
		if (! $model->delete ( $cid )) {
			echo "<script> alert('" . $model->getError ( true ) . "'); window.history.go(-1); </script>\n";
		}
		//$msg = JText::_('COM_REDSHOP_TAX_DETAIL_DELETED_SUCCESSFULLY' );
        $this->setRedirect ( 'index.php?option='.$option.'&step=4');
	}

	function cancel()
    {
        $option = JRequest::getVar ('option');
		$tax_group_id = JRequest::getVar ('tax_group_id');
		$msg = JText::_('COM_REDSHOP_TAX_DETAIL_EDITING_CANCELLED' );
		$this->setRedirect ( 'index.php?option='.$option.'&view=tax&tax_group_id='.$tax_group_id,$msg );
	}
}
