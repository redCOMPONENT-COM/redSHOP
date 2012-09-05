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

defined( '_JEXEC' ) or die( 'Restricted access' );


jimport( 'joomla.application.component.controller' );

class barcodeController extends JController
{
	function __construct( )
	{

		parent::__construct( );

	}

	function display() {

		parent::display();

	}

	function getsearch()
	{
        $post = JRequest::get ( 'post' );

        if(strlen($post['barcode'])!=13)
        {

		           $msg = 'Invalid Barcode';
		           JError::raiseWarning(0,$msg);
		           parent::display();
        }
        else
        {


		          $model = $this->getModel('barcode');
                  $barcode= $post['barcode'];
                  $barcode= substr($barcode, 0,12);

			      $user =& JFactory::getUser();
			      $uid= $user->get('id');
				  $mainframe = JFactory::getApplication();
                  $row = $model->checkorder($barcode);



						   if($row)
						  {


						    $post['search_date']=date("y-m-d H:i:s");
						    $post['user_id']=$uid;
                            $post['order_id']=$row->order_id;

							 if($model->save($post))
							 {
								$msg = JText::_('THANKS_FOR_YOUR_REVIEWS');
							 }
							 else
							 {
								$msg = JText::_('ERROR_PLEASE_TRY_AGAIN');
							 }
							//return $log;
							 $this->setRedirect('index.php?option=com_redshop&view=barcode&order_id='.$row->order_id);

						 }
						 else
						 {
		                     $msg = 'Invalid Barcode';
				             JError::raiseWarning(0,$msg);
				             parent::display();
						 }
				 }

        }
        
		function changestatus()
		{
		
			$post = JRequest::get ( 'post' );

	        if(strlen($post['barcode'])!=13)
	        {
	
		           $msg = 'Invalid Barcode';
		           JError::raiseWarning(0,$msg);
		           $this->setRedirect('index.php?option=com_redshop&view=barcode&layout=barcode_order');
	        } else
      		{

		          $model = $this->getModel('barcode');
                  $barcode= $post['barcode'];
                  $barcode= substr($barcode, 0,12);

				  $mainframe = JFactory::getApplication();
                  $row = $model->checkorder($barcode);
			 	  if($row)
				  {
				    
					 $update_status =  $model->updateorderstatus($barcode,$row->order_id);
					 $this->setRedirect('index.php?option=com_redshop&view=barcode&layout=barcode_order',JText::_('ORDER_STATUS_CHANGED_TO_SHIPPED'));

				  }
				  else
				  {
                     $msg = 'Invalid Barcode';
		             JError::raiseWarning(0,$msg);
		             $this->setRedirect('index.php?option=com_redshop&view=barcode&layout=barcode_order');
				  }
			}
		}




}