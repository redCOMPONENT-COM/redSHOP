<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_shoppergrouplogo
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

$user = JFactory::getUser();
$portalLogo = '';

if (!$user->id)
{
	if (JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . 'shopperlogo/' . Redshop::getConfig()->get('DEFAULT_PORTAL_LOGO')))
	{
		$portalLogo = RedShopHelperImages::getImagePath(
			Redshop::getConfig()->get('DEFAULT_PORTAL_LOGO'),
			'',
			'thumb',
			'shopperlogo',
			$thumbwidth,
			$thumbheight
		);
	}
}
elseif ($userInfo = RedshopHelperUser::getUserInformation($user->id))
{
	if (JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . 'shopperlogo/' . $userInfo->shopper_group_logo))
	{
		$portalLogo = RedShopHelperImages::getImagePath(
			$userInfo->shopper_group_logo,
			'',
			'thumb',
			'shopperlogo',
			$thumbwidth,
			$thumbheight
		);
	}
}
?>
<div class="mod_redshop_shoppergrouplogo">
	<?php if ($portalLogo): ?>
	<img src="<?php echo $portalLogo; ?>">
	<?php endif; ?>
</div>
