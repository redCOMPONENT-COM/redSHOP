<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;
JHTML::_('behavior.tooltip');
$Itemid         = JRequest::getInt('Itemid');
$loginlink      = 'index.php?option=com_redshop&view=login&Itemid=' . $Itemid;
$newuser_link   = 'index.php?option=com_redshop&view=registration&Itemid=' . $Itemid;
$shoppergroupid = JRequest::getInt('protalid', 0);

$returnitemid = $Itemid;

if (Redshop::getConfig()->get('PORTAL_LOGIN_ITEMID'))
{
	$returnitemid = Redshop::getConfig()->get('PORTAL_LOGIN_ITEMID');
}

$portallogofile = REDSHOP_FRONT_IMAGES_RELPATH . 'shopperlogo/' . Redshop::getConfig()->get('DEFAULT_PORTAL_LOGO');

$portallogo = RedShopHelperImages::getImagePath(
					Redshop::getConfig()->get('DEFAULT_PORTAL_LOGO'),
					'',
					'thumb',
					'shopperlogo',
					Redshop::getConfig()->get('THUMB_WIDTH'),
					Redshop::getConfig()->get('THUMB_HEIGHT'),
					Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
				);
$portalname = Redshop::getConfig()->get('DEFAULT_PORTAL_NAME');
$portalintro = "";

if ($shoppergroupid != 0)
{
	$portallogo = RedShopHelperImages::getImagePath(
					$this->ShopperGroupDetail[0]->shopper_group_logo,
					'',
					'thumb',
					'shopperlogo',
					Redshop::getConfig()->get('THUMB_WIDTH'),
					Redshop::getConfig()->get('THUMB_HEIGHT'),
					Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
				);
	$portalname  = $this->ShopperGroupDetail[0]->shopper_group_name;
	$portalintro = $this->ShopperGroupDetail[0]->shopper_group_introtext;
}

?>
<form action="<?php echo JRoute::_($loginlink); ?>" method="post">
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
		<tr>
			<td colspan="2" align="center">
				<?php
				if (JFile::exists($portallogofile))
				{
				?>
					<img src="<?php echo $portallogo; ?>">
				<?php
				}
				?>
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
					<?php echo JText::_('COM_REDSHOP_USERNAME'); ?>:
				</label>
				<input class="inputbox" type="text" id="username" name="username"/>
			</td>
		</tr>
		<tr>
			<td align="center" colspan="2">
				<label for="password">
					<?php echo JText::_('COM_REDSHOP_PASSWORD'); ?>:
				</label>
				<input class="inputbox" id="password" name="password" type="password"/>
			</td>
		</tr>
		<tr>
			<td align="center" colspan="2"><input type="submit" name="submit" class="button btn btn-primary"
			                                      value="<?php echo JText::_('COM_REDSHOP_LOGIN'); ?>"></td>
		</tr>
	</table>
	<input type="hidden" name="task" id="task" value="setlogin">
	<input type="hidden" name="protalid" value="<?php echo $shoppergroupid; ?>">
	<input type="hidden" name="returnitemid" id="returnitemid" value="<?php echo $returnitemid; ?>">
	<input type="hidden" name="option" id="option" value="com_redshop"/>
</form>
<div>&nbsp;</div>
