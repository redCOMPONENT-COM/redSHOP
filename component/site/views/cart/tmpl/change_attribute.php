<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

$app       = JFactory::getApplication();
$cart      = $this->cart;
$cartIndex = $app->input->getInt('cart_index');
$productId = $app->input->getInt('pid');

?>
    <script type="text/javascript">
        function cancelForm(frm) {
            frm.task.value = 'cancel';
            frm.submit();
        }

        function submitChangeAttribute() {
            calculateTotalPrice(<?php echo $productId;?>, 0);
            var requiedAttribute = document.getElementById('requiedAttribute').value;
            var requiedProperty = document.getElementById('requiedProperty').value;

            if (requiedAttribute != 0 && requiedAttribute != "") {
                alert(requiedAttribute);
                return false;
            } else if (requiedProperty != 0 && requiedProperty != "") {
                alert(requiedProperty);
                return false;
            } else {
                document.frmchngAttribute.submit();
            }
        }
    </script>
<?php
$cartAttribute = RedshopHelperTemplate::getTemplate("change_cart_attribute");

if (count($cartAttribute) > 0 && $cartAttribute[0]->template_desc) {
    $templateDesc = $cartAttribute[0]->template_desc;
} else {
    $templateDesc = RedshopHelperTemplate::getTemplate("change_cart_attribute");
}

$templateDesc = RedshopTagsReplacer::_(
    'changecartattribute',
    $templateDesc,
    array(
        'cart'      => $cart,
        'cartIndex' => $cartIndex,
        'productId' => $productId
    )
);

echo eval("?>" . $templateDesc . "<?php ");
