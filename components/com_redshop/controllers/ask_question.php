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
 * Ask Question Controller
 *
 * @static
 * @package		redSHOP
 * @since 1.0
 */
class ask_questionController extends JController
{
	function __construct( $default = array())
	{
		parent::__construct( $default );
	}
	/**
	 * Method to send Ask Question Mail.
	 *
	 */
	function sendaskquestionmail()
	{
		$post = JRequest::get('post');
		$product_id = $post['pid'];
		$Itemid = $post['Itemid'];
		$ask = JRequest::getVar('ask');
		$category_id = JRequest::getVar('category_id');

		$model = $this->getModel('ask_question');

		if ($model->sendMailForAskQuestion($post)){
			$msg = JText::_('COM_REDSHOP_EMAIL_HAS_BEEN_SENT_SUCCESSFULLY');
		}else {
			$msg = JText::_('COM_REDSHOP_EMAIL_HAS_NOT_BEEN_SENT_SUCCESSFULLY');
		}
		if($ask==1)
		{
			$link = 'index.php?option=com_redshop&view=product&pid='.$product_id.'&cid='.$category_id.'&Itemid='.$Itemid;
			$this->setRedirect($link,$msg);
		}
		else
		{
			echo $msg;?>
			<span id="closewindow"><input type="button" value="Close Window" onclick="window.parent.redBOX.close();" /></span>
			<script>
			setTimeout("window.parent.redBOX.close();",5000);
			</script>
			<?php
			exit;
		}
	}
}
?>