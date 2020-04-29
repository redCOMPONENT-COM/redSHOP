<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

$document = JFactory::getDocument();

// For barcode
$model = $this->getModel('order_detail');
$order = $this->OrdersDetail;
$document->setTitle(JText::_('COM_REDSHOP_ORDER_RECEIPT_TITLE'));
?>
<?php
if ($this->params->get('show_page_title', 1)) {
    ?>
    <h1 class="componentheading<?php echo $this->params->get('pageclass_sfx') ?>">
        <?php echo $this->escape(JText::_('COM_REDSHOP_ORDER_RECEIPT')); ?>
    </h1>
    <?php
}

if (!Redshop::getConfig()->get('ONESTEP_CHECKOUT_ENABLE')) {
    echo JLayoutHelper::render('cart.wizard', array('step' => '3'));
}

if (Redshop::getConfig()->get('USE_AS_CATALOG')) {
    $receiptTemplate = RedshopHelperTemplate::getTemplate("catalogue_order_receipt");
    $receiptTemplate = $receiptTemplate[0]->template_desc;
} else {
    $receiptTemplate = RedshopHelperTemplate::getTemplate("order_receipt");

    if (count($receiptTemplate) > 0 && $receiptTemplate[0]->template_desc) {
        $receiptTemplate = $receiptTemplate[0]->template_desc;
    } else {
        $receiptTemplate = RedshopHelperTemplate::getDefaultTemplateContent('order_receipt');
    }
}

// Replace Reorder Button
$this->replaceReorderButton($receiptTemplate);

$receiptTemplate = RedshopTagsReplacer::_(
    'orderreceipt',
    $receiptTemplate,
    array(
        'order' => $order
    )
);

echo eval("?>" . $receiptTemplate . "<?php ");

// Handle order total for split payment
$session = JFactory::getSession();
$isSplit = $session->get('issplit');

if ($isSplit) {
    $splitAmount        = ($order->order_total) / 2;
    $order->order_total = $splitAmount;
}

// End

$model->billingaddresses();

if ($order->analytics_status == 0) {
    $model->UpdateAnalytics_status($order->order_id);
}