<?php

defined ( '_JEXEC' ) or die ( 'Restricted access' );

jimport ( 'joomla.application.component.controller' );

class currency_detailController extends JController
 {
	
	function __construct($default = array()) 
	{ 
		parent::__construct ( $default );
		$this->registerTask ( 'add', 'edit' );
	}
	
	function edit() 
	{
		JRequest::setVar ( 'view', 'currency_detail' );
		JRequest::setVar ( 'layout', 'default' );
		JRequest::setVar ( 'hidemainmenu', 1 );
		
		$model = $this->getModel ( 'currency_detail' );
		parent::display ();
	}
	
	
	function apply() 
	{
       $this->save(1);
	}
	
	function save($apply=0) 
	{
		$post = JRequest::get ( 'post' );
		$currency_name = JRequest::getVar( 'currency_name', '', 'post', 'string', JREQUEST_ALLOWRAW );
		$post["currency_name"] = $currency_name;	
		$option = JRequest::getVar ('option');
		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );
		$post ['currency_id'] = $cid [0];
		
		if (is_array($post["dynamic_country_id"]))
			$post["dynamic_country_id"] = implode(",",$post["dynamic_country_id"]);
		
		$model = $this->getModel ( 'currency_detail' );
		$row = $model->store ( $post );	
		if ($row) 
		{
			$msg = JText::_ ( 'CURRENCY_DETAIL_SAVED' );
		} else 
		{
			$msg = JText::_ ( 'ERROR_SAVING_CURRENCY_DETAIL' );
		}
		if ($apply==1)
		{
			if(isset($post['object']) && $post['object']=='cid')
			{
				$this->setRedirect ( 'index2.php?option='.$option.'&view=currency_detail&task=edit&cid[]='.$row->currency_id.'&object=cid',$msg );
			}
			else
			{
				$this->setRedirect ( 'index.php?option=' . $option . '&view=currency_detail&task=edit&cid[]='.$row->currency_id, $msg );
			}
		}
		else 
		{
			if(isset($post['object']) && $post['object']=='cid')
			{
				$this->setRedirect ( 'index3.php?option='.$option.'&view=currency&object=cid',$msg );
			}
			else
			{
				$this->setRedirect ( 'index.php?option=' . $option . '&view=currency', $msg);
			}
		}
	}	
	
	function cancel() 
	{
		$currencyobject = JRequest::getVar( 'object' );
		$option = JRequest::getVar ('option');
		$msg = JText::_ ( 'CURRENCY_DETAIL_EDITING_CANCELLED' );
		if($currencyobject=='cid')
		{
			$this->setRedirect ( 'index3.php?option='.$option.'&view=currency&object=cid',$msg );
		}
		else
		{
			$this->setRedirect ( 'index.php?option='.$option.'&view=currency',$msg );
		}
	}
	
	function remove() 
	{
		$option = JRequest::getVar ('option');
		
		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );
		
		if (! is_array ( $cid ) || count ( $cid ) < 1) {
			JError::raiseError ( 500, JText::_ ( 'SELECT AN ITEM TO DELETE' ) );
		}
		
		$model = $this->getModel ( 'currency_detail' );
		if (! $model->delete ( $cid )) {
			echo "<script> alert('" . $model->getError ( true ) . "'); window.history.go(-1); </script>\n";
		}
		$msg = JText::_ ( 'CURRENCY_DETAIL_DELETED_SUCCESSFULLY' );
		$this->setRedirect ( 'index.php?option='.$option.'&view=currency',$msg );
	}	
}	?>