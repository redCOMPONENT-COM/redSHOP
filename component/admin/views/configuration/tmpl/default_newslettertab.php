<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;
$uri = JURI::getInstance();
$url = $uri->root();
$ord_path = "/components/com_redshop/assets/images/";

?>

<fieldset class="adminform">
	<div class="row">
		<div class="col-sm-4">
			<fieldset class="adminform">
				<legend><?php echo JText::_('COM_REDSHOP_NEWSLETTER'); ?></legend>

				<div class="form-group">
					<span class="editlinktip hasTip"
							  title="<?php echo JText::_('COM_REDSHOP_NEWSLETTER_ENABLE_TEXT'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_NEWSLETTER_ENABLE'); ?>">
					<label
						for="newsletter_enable"><?php  echo JText::_('COM_REDSHOP_NEWSLETTER_ENABLE_TEXT');?>
					</label>
					</span>
					<?php echo $this->lists ['newsletter_enable'];?>
				</div>

				<div class="form-group">
					<span class="editlinktip hasTip"
						  title="<?php echo JText::_('COM_REDSHOP_NEWSLETTER_CONFIRMATION_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_NEWSLETTER_CONFIRMATION_LBL'); ?>">
						<label for="newsletter_enable">
							<?php
							echo JText::_('COM_REDSHOP_NEWSLETTER_CONFIRMATION_LBL');
							?>
						</label>
					</span>
					<?php echo $this->lists ['newsletter_confirmation']; ?>
				</div>

				<div class="form-group">
					<span class="editlinktip hasTip"
						  title="<?php echo JText::_('COM_REDSHOP_NEWS_FROM_NAME'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_NEWS_FROM_NAME'); ?>">
						<label for="name">
							<?php
							echo JText::_('COM_REDSHOP_NEWS_FROM_NAME');
							?>
						</label>
					</span>
					<input type="text" name="news_from_name" id="news_from_name"
						value="<?php
						echo $this->config->get('NEWS_FROM_NAME');
						?>"
						size="50">
				</div>

				<div class="form-group">
					<span class="editlinktip hasTip"
							  title="<?php echo JText::_('COM_REDSHOP_NEWS_MAIL_FROM'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_NEWS_MAIL_FROM'); ?>">
						<label for="name">
							<?php
							echo JText::_('COM_REDSHOP_NEWS_MAIL_FROM');
							?>
						</label>
					</span>
					<input type="text" name="news_mail_from" id="news_mail_from"
					   value="<?php
					   echo $this->config->get('NEWS_MAIL_FROM');
					   ?>"
					   size="50">
				</div>

				<div class="form-group">
					<span class="editlinktip hasTip"
							  title="<?php echo JText::_('COM_REDSHOP_DEFAULT_NEWSLETTER'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_DEFAULT_NEWSLETTER'); ?>">
						<label for="name">
							<?php
							echo JText::_('COM_REDSHOP_DEFAULT_NEWSLETTER');
							?>
						</label>
					</span>
					<?php echo $this->lists ['newsletters']; ?>
				</div>

				<div class="form-group">
					<span class="editlinktip hasTip"
				  title="<?php echo JText::_('COM_REDSHOP_NEWSLETTER_TESTING_EMAIL_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_NEWSLETTER_TESTING_EMAIL_LBL'); ?>">
						<label for="name"><?php echo JText::_('COM_REDSHOP_NEWSLETTER_TESTING_EMAIL_LBL');?></label>
					</span>
					<input type="text" name="newsletter_test_email" id="newsletter_test_email" value="" size="50">
				</div>

				<div class="form-group">
					<span class="editlinktip hasTip"
						  title="<?php echo JText::_('COM_REDSHOP_NEWSLETTER_TEST_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_NEWSLETTER_TEST_LBL'); ?>">
						<label for="name">
							<?php
							echo JText::_('COM_REDSHOP_NEWSLETTER_TEST_LBL');
							?>
						</label>
					</span>
					<input type="button" class="btn"
						   onclick="if(document.getElementById('newsletter_test_email').value != ''){document.adminForm.task.value='apply';form.submit();}else{alert('<?php echo JText::_('COM_REDSHOP_PLEASE_ENTER_EMAIL_ADDRESS'); ?>');}"
						   value="<?php
						   echo JText::_('COM_REDSHOP_SEND');
						   ?>"/>
				</div>

				<div class="form-group">
					<span class="editlinktip hasTip" title="<?php echo JText::_('COM_REDSHOP_NEWSLETTER_MAIL_BATCHES_SENT_AT_ONE_TIME_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_NEWSLETTER_MAIL_BATCHES_SENT_AT_ONE_TIME_LBL'); ?>">
						<label
							for="name"><?php echo JText::_('COM_REDSHOP_NEWSLETTER_MAIL_BATCHES_SENT_AT_ONE_TIME_LBL');?></label>
					</span>
					<input type="text" name="newsletter_mail_chunk" id="newsletter_mail_chunk"
										   value="<?php echo $this->config->get('NEWSLETTER_MAIL_CHUNK'); ?>" size="20" maxlength="3">
				</div>

				<div class="form-group">
					<span class="editlinktip hasTip"
				  title="<?php echo JText::_('COM_REDSHOP_PAUSE_SECONDS_EVERY_AMOUNT_OF_EMAILS_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_PAUSE_SECONDS_EVERY_AMOUNT_OF_EMAILS_LBL'); ?>">
						<label for="name"><?php echo JText::_('COM_REDSHOP_PAUSE_SECONDS_EVERY_AMOUNT_OF_EMAILS_LBL');?></label>
					</span>
					<input type="text" name="newsletter_mail_pause_time" id="newsletter_mail_pause_time"
										   value="<?php echo $this->config->get('NEWSLETTER_MAIL_PAUSE_TIME'); ?>" size="20" maxlength="3">
				</div>

			</fieldset>
		</div>
	</div>
</fieldset>
