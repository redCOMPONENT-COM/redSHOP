<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

// Using MooTools for Joomla 2.5
JHTML::_( 'behavior.mootools' );

$serialized = JFactory::getApplication()->getUserState( "com_redshop.order.batch.postdata");
$postdata   = unserialize($serialized);
$orderIds   = implode(',', $postdata['cid']);

?>
<div id="editcell">
<fieldset>
	<legend><?php echo JText::_('COM_REDSHOP_ORDER_STATUS_UPDATE_LOG');?></legend>
	<div id="status">&nbsp;</div>
	<div id="log">&nbsp;</div>
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
		var logElement = document.id('log'),
				statusElement = document.id('status'),
				oid = a[i];

		if (i < a.length)
		{
			i++;

			var batchRequest = new Request({
				url: 'index.php?option=com_redshop&option=com_redshop&tmpl=component&view=order&json=1&task=updateOrderStatus&oid=' + oid,
				method: 'get',
				onRequest: function(){
					statusElement.set('text', 'loading...');
				},
				onSuccess: function(responseText){

					responseText = parseInt(responseText);

					if (responseText)
					{
						logElement.set('html', logElement.get('html') + '<p class="success"><?php echo JText::sprintf("COM_REDSHOP_SHIPPING_PDF_CREATED", "' + responseText + '"); ?></p>');

						oids.push(parseInt(responseText));
					}
					else
					{
						logElement.set('html', logElement.get('html') + '<p class="red"><?php echo JText::sprintf("COM_REDSHOP_SHIPPING_PDF_CREATE_FAIL", "' + oid + '"); ?></p>');
					}

					// Call next batch
					recursiveLoop(a, i);
				},
				onFailure: function(){
					statusElement.set('text', '<?php echo JText::_("COM_REDSHOP_SHIPPING_PDF_FAIL"); ?>');
				}
			}).send();
		}
		else
		{
			var batchRequest = new Request({
				url: 'index.php?option=com_redshop&option=com_redshop&tmpl=component&view=order&json=1&task=mergeShippingPdf',
				method: 'post',
				data: {'mergeOrderIds':oids},
				onRequest: function(){
					statusElement.set('text', 'loading...');
				},
				onSuccess: function(responseText){
					statusElement.set('text', '');
					logElement.set(
						'html',
						logElement.get('html') + '<p><?php echo JText::_("COM_REDSHOP_SHIPPING_PDF_MERGE_FILE_FROM_HERE"); ?>: <a href="' + responseText + '">' + responseText + '</a></p>'
					);

					//window.history.go(-1);
					window.open(responseText,'_blank');
				},
				onFailure: function(){
					statusElement.set('text', '<?php echo JText::_("COM_REDSHOP_SHIPPING_PDF_FAIL"); ?>');
				}
			}).send();
		}
	}
});
</script>
