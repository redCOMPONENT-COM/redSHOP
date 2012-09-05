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
/**
 * Product rating Controller
 *
 * @static
 * @package		redSHOP
 * @since 1.0
 */
class product_ratingController extends JController
{
	function __construct( $default = array())
	{
		parent::__construct( $default );
	}
	/**
	 * save function
	 *
	 * @access public
	 * @return void
	 */
	function save()
	{
		$post = JRequest::get('post');
		$option = JRequest::getVar('option');
		$Itemid = JRequest::getVar('Itemid');
		$product_id = JRequest::getInt('product_id');
		$category_id = JRequest::getInt('category_id');
		$model = $this->getModel('product_rating');
		$rate = JRequest::getVar('rate');
		
		if ($model->sendMailForReview($post)){
			$msg = JText::_('EMAIL_HAS_BEEN_SENT_SUCCESSFULLY');
		}else {
			$msg = JText::_('EMAIL_HAS_NOT_BEEN_SENT_SUCCESSFULLY');
		}
		if($rate==1)
		{
			$link = 'index.php?option='.$option.'&view=product&pid='.$product_id.'&cid='.$category_id.'&Itemid='.$Itemid;
			$this->setRedirect($link,$msg);
		}
		else
		{
			echo $msg;?>
			<span id="closewindow"><input type="button" value="Close Window" onclick="window.parent.document.getElementById( 'sbox-window' ).close();" /></span>
			<script>
			setTimeout("window.parent.document.getElementById( 'sbox-window' ).close()",5000);
			</script>
			<?php
			exit;
		}
	}
}?>