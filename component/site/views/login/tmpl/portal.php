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
defined ('_JEXEC') or die ('restricted access');
JHTML::_('behavior.tooltip');
$option = JRequest::getVar('option');
$Itemid = JRequest::getVar('Itemid');
$loginlink='index.php?option='.$option.'&view=login&Itemid='.$Itemid;
$newuser_link='index.php?option='.$option.'&view=registration&Itemid='.$Itemid;
$forgotpwd_link='index.php?option='.$option.'&view=password&Itemid='.$Itemid;
$shoppergroupid = JRequest::getInt('protalid',0);

$returnitemid = $Itemid;
if(PORTAL_LOGIN_ITEMID)
{
	$returnitemid = PORTAL_LOGIN_ITEMID;
}

$portallogofile = JPATH_ROOT.'/components/com_redshop/assets/images/shopperlogo/'.DEFAULT_PORTAL_LOGO;
$portallogo = JURI::root().'components/com_redshop/helpers/thumb.php?filename=shopperlogo/'.DEFAULT_PORTAL_LOGO.'&newxsize='.THUMB_WIDTH.'&newysize='.THUMB_HEIGHT.'&swap='.USE_IMAGE_SIZE_SWAPPING;
$portalname = DEFAULT_PORTAL_NAME;
$portalintro = "";

if ($shoppergroupid != 0){
	$portallogo = JURI::root().'components/com_redshop/helpers/thumb.php?filename=shopperlogo/'.$this->ShopperGroupDetail[0]->shopper_group_logo.'&newxsize='.THUMB_WIDTH.'&newysize='.THUMB_HEIGHT.'&swap='.USE_IMAGE_SIZE_SWAPPING;	
	$portalname = $this->ShopperGroupDetail[0]->shopper_group_name ;
	$portalintro = $this->ShopperGroupDetail[0]->shopper_group_introtext ;
}

?>
<form action="<?php echo JRoute::_($loginlink); ?>" method="post">
<table cellpadding="0" cellspacing="0" border="0" width="100%">
		<tr>
			<td colspan="2" align="center">
			<?php if(is_file($portallogofile)){ ?>
				<img  src="<?php echo $portallogo;?>">
			<?php }?>
			</td>
		</tr>
		<tr>
			<td colspan="2" align="center">
			<label for="portalname"><strong><?php echo $portalname;?></strong></label>
			</td>
		</tr>
		<tr>
			<td colspan="2" align="center">
			<label for="portalintrotext"><?php echo $portalintro;?></label>
			</td>
		</tr>
		<tr>
			<td align="center" colspan="2">
				<label for="username">
					<?php echo JText::_('USERNAME'); ?>:
				</label>
				<input class="inputbox" type="text" id="username" name="username" />
			</td>
		</tr>
		<tr>
			<td align="center" colspan="2" >
				<label for="password">
					<?php echo JText::_('PASSWORD'); ?>:
				</label>			
				<input class="inputbox" id="password" name="password" type="password" />
			</td>			
		</tr>
		<tr>
			<td align="center" colspan="2"><input type="submit" name="submit" class="button" value="<?php echo JText::_('LOGIN'); ?>"></td>
		</tr>		
	</table>
	<input type="hidden" name="task" id="task" value="setlogin">
	<input type="hidden" name="protalid" value="<?php echo $shoppergroupid;?>">
	<input type="hidden" name="returnitemid" id="returnitemid" value="<?php echo $returnitemid;?>">
	<input type="hidden" name="option" id="option" value="<?php echo $option;?>"/> 
</form>
<div>&nbsp;</div>