<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;

/**
 * $displayData extract
 *
 * @var   array   $displayData Display data
 * @var   integer $link        Link
 * @var   integer $productId   Product id
 * @var   string  $formId      Form id
 */

use Joomla\CMS\Language\Text;

extract($displayData);

$user          = Factory::getApplication()->getIdentity();
$wishlistExist = 'icon icon-heart-2';
$checkWishlist = RedshopHelperWishlist::checkWishlistExist($productId);

if ($checkWishlist) {
    $wishlistExist = 'icon icon-heart';
}
?>

<?php if (!$user->guest): ?>
        <button class="btn btn-primary redshop-wishlist-button modalAddToWishlistButton" type="button" data-url="<?php echo $link ?>"
                data-productid="<?php echo $productId ?>" data-formid="<?php echo $formId ?>" >
            <i class="<?php echo $wishlistExist; ?>"></i> <?php echo Text::_("COM_REDSHOP_ADD_TO_WISHLIST") ?>
        </button>
<?php else: ?>
        <?php if (Redshop::getConfig()->get('WISHLIST_LOGIN_REQUIRED') != 0): ?>
                <input type="submit" class="btn btn-primary redshop-wishlist-form-button" name="btnwishlist" 
                       id="btnwishlist" value="<?php echo Text::_("COM_REDSHOP_ADD_TO_WISHLIST") ?>"
                       onclick="window.location='<?php echo $link ?>'"/>
        <?php else: ?>
                <form method="post" action="" id="form_wishlist_<?php echo $productId ?>_link"
                      name="form_wishlist_<?php echo $productId ?>_link">
                    <input type='hidden' name='task' value='addtowishlist'/>
                    <input type='hidden' name='product_id' value='<?php echo $productId ?>'/>
                    <input type='hidden' name='view' value='product'/>
                    <input type='hidden' name='attribute_id' value=''/>
                    <input type='hidden' name='property_id' value=''/>
                    <input type='hidden' name='subattribute_id' value=''/>
                    <input type='hidden' name='rurl' value='<?php echo base64_encode(JUri::getInstance()->toString()) ?>'/>

                    <input type="submit" data-productid="<?php echo $productId ?>" data-formid="<?php echo $formId ?>"
                           class="btn btn-primary redshop-wishlist-form-button" name="btnwishlist" id="btnwishlist"
                           value="<?php echo Text::_("COM_REDSHOP_ADD_TO_WISHLIST") ?>"/>
                </form>
        <?php endif; ?>
<?php endif; ?>