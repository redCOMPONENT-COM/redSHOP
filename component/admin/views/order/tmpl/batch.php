<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

$serialized = JFactory::getApplication()->getUserState( "com_redshop.order.batch.postdata");
$postdata   = unserialize($serialized);
$orderIds   = implode(',', $postdata['cid']);

?>
<div id="editcell" class="well">
	<fieldset>
		<legend><?php echo JText::_('COM_REDSHOP_ORDER_STATUS_UPDATE_LOG');?></legend>
		<div id="loopStatus" class="alert">&nbsp;</div>
		<ul id="loopLog" class="nav nav-list">&nbsp;</ul>
	</fieldset>
</div>
<script type="text/javascript">
window.addEvent('domready', function() {
	// declare JavaScript Aarray
	var orderIds = [<?php echo $orderIds; ?>],
		oids = []
		ind = 0;

	// initiallize function
	recursiveLoop(orderIds, ind);

	// recursive function defination
	function recursiveLoop(a, i)
	{
		var logElement = document.id('loopLog'),
				statusElement = document.id('loopStatus'),
				oid = a[i];

		if (i < a.length)
		{
			i++;

			var batchRequest = new Request({
				url: 'index.php?option=com_redshop&option=com_redshop&tmpl=component&view=order&json=1&task=updateOrderStatus&oid=' + oid,
				method: 'get',
				onRequest: function(){
					statusElement.show().set('text', 'loading...').addClass('alert-info');
				},
				onSuccess: function(responseText){

					responseText = parseInt(responseText);

					if (responseText)
					{
						logElement.set('html', logElement.get('html') + '<li class="success text-success"><?php echo JText::sprintf("COM_REDSHOP_SHIPPING_PDF_CREATED", "<span class=\"badge badge-success\">' + responseText + '</span>"); ?></li>');

						oids.push(parseInt(responseText));
					}
					else
					{
						logElement.set('html', logElement.get('html') + '<li class="red text-error"><?php echo JText::sprintf("COM_REDSHOP_SHIPPING_PDF_CREATE_FAIL", "<span class=\"badge badge-important\">' + oid + '</span>"); ?></li>');
					}

					// Call next batch
					recursiveLoop(a, i);
				},
				onFailure: function(xhr){
					statusElement.show()
								.set('text', '<?php echo JText::_("COM_REDSHOP_SHIPPING_PDF_FAIL"); ?>' + xhr.statusText)
								.removeClass('alert-info')
								.addClass('alert-error');
				}
			}).send();
		}
		else
		{
			statusElement.hide();

			if (oids.length)
			{
				var batchRequest = new Request({
					url: 'index.php?option=com_redshop&option=com_redshop&tmpl=component&view=order&json=1&task=mergeShippingPdf',
					method: 'post',
					data: {'mergeOrderIds':oids},
					onRequest: function(){
						statusElement.show().set('text', 'loading...');
					},
					onSuccess: function(responseText){

						statusElement.hide();

						logElement.set(
							'html',
							logElement.get('html') + '<p class=\"text-info\"><?php echo JText::_("COM_REDSHOP_SHIPPING_PDF_MERGE_FILE_FROM_HERE"); ?>: <a href="' + responseText + '">' + responseText + '</a></p>'
						);

						window.open(responseText,'_blank');
					},
					onFailure: function(xhr){
						statusElement.show()
									.set('text', '<?php echo JText::_("COM_REDSHOP_SHIPPING_PDF_FAIL"); ?>' + xhr.statusText)
									.removeClass('alert-info')
									.addClass('alert-error');
					}
				}).send();
			}
		}
	}
});
</script>
