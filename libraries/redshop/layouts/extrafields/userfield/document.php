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
use Joomla\CMS\HTML\HTMLHelper;

JHtml::_('redshopjquery.framework');
HTMLHelper::script('com_redshop/ajaxupload.min.js', ['relative' => true]);

/**
 * $displayData extract
 *
 * @var   array $displayData Layout data.
 * @var   object $rowData    Extra field data
 * @var   string $required   Extra field required
 * @var   string $uniqueId   Extra field unique Id
 * @var   array $fieldCheck  Extra field check
 * @var   int $isAtt
 * @var   int $productId
 */

extract($displayData);

$http_referer = JFactory::getApplication()->input->server->getString('HTTP_REFERER', '');
$ajax         = '';

$unique = $rowData->name . '_' . $productId;

if ($isAtt > 0) {
    $ajax   = 'ajax';
    $unique = $rowData->name;
}

?>
<div class="userfield_input">
    <input type="button" class="<?php echo $rowData->class; ?>" id="file<?php echo $ajax . $unique ?>"
        name="file<?php echo $ajax . $unique ?>" value="<?php echo Text::_('COM_REDSHOP_UPLOAD'); ?>"
        size="<?php echo $rowData->size; ?>" userfieldlbl="<?php echo $rowData->title; ?>" <?php echo $required; ?> />
</div>
<?php if (
    strpos($http_referer, 'administrator') !== false
    && (strpos($http_referer, 'view=order_detail') !== false
        || strpos($http_referer, 'view=addorder_detail') !== false
        || strpos($http_referer, 'view=quotation') !== false
        || strpos($http_referer, 'view=quotation_detail') !== false
        || strpos($http_referer, 'view=addquotation_detail') !== false)
): ?>
        <script type="text/javascript" id="inner-ajax-script_<?php echo $uniqueId ?>">
            (function ($) {
                new AjaxUpload(
                    "file<?php echo $rowData->name . '_' . $uniqueId; ?>",
                    {
                        action: "<?php echo JUri::root(
                        ) ?>index.php?tmpl=component&option=com_redshop&view=product&task=ajaxupload",
                        data: {
                            mname: "file<?php echo $rowData->name . '_' . $uniqueId; ?>",
                            fieldName: "<?php echo $rowData->name ?>",
                            uniqueOl: "<?php echo $rowData->name . '_' . $uniqueId; ?>"
                        },
                        name: "file<?php echo $rowData->name . '_' . $uniqueId; ?>",
                        onSubmit: function (file, ext) {
                            jQuery('#<?php echo $rowData->name ?>').text("<?php echo Text::_(
                                   'COM_REDSHOP_UPLOADING'
                               ) . 'file'; ?>");
                        },
                        onComplete: function (file, response) {
                            jQuery("#ol_<?php echo $rowData->name; ?> li.error").remove();
                            jQuery('#ol_<?php echo $rowData->name; ?>').append(response);
                            var uploadfiles = jQuery('#ol_<?php echo $rowData->name; ?> li').map(function () {
                                return jQuery(this).find('span').text();
                            }).get().join(',');
                            jQuery('#<?php echo $rowData->name . '_' . $uniqueId; ?>').val(uploadfiles);
                            jQuery('#<?php echo $rowData->name; ?>').val(uploadfiles);
                            this.enable();
                        }
                    }
                );
            })(jQuery);
        </script>
<?php else: ?>
        <script>
            // jQuery.noConflict();
            new AjaxUpload(
                "file<?php echo $ajax . $unique ?>",
                {
                    action: "<?php echo JUri::root() ?>index.php?tmpl=component&option=com_redshop&view=product&task=ajaxupload",
                    data: {
                        mname: "file<?php echo $ajax . $rowData->name ?>",
                        product_id: "<?php echo $productId ?>",
                        uniqueOl: "<?php echo $unique ?>",
                        fieldName: "<?php echo $rowData->name ?>",
                        ajaxFlag: "<?php echo $ajax ?>"
                    },
                    name: "file<?php echo $ajax . $unique ?>",
                    onSubmit: function (file, ext) {
                        jQuery("file<?php echo $ajax . $unique ?>").text("<?php echo Text::_('COM_REDSHOP_UPLOADING') ?>" + file);
                        this.disable();
                    },
                    onComplete: function (file, response) {
                        jQuery("#ol_<?php echo $unique ?> li.error").remove();
                        jQuery("#ol_<?php echo $unique ?>").append(response);
                        var uploadfiles = jQuery("#ol_<?php echo $unique ?> li").map(function () {
                            return jQuery(this).find("span").text();
                        }).get().join(",");
                        this.enable();
                        jQuery("#<?php echo $ajax . $unique ?>").val(uploadfiles);
                        jQuery("#<?php echo $rowData->name ?>").val(uploadfiles);
                    }
                }
            );
        </script>
<?php endif; ?>