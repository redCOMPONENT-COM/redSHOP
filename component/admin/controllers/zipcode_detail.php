<?php

defined ( '_JEXEC' ) or die ( 'Restricted access' );

jimport ( 'joomla.application.component.controller' );

class zipcode_detailController extends JController
 {
	function __construct($default = array()) 
	{ 
		parent::__construct ( $default );
		$this->registerTask ( 'add', 'edit' );
		
		
	}
	
	function edit() 
	{
		JRequest::setVar ( 'view', 'zipcode_detail' );
		JRequest::setVar ( 'layout', 'default' );
		JRequest::setVar ( 'hidemainmenu', 1 );
		$model = $this->getModel ( 'zipcode_detail' );
		
		parent::display ();
	}
	
	
	function apply() 
	{
       $this->save(1);
	}
	function save($apply=0) {	
		$post = JRequest::get ( 'post' );
		
		
		$city_name = JRequest::getVar( 'city_name', '', 'post', 'string', JREQUEST_ALLOWRAW );
		$post["city_name"] = $city_name;	
		$option = JRequest::getVar ('option');
		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );
		$post ['zipcode_id'] = $cid [0];
		$model = $this->getModel ( 'zipcode_detail' );
		
		if($post["zipcode_to"]=="")
		{
			$row = $model->store ( $post );
		} else {
			for($i=$post["zipcode"];$i<=$post["zipcode_to"];$i++)
			{
				$post['zipcode'] = $i;
				$row = $model->store ( $post );
			}
		}	
		
		
		if ($row) 
		{
			
			$msg = JText::_ ( 'ZIPCODE_DETAIL_SAVED' );
		
		} else 
		{
			
			$msg = JText::_ ( 'ERROR_SAVING_IN_ZIPCODE_DETAIL' );
			
		}

		
		if ($apply==1){
			$this->setRedirect ( 'index.php?option=' . $option . '&view=zipcode_detail&task=edit&cid[]='.$row->zipcode_id, $msg );
		}else {
			$this->setRedirect ( 'index.php?option=' . $option . '&view=zipcode', $msg);
		}
		
	}	
	
	function cancel() {
		
		$option = JRequest::getVar ('option');
		$msg = JText::_ ( 'ZIPCODE_DETAIL_EDITING_CANCELLED' );
		$this->setRedirect ( 'index.php?option='.$option.'&view=zipcode',$msg );
	}
	
	function remove() {
		
		$option = JRequest::getVar ('option');
		
		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );
		
		if (! is_array ( $cid ) || count ( $cid ) < 1) {
			JError::raiseError ( 500, JText::_ ( 'SELECT AN ITEM TO DELETE' ) );
		}
		
		$model = $this->getModel ( 'zipcode_detail' );
		if (! $model->delete ( $cid )) {
			echo "<script> alert('" . $model->getError ( true ) . "'); window.history.go(-1); </script>\n";
		}
		$msg = JText::_ ( 'ZIPCODE_DETAIL_DELETED_SUCCESSFULLY' );
		$this->setRedirect ( 'index.php?option='.$option.'&view=zipcode',$msg );
	}
	
	

	
}
?>