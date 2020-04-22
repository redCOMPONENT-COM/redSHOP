<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JHTML::_('behavior.modal');

$app = JFactory::getApplication();
$config = Redconfiguration::getInstance();

$url = JURI::base();
$Itemid = $app->input->getInt('Itemid');
$wishlists = $this->wishlists;
$productId = $app->input->getInt('product_id');
$user = JFactory::getUser();

$pagetitle = JText::_('COM_REDSHOP_MY_WISHLIST');

$redTemplate = Redtemplate::getInstance();
$template = RedshopHelperTemplate::getTemplate("wishlist_template");
$wishlistData = $template[0]->template_desc;
$returnArr = \Redshop\Product\Product::getProductUserfieldFromTemplate($wishlistData);
$templateUserField = $returnArr[0];
$userfieldArr = $returnArr[1];

if ($this->params->get('show_page_heading', 1)) {
    ?>
    <h1 class="componentheading<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>"><?php echo $pagetitle; ?></h1>
    <div>&nbsp;</div>
    <?php
}

$wishlistTemplateWapper = \RedshopTagsReplacer::_(
    'wishlist',
    $wishlistData,
    array(
        'user' => $user,
        'wishlists' => $wishlists,
        'wishlistSesion' => $this->wish_session,
        'userFieldArr' => $userfieldArr
    )
);

echo $wishlistTemplateWapper;
