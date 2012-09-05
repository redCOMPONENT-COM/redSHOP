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
// no direct access
defined('_JEXEC') or die('Restricted access');
require_once( JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_redshop'.DS.'helpers'.DS.'order.php' );
$order_function = new order_functions();
$user =& JFactory::getUser();
$mainparam=$params->def('logging_greeting', 1);
$maintext=$params->def('greeting_text', 1);
$document = JFactory::getDocument();
$document->addStyleSheet("modules/mod_redshop_logingreeting/css/logingreeting.css");
?>
<div id="mod_logingreeting">
<?php
if($user->id!='')
{
	if($mainparam==0)
	{?>
	<div class="logingreeting" ><?php echo $maintext;?> <?php echo $user->username;?></div>
<?php }else{?>
	<div class="loginname" ><?php echo $maintext;?> <?php echo $order_function->getUserFullname($user->user_id);?></div>
<?php }
}
?>
</div>