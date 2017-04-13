<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

$serialized = JFactory::getApplication()->getUserState("com_redshop.order.batch.postdata");
$postdata   = unserialize($serialized);
$orderIds   = implode(',', $postdata['cid']);
?>
<a class="btn btn-lg" href="index.php?option=com_redshop&view=order">
    <i class="fa fa-backward"></i>
	<?php echo JText::_('COM_REDSHOP_ORDER_STATUS_UPDATE_BACK_TO_ORDER_LIST') ?>
</a>
<hr />
<div id="editcell" class="well">
    <fieldset>
        <legend><?php echo JText::_('COM_REDSHOP_ORDER_STATUS_UPDATE_LOG'); ?></legend>
        <div id="loopStatus" class="alert">&nbsp;</div>
        <ul id="loopLog" class="nav nav-list">&nbsp;</ul>
    </fieldset>
</div>
<script type="text/javascript">
    window.addEvent('domready', function () {
        // declare JavaScript Aarray
        var orderIds = [<?php echo $orderIds; ?>],
            oids = []
        ind = 0;

        // initiallize function
        recursiveLoop(orderIds, ind);

        // recursive function defination
        function recursiveLoop(a, i) {
            var logElement = document.id('loopLog'),
                statusElement = document.id('loopStatus'),
                oid = a[i];

            if (i < a.length) {
                i++;

                var batchRequest = new Request({
                    url: 'index.php?option=com_redshop&option=com_redshop&tmpl=component&view=order&json=1&task=updateOrderStatus&oid=' + oid,
                    method: 'get',
                    onRequest: function () {
                        statusElement.show().set('text', 'loading...').addClass('alert-info');
                    },
                    onSuccess: function (responseText) {

                        responseObj = JSON.parse(responseText);

                        logElement.set('html', logElement.get('html') + responseObj.message);

                        // Call next batch
                        recursiveLoop(a, i);
                    },
                    onFailure: function (xhr) {
                        statusElement.show()
                            .set('text', '<?php echo JText::_("COM_REDSHOP_AJAX_ORDER_UPDATE_FAIL"); ?>' + xhr.statusText)
                            .removeClass('alert-info')
                            .addClass('alert-error');
                    }
                }).send();
            }
            else {
                statusElement.className += " hidden";

                window.setTimeout(function(){
                    window.location = 'index.php?option=com_redshop&view=order';
                }, 1000);
            }
        }
    });
</script>
