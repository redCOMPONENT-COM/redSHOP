<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die ('Restricted access');
$uri = JURI::getInstance();
$url = $uri->root();
$ord_path = "/components/com_redshop/assets/images/";

?>
<div id="config-document">
	<fieldset class="adminform">
		<legend><?php echo JText::_('COM_REDSHOP_NEWSLETTER'); ?></legend>
		<table width="100%" cellpadding="0" cellspacing="0" border="0">
			<tr valign="top">
				<td width="50%">
					<fieldset class="adminform">
						<table class="admintable">
							<tr>
								<td class="config_param"><?php echo JText::_('COM_REDSHOP_NEWSLETTER'); ?></td>
							</tr>
							<tr>
								<td width="100" align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_NEWSLETTER_CONFIRMATION_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_NEWSLETTER_CONFIRMATION_LBL'); ?>">
		<label for="newsletter_enable">
			<?php
			echo JText::_('COM_REDSHOP_NEWSLETTER_CONFIRMATION_LBL');
			?>
		</label></span></td>
								<td><?php
									echo $this->lists ['newsletter_confirmation'];
									?>
								</td>
							</tr>
							<tr>
								<td width="100" align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_NEWS_FROM_NAME'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_NEWS_FROM_NAME'); ?>">
		<label for="name">
			<?php
			echo JText::_('COM_REDSHOP_NEWS_FROM_NAME');
			?>
		</label></span></td>
								<td><input type="text" name="news_from_name" id="news_from_name"
								           value="<?php
								           echo NEWS_FROM_NAME;
								           ?>"
								           size="50">
								</td>
							</tr>
							<tr>
								<td width="100" align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_NEWS_MAIL_FROM'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_NEWS_MAIL_FROM'); ?>">
		<label for="name">
			<?php
			echo JText::_('COM_REDSHOP_NEWS_MAIL_FROM');
			?>
		</label></span></td>
								<td><input type="text" name="news_mail_from" id="news_mail_from"
								           value="<?php
								           echo NEWS_MAIL_FROM;
								           ?>"
								           size="50">
								</td>
							</tr>
							<tr>
								<td width="100" align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_DEFAULT_NEWSLETTER'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_DEFAULT_NEWSLETTER'); ?>">
		<label for="name">
			<?php
			echo JText::_('COM_REDSHOP_DEFAULT_NEWSLETTER');
			?>
		</label></span></td>
								<td>
									<?php
									echo $this->lists ['newsletters'];
									?>
								</td>
							</tr>
							<tr>
								<td width="100" align="right" class="key">
			<span class="editlinktip hasTip"
			      title="<?php echo JText::_('COM_REDSHOP_NEWSLETTER_TESTING_EMAIL_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_NEWSLETTER_TESTING_EMAIL_LBL'); ?>">
			<label for="name"><?php echo JText::_('COM_REDSHOP_NEWSLETTER_TESTING_EMAIL_LBL');?></label></span>
								</td>
								<td><input type="text" name="newsletter_test_email"
								           id="newsletter_test_email" value="" size="50">
								</td>
							</tr>
							<tr>
								<td width="100" align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_NEWSLETTER_TEST_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_NEWSLETTER_TEST_LBL'); ?>">
		<label for="name">
			<?php
			echo JText::_('COM_REDSHOP_NEWSLETTER_TEST_LBL');
			?>
		</label></span></td>
								<td><input type="button"
								           onclick="document.adminForm.task.value='save';form.submit();"
								           value="<?php
								           echo JText::_('COM_REDSHOP_SEND');
								           ?>"/></td>
							</tr>
							<tr>
								<td width="30%" align="right" class="key">
			<span class="editlinktip hasTip"
			      title="<?php echo JText::_('COM_REDSHOP_NEWSLETTER_MAIL_BATCHES_SENT_AT_ONE_TIME_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_NEWSLETTER_MAIL_BATCHES_SENT_AT_ONE_TIME_LBL'); ?>">
				<label
					for="name"><?php echo JText::_('COM_REDSHOP_NEWSLETTER_MAIL_BATCHES_SENT_AT_ONE_TIME_LBL');?></label>
			</span>
								</td>
								<td width="70%">
									<input type="text" name="newsletter_mail_chunk" id="newsletter_mail_chunk"
									       value="<?php echo NEWSLETTER_MAIL_CHUNK; ?>" size="20" maxlength="3">
								</td>
							</tr>
							<tr>
								<td width="30%" align="right" class="key">
			<span class="editlinktip hasTip"
			      title="<?php echo JText::_('COM_REDSHOP_PAUSE_SECONDS_EVERY_AMOUNT_OF_EMAILS_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_PAUSE_SECONDS_EVERY_AMOUNT_OF_EMAILS_LBL'); ?>">
					<label
						for="name"><?php echo JText::_('COM_REDSHOP_PAUSE_SECONDS_EVERY_AMOUNT_OF_EMAILS_LBL');?></label>
			</span>
								</td>
								<td width="70%">
									<input type="text" name="newsletter_mail_pause_time" id="newsletter_mail_pause_time"
									       value="<?php echo NEWSLETTER_MAIL_PAUSE_TIME; ?>" size="20" maxlength="3">
								</td>
							</tr>
						</table>
					</fieldset>
				</td>
				<td></td>
			</tr>
		</table>
	</fieldset>
</div>
