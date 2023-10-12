<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

$app = JFactory::getApplication();
?>

<?php if ($this->params->get('show_page_heading', 1)): ?>
    <div class="componentheading<?php echo $this->params->get('pageclass_sfx') ?>">
        <?php echo $this->escape($this->productInfo->product_name); ?>
    </div>
<?php endif; ?>
<?php

$displayData = array(
    'form'      => $this->form,
    'modal'     => 1,
    'productId' => $this->productId
);

$form = RedshopModelForm::getInstance(
    'Product_Rating',
    'RedshopModel',
    array(
        'context' => 'com_redshop.edit.product_rating.' . $this->productId
    )
)-> /** @scrutinizer ignore-call */getForm();

echo RedshopLayoutHelper::render('product.product_rating', $displayData);
?>