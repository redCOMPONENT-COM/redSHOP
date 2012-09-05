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

class newslettersubscr_detailController extends JController {
	function __construct($default = array()) {
		parent::__construct ( $default );
		$this->registerTask ( 'add', 'edit' );
	}
	function edit()
	{
		JRequest::setVar ( 'view', 'newslettersubscr_detail' );
		JRequest::setVar ( 'layout', 'default' );
		JRequest::setVar ( 'hidemainmenu', 1 );

		$model = $this->getModel ('newslettersubscr_detail');

		$userlist = $model->getuserlist();
		//merging select option in the select box
		$temps = array();
		$temps[0]->value=0;
		$temps[0]->text=JText::_('SELECT');
		$userlist=array_merge($temps,$userlist);

		JRequest::setVar ( 'userlist',$userlist );

		parent::display ();
	}
	function apply() {
		$this->save(1);
	}
	function save($apply=0) {

		$post = JRequest::get ( 'post' );
		$body = JRequest::getVar( 'body', '', 'post', 'string', JREQUEST_ALLOWRAW );
		$post["body"] = $body;

		$option = JRequest::getVar ('option');
		$model = $this->getModel ( 'newslettersubscr_detail' );
		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );

		$post ['subscription_id'] = $cid [0];

		$userinfo = $model->getUserFromEmail($post['email']);
		if(count($userinfo)>0)
		{
			$post['email'] = $userinfo->user_email;
//			$post['name'] = $userinfo->firstname." ".$userinfo->lastname;
			$post['user_id'] = $userinfo->user_id;
		}
		$post ['name'] = $post['username'];

		if ($row=$model->store ( $post )) {

			$msg = JText::_ ( 'NEWSLETTER_SUBSCR_DETAIL_SAVED' );

		} else {

			$msg = JText::_ ( 'ERROR_SAVING_NEWSLETTER_SUBSCR_DETAIL' );
		}

		if($apply==1)
			$this->setRedirect ( 'index.php?option=' . $option . '&view=newslettersubscr_detail&task=edit&cid[]='.$row->subscription_id, $msg );
		else
			$this->setRedirect ( 'index.php?option=' . $option . '&view=newslettersubscr', $msg );
	}
	function remove() {

		$option = JRequest::getVar ('option');

		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );

		if (! is_array ( $cid ) || count ( $cid ) < 1) {
			JError::raiseError ( 500, JText::_ ( 'SELECT_AN_ITEM_TO_DELETE' ) );
		}

		$model = $this->getModel ( 'newslettersubscr_detail' );
		if (! $model->delete ( $cid )) {
			echo "<script> alert('" . $model->getError ( true ) . "'); window.history.go(-1); </script>\n";
		}
		$msg = JText::_ ( 'NEWSLETTER_SUBSCR_DETAIL_DELETED_SUCCESSFULLY' );
		$this->setRedirect ( 'index.php?option='.$option.'&view=newslettersubscr',$msg );
	}
	function publish() {

		$option = JRequest::getVar ('option');

		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );

		if (! is_array ( $cid ) || count ( $cid ) < 1) {
			JError::raiseError ( 500, JText::_ ( 'SELECT_AN_ITEM_TO_PUBLISH' ) );
		}

		$model = $this->getModel ( 'newslettersubscr_detail' );
		if (! $model->publish ( $cid, 1 )) {
			echo "<script> alert('" . $model->getError ( true ) . "'); window.history.go(-1); </script>\n";
		}
		$msg = JText::_ ( 'NEWSLETTER_SUBSCR_DETAIL_PUBLISHED_SUCCESFULLY' );
		$this->setRedirect ( 'index.php?option='.$option.'&view=newslettersubscr',$msg );
	}
	function unpublish() {

		$option = JRequest::getVar ('option');

		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );

		if (! is_array ( $cid ) || count ( $cid ) < 1) {
			JError::raiseError ( 500, JText::_ ( 'SELECT_AN_ITEM_TO_UNPUBLISH' ) );
		}

		$model = $this->getModel ( 'newslettersubscr_detail' );
		if (! $model->publish ( $cid, 0 )) {
			echo "<script> alert('" . $model->getError ( true ) . "'); window.history.go(-1); </script>\n";
		}
		$msg = JText::_ ( 'NEWSLETTER_SUBSCR_DETAIL_UNPUBLISHED_SUCCESFULLY' );
		$this->setRedirect ( 'index.php?option='.$option.'&view=newslettersubscr',$msg );
	}
	function cancel() {

		$option = JRequest::getVar ('option');
		$msg = JText::_ ( 'NEWSLETTER_SUBSCR_DETAIL_EDITING_CANCELLED' );
		$this->setRedirect ( 'index.php?option='.$option.'&view=newslettersubscr',$msg );
	}
	function export_data(){

		$model = $this->getModel('newslettersubscr_detail');

		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
       	header("Content-type: text/x-csv");
    	header("Content-type: text/csv");
    	header("Content-type: application/csv");
    	header('Content-Disposition: attachment; filename=NewsletterSbsc.csv');

    	echo "Subscriber Full Name,Newsletter,Email Id\n\n";
    	$data = $model->getnewslettersbsc();

    	for($i=0;$i<count($data);$i++)
		{
			$subname = $model->getuserfullname( $data[$i]->user_id);
			echo $fullname = $subname->firstname." ".$subname->lastname;
			echo ",";
			echo $data[$i]->name.",";
			echo $subname->email.",";
			echo "\n";
		}

    	exit;
	}

	function export_acy_data(){
		ob_clean();
		$model = $this->getModel('newslettersubscr_detail');
		$cid = JRequest::getVar ( 'cid', array (), 'post', 'array' );
       	$data = $model->getnewslettersbsc($cid);

		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
       	header("Content-type: text/x-csv");
    	header("Content-type: text/csv");
    	header("Content-type: application/csv");
    	header('Content-Disposition: attachment; filename=import_to_acyba.csv');

       	echo '"email","name","enabled"';
		echo "\n";

    	for($i=0;$i<count($data);$i++)
		{

				echo '"'.$data[$i]->email.'","';
				if($data[$i]->user_id != 0){
					$subname = $model->getuserfullname( $data[$i]->user_id);
					echo $fullname = $subname->firstname." ".$subname->lastname;
				}else{
					echo $data[$i]->subscribername;
				}
				echo '","';
				echo $data[$i]->published.'"';
				echo "\n";
		}

    	exit;
	}
}