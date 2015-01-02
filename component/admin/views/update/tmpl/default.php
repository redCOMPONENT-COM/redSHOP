<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

if (version_compare(JVERSION, '3.0', '<'))
{
	$document = JFactory::getDocument();
	$document->addStyleSheet(JURI::root() . 'administrator/components/com_redshop/assets/css/update.css');
}

?>
<script type="text/javascript">
	(function ($) {
		function syncItem(persent, progressLog)
		{
			var progressUpdate = $('div#progressUpdate');
			$.ajax({
				url: 'index.php?option=com_redshop&task=update.update',
				dataType: 'json',
				type: 'POST',
				beforeSend: function () {
					progressUpdate.find('div.bar').css('width', '0%');
					progressUpdate.find('div.bar-success').css('width', persent+'%');
					progressUpdate.addClass('active');
					$('#toolbar-cancel').css({'display':'none'});
				}
			}).always(function(data, textStatus){
				var haveErrors = false;
				if(textStatus == 'timeout' || textStatus == 'parsererror') {
					progressLog.append('<span class="label label-important"><?php echo JText::_('COM_REDSHOP_UPDATE_ERROR_TIMEOUT', true); ?></span><br />');
					haveErrors = true;
				}
				else if (typeof data === 'undefined' || textStatus == 'error') {
					progressLog.append('<span class="label label-important"><?php echo JText::_('COM_REDSHOP_UPDATE_ERROR_APPLICATION_ERROR', true); ?></span><br />');
					haveErrors = true;
				}
				else {
					if (data.messages.length > 0)
					{
						$(data.messages).each(function (messageIdx, messageData) {
							progressLog.append('<span class="label label-' + messageData.type_message + '">' + messageData.message + '</span><br />');
							if (messageData.type_message == 'important')
							{
								haveErrors = true;
							}
						});
					}
				}

				if(!haveErrors && data.success != false && typeof data.success[0] !== 'undefined' && typeof data.success[0]['parts'] !== 'undefined')
				{
					var persent = 100 - Math.ceil((100 * data.success[0]['parts'])/data.success[0]['total']);
					syncItem(persent, progressLog);
				}
				else
				{
					if(haveErrors || data.success == false){
						var widthProgress = progressUpdate.width(),
							widthSuccess = progressUpdate.find('.bar-success').width(),
							persentError = Math.floor(100 * (widthProgress - widthSuccess)/widthProgress);
						progressUpdate.append('<div class="bar bar-danger" style="width: ' + persentError +'%;"></div>');
					}
					else{
						progressUpdate.find('div.bar-success').css('width', '100%');
					}
					progressUpdate.removeClass('active');
					$('#toolbar-cancel').css({'display':'inline-block'});
				}
			});
		}
		Joomla.submitbutton = function (task)
		{
			if (task == 'update.update')
			{
				var progressLog = jQuery('div#progress-log');
				progressLog.html('');
				syncItem(20, progressLog);
			}
			else
			{
				Joomla.submitform(task);
			}
		}
	})(jQuery);
</script>
<div class="well" id="divUpdate">
	<div id="progressUpdate" class="progress progress-striped">
		<div class="bar bar-success" style="width: 0%"></div>
	</div>
	<h4><?php echo JText::_('COM_REDSHOP_UPDATE_NOTICE') ?></h4>
	<div class="progress-log" id="progress-log"></div>
</div>
