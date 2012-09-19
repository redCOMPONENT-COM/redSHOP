<?php

defined ( '_JEXEC' ) or die ( 'Restricted access' );

jimport ( 'joomla.application.component.controller' );

class state_detailController extends JController
 {
	function __construct($default = array())
	{
		parent::__construct ( $default );
		$this->registerTask ( 'add', 'edit' );


	}

	function edit()
	{
		JRequest::setVar ( 'view', 'state_detail' );
		JRequest::setVar ( 'layout', 'default' );
		JRequest::setVar ( 'hidemainmenu', 1 );

		$model = $this->getModel ( 'state_detail' );

		parent::display ();
	}


	function apply()
	{
       $this->save(1);
	}
	function save($apply=0) {
		$post = JRequest::get ( 'post' );

		$state_name = JRequest::getVar( 'state_name', '', 'post', 'string', JREQUEST_ALLOWRAW );
		$post["state_name"] = $state_name;
		$option = JRequest::getVar ('option');
		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );
		$post ['state_id'] = $cid [0];
		$model = $this->getModel ( 'state_detail' );
		$row = $model->store ( $post );
		if ($row)
		{

			$msg = JText::_('COM_REDSHOP_STATE_DETAIL_SAVED' );

		} else
		{

			$msg = JText::_('COM_REDSHOP_ERROR_SAVING_IN_STATE_DETAIL' );

		}


		if ($apply==1){
			$this->setRedirect ( 'index.php?option=' . $option . '&view=state_detail&task=edit&cid[]='.$row->state_id, $msg );
		}else {
			$this->setRedirect ( 'index.php?option=' . $option . '&view=state', $msg);
		}

	}

	function cancel() {

		$option = JRequest::getVar ('option');

		$model = $this->getModel('state_detail');
		$model->checkin();
		$msg = JText::_('COM_REDSHOP_state_DETAIL_EDITING_CANCELLED' );
		$this->setRedirect ( 'index.php?option='.$option.'&view=state',$msg );
	}

	function remove() {

		$option = JRequest::getVar ('option');

		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );

		if (! is_array ( $cid ) || count ( $cid ) < 1) {
			JError::raiseError ( 500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_DELETE' ) );
		}

		$model = $this->getModel ( 'state_detail' );
		if (! $model->delete ( $cid )) {
			echo "<script> alert('" . $model->getError ( true ) . "'); window.history.go(-1); </script>\n";
		}
		$msg = JText::_('COM_REDSHOP_state_DETAIL_DELETED_SUCCESSFULLY' );
		$this->setRedirect ( 'index.php?option='.$option.'&view=state',$msg );
	}




}
?>