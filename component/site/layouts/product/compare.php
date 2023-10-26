<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;

$itemId = RedshopHelperRouter::getRedShopMenuItem(
    array(
        'option' => 'com_redshop',
        'view'   => 'product',
        'layout' => 'compare'
    )
);

$compareLink = Redshop\IO\Route::_('index.php?option=com_redshop&view=product&layout=compare&Itemid=' . $itemId);

?>
<div class="compare_product_div">
    <a class="btn btn-primary" href="<?php echo $compareLink ?>">
        <?php echo Text::_(
            'COM_REDSHOP_SHOW_PRODUCTS_TO_COMPARE'
        ) ?>
    </a>
    <div id="divCompareProduct"></div>
</div>