<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

JHtml::_('behavior.modal', '.joom-box');

?>
<div class="panel panel-primary form-vertical">
    <div class="panel-heading">
        <h3><?php echo JText::_('COM_REDSHOP_REGISTRATION') ?></h3>
    </div>
    <div class="panel-body">
        <div class="form-group">
            <span class="editlinktip hasTip"
                  title="<?php echo JText::_('COM_REDSHOP_REGISTER_METHOD_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_REGISTER_METHOD_LBL'); ?>">
                <label for="name"><?php echo JText::_('COM_REDSHOP_REGISTER_METHOD_LBL'); ?></label>
            </span>
			<?php echo $this->lists ['register_method']; ?>
        </div>
        <div class="form-group">
            <span class="editlinktip hasTip"
                  title="<?php echo JText::_('COM_REDSHOP_CREATE_ACCOUNT_CHECKBOX_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_CREATE_ACCOUNT_CHECKBOX'); ?>">
                <label for="name"><?php echo JText::_('COM_REDSHOP_CREATE_ACCOUNT_CHECKBOX_LBL'); ?></label>
            </span>
			<?php echo $this->lists ['create_account_checkbox']; ?>
        </div>
        <div class="form-group">
            <span class="editlinktip hasTip"
                  title="<?php echo JText::_('COM_REDSHOP_SHOW_REGISTER_CHECKOUT_CAPTCHA'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_SHOW_REGISTER_EMAIL_VERIFICATION'); ?>">
                <label for="name"><?php echo JText::_('COM_REDSHOP_SHOW_REGISTER_EMAIL_VERIFICATION'); ?></label>
            </span>
			<?php echo $this->lists ['show_email_verification']; ?>
        </div>
        <div class="form-group">
            <span class="editlinktip hasTip"
                  title="<?php echo JText::_('COM_REDSHOP_NEW_CUSTOMER_SELECTION_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_NEW_CUSTOMER_SELECTION_LBL'); ?>">
                <label for="new_customer_selection"><?php echo JText::_('COM_REDSHOP_NEW_CUSTOMER_SELECTION_LBL'); ?></label>
            </span>
			<?php echo $this->lists ['new_customer_selection']; ?>
        </div>
        <div class="form-group">
            <span class="editlinktip hasTip"
                  title="<?php echo JText::_('COM_REDSHOP_TERMS_AND_CONDITIONS_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_TERMS_AND_CONDITIONS_LBL'); ?>">
                <label for="showprice"><?php echo JText::_('COM_REDSHOP_TERMS_AND_CONDITIONS_LBL'); ?></label>
            </span>
			<?php

			$doc = JFactory::getDocument();

			$article    = JTable::getInstance('content');
			$article_id = $this->config->get('TERMS_ARTICLE_ID');
			if ($article_id)
			{
				$article->load($article_id);
			}
			else
			{
				$article->title = JText::_('COM_REDSHOP_SELECT_AN_ARTICLE');
			}

            $js = "
                function jSelectArticle_terms_article_id(id, title, catid) {
                    document.getElementById('terms_article_id_id').value = id;
                    document.getElementById('terms_article_id_name').value = title;
                    SqueezeBox.close();
                }";
			$doc->addScriptDeclaration($js);

			$link = 'index.php?option=com_content&view=articles&layout=modal&tmpl=component&function=jSelectArticle_terms_article_id';
			?>
			<div class="input-group">
                <input type="text" id="terms_article_id_name" class="form-control"
                       value="<?php echo htmlspecialchars($article->title, ENT_QUOTES, 'UTF-8') ?>" disabled="disabled" />
                <span class="input-group-btn">
                    <a class="joom-box btn btn-default" title="<?php JText::_('COM_CONTENT_SELECT_AN_ARTICLE') ?>"
				        href="<?php echo $link ?>" rel="{handler: 'iframe', size: {x: 650, y: 375}}"><?php echo JText::_('COM_REDSHOP_Select') ?></a>
                </span>
            </div>
            <input type="hidden" id="terms_article_id_id" name="terms_article_id" value="<?php echo $article_id ?>" />
        </div>
        <div class="form-group">
            <span class="editlinktip hasTip"
                  title="<?php echo JText::_('COM_REDSHOP_SHOW_TERMS_AND_CONDITIONS_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_SHOW_TERMS_AND_CONDITIONS_LBL'); ?>">
                <label for="showprice"><?php echo JText::_('COM_REDSHOP_SHOW_TERMS_AND_CONDITIONS_LBL'); ?></label>
            </span>
	        <?php echo $this->lists['show_terms_and_conditions']; ?>
            <input type="button" class="btn btn-small"
                   onclick="javascript:resetTermsCondition();" value="<?php echo JText::_('COM_REDSHOP_RESET_FOR_ALL_USER'); ?>"/>
        </div>

        <div class="form-group">
            <span class="editlinktip hasTip"
                  title="<?php echo JText::_('COM_REDSHOP_ALLOW_CUSTOMER_REGISTRATION_TYPE_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_ALLOW_CUSTOMER_REGISTRATION_TYPE_LBL'); ?>">
                <label
                        for="allow_customer_register_type"><?php echo JText::_('COM_REDSHOP_ALLOW_CUSTOMER_REGISTRATION_TYPE_LBL'); ?></label>
            </span>
			<?php echo $this->lists ['allow_customer_register_type']; ?>
        </div>
        <div class="form-group">
            <span class="editlinktip hasTip"
                  title="<?php echo JText::_('COM_REDSHOP_DEFAULT_CUSTOMER_REGISTRATION_TYPE_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_DEFAULT_CUSTOMER_REGISTRATION_TYPE_LBL'); ?>">
                <label
                        for="default_customer_register_type"><?php echo JText::_('COM_REDSHOP_DEFAULT_CUSTOMER_REGISTRATION_TYPE_LBL'); ?></label>
            </span>
			<?php echo $this->lists ['default_customer_register_type']; ?>
        </div>
        <div class="form-group">
            <span class="editlinktip hasTip"
                  title="<?php echo JText::_('COM_REDSHOP_CONFIG_CHECKOUT_LOGIN_REGISTER_SWITCHER_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_CONFIG_CHECKOUT_LOGIN_REGISTER_SWITCHER_DESC'); ?>">
                <label
                        for="checkout_login_register_switcher"><?php echo JText::_('COM_REDSHOP_CONFIG_CHECKOUT_LOGIN_REGISTER_SWITCHER_LBL'); ?></label>
            </span>
			<?php echo $this->lists['checkout_login_register_switcher']; ?>
        </div>

        <div class="form-group">
            <span class="editlinktip hasTip"
                  title="<?php echo JText::_('COM_REDSHOP_WELCOMEPAGE_INTROTEXT_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_WELCOMEPAGE_INTROTEXT_LBL'); ?>">
                <?php echo JText::_('COM_REDSHOP_WELCOMEPAGE_INTROTEXT_LBL'); ?>:
            </span>
            <textarea class="form-control" type="text" name="welcomepage_introtext" id="welcomepage_introtext" rows="4"
                      cols="40"/><?php echo stripslashes($this->config->get('WELCOMEPAGE_INTROTEXT')); ?></textarea>
        </div>

        <div class="form-group">
            <span class="editlinktip hasTip"
                  title="<?php echo JText::_('COM_REDSHOP_REGISTRATION_PAGE_INTRO_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_REGISTRATION_PAGE_INTRO_LBL'); ?>">
                <?php echo JText::_('COM_REDSHOP_REGISTRATION_PAGE_INTRO_LBL'); ?>:
            </span>
            <textarea class="form-control" type="text" name="registration_introtext" id="registration_introtext" rows="4"
                      cols="40"/><?php echo stripslashes($this->config->get('REGISTRATION_INTROTEXT')); ?></textarea>
        </div>

        <div class="form-group">
            <span class="editlinktip hasTip"
                  title="<?php echo JText::_('COM_REDSHOP_REGISTRATION_PAGE_COMP_INTRO_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_REGISTRATION_PAGE_COMP_INTRO_LBL'); ?>">
                <?php echo JText::_('COM_REDSHOP_REGISTRATION_PAGE_COMP_INTRO_LBL'); ?>:
            </span>
            <textarea class="form-control" type="text" name="registration_comp_introtext" id="registration_comp_introtext"
                      rows="4" cols="40"/><?php echo stripslashes($this->config->get('REGISTRATION_COMPANY_INTROTEXT')); ?></textarea>
        </div>
    </div>
</div>
