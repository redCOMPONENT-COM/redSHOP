<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;
$uri      = JURI::getInstance();
$url      = $uri->root();
$ord_path = "/components/com_redshop/assets/images/";
?>

<div class="row adminform">
    <div class="col-sm-6">
        <div class="box box-primary form-vertical">
            <div class="box-header with-border">
                <h3 class="text-primary center"><?php echo JText::_('COM_REDSHOP_NEWSLETTER') ?></h3>
            </div>
            <div class="box-body">
				<?php
				echo RedshopLayoutHelper::render(
					'config.config',
					array(
						'title' => JText::_('COM_REDSHOP_NEWSLETTER_ENABLE_TEXT'),
						'desc'  => JText::_('COM_REDSHOP_TOOLTIP_NEWSLETTER_ENABLE'),
						'field' => $this->lists['newsletter_enable']
					)
				);
				echo RedshopLayoutHelper::render(
					'config.config',
					array(
						'title'  => JText::_('COM_REDSHOP_NEWSLETTER_CONFIRMATION_LBL'),
						'desc'   => JText::_('COM_REDSHOP_TOOLTIP_NEWSLETTER_CONFIRMATION_LBL'),
						'id'     => 'newsletter_confirmation',
						'showOn' => 'newsletter_enable:1',
						'field'  => $this->lists['newsletter_confirmation']
					)
				);
				echo RedshopLayoutHelper::render(
					'config.config',
					array(
						'title'  => JText::_('COM_REDSHOP_NEWS_FROM_NAME'),
						'desc'   => JText::_('COM_REDSHOP_TOOLTIP_NEWS_FROM_NAME'),
						'id'     => 'news_from_name',
						'showOn' => 'newsletter_enable:1',
						'field'  => '<input type="text" name="news_from_name" id="news_from_name" class="form-control"
                            value="' . $this->config->get('NEWS_FROM_NAME') . '" size="50" />'
					)
				);
				echo RedshopLayoutHelper::render(
					'config.config',
					array(
						'title'  => JText::_('COM_REDSHOP_NEWS_MAIL_FROM'),
						'desc'   => JText::_('COM_REDSHOP_TOOLTIP_NEWS_MAIL_FROM'),
						'id'     => 'news_mail_from',
						'showOn' => 'newsletter_enable:1',
						'field'  => '<input type="text" name="news_mail_from" id="news_mail_from" class="form-control"
                            value="' . $this->config->get('NEWS_MAIL_FROM') . '" size="50" />'
					)
				);
				echo RedshopLayoutHelper::render(
					'config.config',
					array(
						'title'  => JText::_('COM_REDSHOP_DEFAULT_NEWSLETTER'),
						'desc'   => JText::_('COM_REDSHOP_TOOLTIP_DEFAULT_NEWSLETTER'),
						'id'     => 'newsletters',
						'showOn' => 'newsletter_enable:1',
						'field'  => $this->lists['newsletters']
					)
				);
				echo RedshopLayoutHelper::render(
					'config.config',
					array(
						'title'  => JText::_('COM_REDSHOP_NEWSLETTER_MAIL_BATCHES_SENT_AT_ONE_TIME_LBL'),
						'desc'   => JText::_('COM_REDSHOP_TOOLTIP_NEWSLETTER_MAIL_BATCHES_SENT_AT_ONE_TIME_LBL'),
						'id'     => 'newsletter_mail_chunk',
						'showOn' => 'newsletter_enable:1',
						'field'  => '<input type="number" name="newsletter_mail_chunk" id="newsletter_mail_chunk" class="form-control"
                            value="' . $this->config->get('NEWSLETTER_MAIL_CHUNK') . '" size="20" maxlength="3" />'
					)
				);
				echo RedshopLayoutHelper::render(
					'config.config',
					array(
						'title'  => JText::_('COM_REDSHOP_PAUSE_SECONDS_EVERY_AMOUNT_OF_EMAILS_LBL'),
						'desc'   => JText::_('COM_REDSHOP_TOOLTIP_PAUSE_SECONDS_EVERY_AMOUNT_OF_EMAILS_LBL'),
						'id'     => 'newsletter_mail_pause_time',
						'showOn' => 'newsletter_enable:1',
						'field'  => '<input type="number" name="newsletter_mail_pause_time" id="newsletter_mail_pause_time" class="form-control"
                            value="' . $this->config->get('NEWSLETTER_MAIL_PAUSE_TIME') . '" size="20" maxlength="3" />'
					)
				);
				?>
            </div>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="box box-primary form-vertical">
            <div class="box-header with-border">
                <h3 class="text-primary center"><?php echo JText::_('COM_REDSHOP_NEWSLETTER_TESTING_EMAIL_LBL') ?></h3>
            </div>
            <div class="box-body">
			    <?php
			    echo RedshopLayoutHelper::render(
				    'config.config',
				    array(
					    'title' => JText::_('COM_REDSHOP_NEWSLETTER_TESTING_EMAIL_LBL'),
					    'desc'  => JText::_('COM_REDSHOP_TOOLTIP_NEWSLETTER_TESTING_EMAIL_LBL'),
					    'field' => '<input type="text" name="newsletter_test_email" id="newsletter_test_email" class="form-control"
                            value="" size="50" />',
					    'line'  => false
				    )
			    );
			    echo RedshopLayoutHelper::render(
				    'config.config',
				    array(
					    'title' => JText::_('COM_REDSHOP_NEWSLETTER_TEST_LBL'),
					    'desc'  => JText::_('COM_REDSHOP_TOOLTIP_NEWSLETTER_TEST_LBL'),
					    'field' => '<button type="button" class="btn btn-success btn-large"
                            onclick="if(document.getElementById(\'newsletter_test_email\').value != \'\'){document.adminForm.task.value=\'apply\';form.submit();}else{alert(\'' . JText::_('COM_REDSHOP_PLEASE_ENTER_EMAIL_ADDRESS') . '\');}"><i class="fa fa-envelope"></i>&nbsp;&nbsp;' . JText::_('COM_REDSHOP_SEND') . '</button>',
					    'line'  => false
				    )
			    );
			    ?>
            </div>
        </div>
    </div>
</div>
