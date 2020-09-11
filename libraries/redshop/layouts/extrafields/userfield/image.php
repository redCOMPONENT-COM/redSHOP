<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * $displayData extract
 *
 * @var   array  $displayData Layout data.
 * @var   object $rowData     Extra field data
 * @var   string $required    Extra field required
 * @var   string $uniqueId    Extra field unique Id
 * @var   array  $fieldCheck  Extra field check
 * @var   int    $isAtt
 * @var   int    $productId
 */
extract($displayData);

$ajax    = '';

if (\Redshop::getConfig()->getInt('AJAX_CART_BOX') && $isAtt > 0) {
    $ajax = 'ajax';
}

?>
<table>
    <tr>
        <?php foreach ($fieldCheck as $key => $field) : ?>
            <td>
                <div class="userfield_input">
                    <img
                            class="pointer imgClass_<?php echo $uniqueId; ?>"
                            id="<?php echo $rowData->name . '_' . $field->value_id; ?>"
                            src="<?php echo REDSHOP_FRONT_IMAGES_ABSPATH . 'extrafield/' . $field->field_name; ?>"
                            title="<?php echo $field->field_value; ?>"
                            alt="<?php echo $field->field_value; ?>"
                            onclick="javascript:setProductUserFieldImage('<?php echo $rowData->name; ?>', '<?php echo $uniqueId; ?>', '<?php echo $field->field_value; ?>', this);"
                    >
                </div>
            </td>
        <?php endforeach; ?>
    </tr>
    <input
            type="hidden"
            name="extrafields<?php echo $uniqueId; ?>[]"
            id="<?php echo $ajax . $rowData->name . '_' . $uniqueId; ?>"
            userfieldlbl="<?php echo $rowData->title; ?>"
        <?php echo $required; ?>
    >
</table>
