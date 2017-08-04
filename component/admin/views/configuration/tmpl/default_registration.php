<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

JHtml::_('behavior.modal', '.joom-box');

echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_REGISTER_METHOD_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_REGISTER_METHOD_LBL'),
		'field' => $this->lists['register_method']
	)
);

echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_CREATE_ACCOUNT_CHECKBOX_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_CREATE_ACCOUNT_CHECKBOX'),
		'field' => $this->lists['create_account_checkbox']
	)
);

echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_SHOW_REGISTER_EMAIL_VERIFICATION'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_SHOW_REGISTER_EMAIL_VERIFICATION'),
		'field' => $this->lists['show_email_verification']
	)
);

echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_NEW_CUSTOMER_SELECTION_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_NEW_CUSTOMER_SELECTION_LBL'),
		'field' => $this->lists['new_customer_selection']
	)
);

$doc        = JFactory::getDocument();
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

$js = "function jSelectArticle_terms_article_id(id, title, catid) {
    document.getElementById('terms_article_id_id').value = id;
    document.getElementById('terms_article_id_name').value = title;
    SqueezeBox.close();
}";
$doc->addScriptDeclaration($js);
$link = 'index.php?option=com_content&view=articles&layout=modal&tmpl=component&function=jSelectArticle_terms_article_id';
$html = '<div class="input-group">'
	. '<input type="text" id="terms_article_id_name" class="form-control"'
	. ' value="' . htmlspecialchars($article->title, ENT_QUOTES, 'UTF-8') . '" disabled="disabled"/>'
	. '<span class="input-group-btn">'
	. '<a class="joom-box btn btn-default" title="' . JText::_('COM_CONTENT_SELECT_AN_ARTICLE') . '"'
	. 'href="' . $link . '" rel="{handler: \'iframe\', size: {x: 650, y: 375}}">' . JText::_('COM_REDSHOP_Select') . '</a>'
	. '</span></div><input type="hidden" id="terms_article_id_id" name="terms_article_id" value="' . $article_id . '"/>';

echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_TERMS_AND_CONDITIONS_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_TERMS_AND_CONDITIONS_LBL'),
		'field' => $html
	)
);

echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_SHOW_TERMS_AND_CONDITIONS_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_SHOW_TERMS_AND_CONDITIONS_LBL'),
		'field' => $this->lists['show_terms_and_conditions'] . '<input type="button" class="btn pull-right btn-warning"
                   onclick="javascript:resetTermsCondition();" value="' . JText::_('COM_REDSHOP_RESET_FOR_ALL_USER') . '"/>'
	)
);

echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_ALLOW_CUSTOMER_REGISTRATION_TYPE_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_ALLOW_CUSTOMER_REGISTRATION_TYPE_LBL'),
		'field' => $this->lists['allow_customer_register_type']
	)
);

echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_DEFAULT_CUSTOMER_REGISTRATION_TYPE_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_DEFAULT_CUSTOMER_REGISTRATION_TYPE_LBL'),
		'field' => $this->lists['default_customer_register_type']
	)
);

echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_CONFIG_CHECKOUT_LOGIN_REGISTER_SWITCHER_LBL'),
		'desc'  => JText::_('COM_REDSHOP_CONFIG_CHECKOUT_LOGIN_REGISTER_SWITCHER_DESC'),
		'field' => $this->lists['checkout_login_register_switcher']
	)
);

echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_WELCOMEPAGE_INTROTEXT_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_WELCOMEPAGE_INTROTEXT_LBL'),
		'field' => '<textarea class="form-control" type="text" name="welcomepage_introtext" id="welcomepage_introtext" rows="4"
                      cols="40"/>' . stripslashes($this->config->get('WELCOMEPAGE_INTROTEXT')) . '</textarea>'
	)
);

echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_REGISTRATION_PAGE_INTRO_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_REGISTRATION_PAGE_INTRO_LBL'),
		'field' => '<textarea class="form-control" type="text" name="registration_introtext" id="registration_introtext" rows="4"
                      cols="40"/>' . stripslashes($this->config->get('REGISTRATION_INTROTEXT')) . '</textarea>'
	)
);

echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_REGISTRATION_PAGE_COMP_INTRO_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_REGISTRATION_PAGE_COMP_INTRO_LBL'),
		'field' => '<textarea class="form-control" type="text" name="registration_comp_introtext" id="registration_comp_introtext" rows="4"
                      cols="40"/>' . stripslashes($this->config->get('REGISTRATION_COMPANY_INTROTEXT')) . '</textarea>',
		'line'  => false
	)
);
