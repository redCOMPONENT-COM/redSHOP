<?php
/**
 * @copyright Copyright (C) 2010 redCOMPONENT.com. All rights reserved.
 * @license GNU/GPL, see license.txt or http://www.gnu.org/copyleft/gpl.html
 * Developed by email@recomponent.com - redCOMPONENT.com
 *
 * redSHOP can be downloaded from www.redcomponent.com
 * redSHOP is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License 2
 * as published by the Free Software Foundation.
 *
 * You should have received a copy of the GNU General Public License
 * along with redSHOP; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );

?>
<table class="admintable" width="100%">
<tr><td class="config_param"><?php echo JText::_( 'REGISTRATION' ); ?></td></tr>
	<tr>
		<td width="100" align="right" class="key">
			<span class="editlinktip hasTip" title="<?php echo JText::_( 'REGISTER_METHOD_LBL' ); ?>::<?php echo JText::_( 'TOOLTIP_REGISTER_METHOD_LBL' ); ?>">
			<label for="name"><?php echo JText::_ ( 'REGISTER_METHOD_LBL' );?></label>
		</td>
		<td>
			<?php echo $this->lists ['register_method']; ?>
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
			<span class="editlinktip hasTip" title="<?php echo JText::_( 'CREATE_ACCOUNT_CHECKBOX_LBL' ); ?>::<?php echo JText::_( 'TOOLTIP_CREATE_ACCOUNT_CHECKBOX' ); ?>">
			<label for="name"><?php echo JText::_ ( 'CREATE_ACCOUNT_CHECKBOX_LBL' );?></label>
		</td>
		<td>
			<?php echo $this->lists ['create_account_checkbox']; ?>
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
			<span class="editlinktip hasTip" title="<?php echo JText::_( 'SHOW_REGISTER_CHECKOUT_CAPTCHA' ); ?>::<?php echo JText::_( 'TOOLTIP_SHOW_REGISTER_CHECKOUT_CAPTCHA' ); ?>">
			<label for="name"><?php echo JText::_ ( 'SHOW_REGISTER_CHECKOUT_CAPTCHA' );?></label>
		</td>
		<td>
			<?php echo $this->lists ['show_captcha']; ?>
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
			<span class="editlinktip hasTip" title="<?php echo JText::_( 'SHOW_REGISTER_CHECKOUT_CAPTCHA' ); ?>::<?php echo JText::_( 'TOOLTIP_SHOW_REGISTER_EMAIL_VERIFICATION' ); ?>">
			<label for="name"><?php echo JText::_ ( 'SHOW_REGISTER_EMAIL_VERIFICATION' );?></label>
		</td>
		<td>
			<?php echo $this->lists ['show_email_verification']; ?>
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip" title="<?php echo JText::_( 'NEW_CUSTOMER_SELECTION_LBL' ); ?>::<?php echo JText::_( 'TOOLTIP_NEW_CUSTOMER_SELECTION_LBL' ); ?>">
		<label for="new_customer_selection"><?php echo JText::_ ( 'NEW_CUSTOMER_SELECTION_LBL' );?></label>
		</td>
		<td><?php echo $this->lists ['new_customer_selection'];?></td>
	</tr>
	<tr><td colspan="2"><hr /></td></tr>

	<tr>
		<td width="100" align="right" class="key">
			<span class="editlinktip hasTip" title="<?php echo JText::_( 'TERMS_AND_CONDITIONS_LBL' ); ?>::<?php echo JText::_( 'TOOLTIP_TERMS_AND_CONDITIONS_LBL' ); ?>">
			<label for="showprice"><?php echo JText::_ ( 'TERMS_AND_CONDITIONS_LBL' );?></label></span>
		</td>
		<td>
			<?php

			$doc 		=& JFactory::getDocument();

			$article =& JTable::getInstance('content');
			$article_id = TERMS_ARTICLE_ID;
			if ($article_id) {
				$article->load($article_id);
			} else {
				$article->title = JText::_('Select an Article');
			}

			$js = "
		function jSelectArticle(id, title, object) {
			document.getElementById(object + '_id').value = id;
			document.getElementById(object + '_name').value = title;
			document.getElementById('sbox-window').close();
		}";
		$doc->addScriptDeclaration($js);

		$link = 'index.php?option=com_content&amp;task=element&amp;tmpl=component&amp;object=terms_article_id';

		JHTML::_('behavior.modal', 'a.modal');
		$html = "\n".'<div style="float: left;"><input style="background: #ffffff;" type="text" id="terms_article_id_name" value="'.htmlspecialchars($article->title, ENT_QUOTES, 'UTF-8').'" size="40" disabled="disabled" /></div>';
		$html .= '<div class="button2-left"><div class="blank"><a class="modal" title="'.JText::_('SELECT_AN_ARTICLE').'"  href="'.$link.'" rel="{handler: \'iframe\', size: {x: 900, y: 500}}">'.JText::_('SELECT').'</a></div></div>'."\n";
		$html .= "\n".'<input type="hidden" id="terms_article_id_id" name="terms_article_id" value="'.$article_id.'" />';

		echo $html;
			?>
		</td>
	</tr>
	<tr><td width="100" align="right" class="key">
			<span class="editlinktip hasTip" title="<?php echo JText::_( 'SHOW_TERMS_AND_CONDITIONS_LBL' ); ?>::<?php echo JText::_( 'TOOLTIP_SHOW_TERMS_AND_CONDITIONS_LBL' ); ?>">
			<label for="showprice"><?php echo JText::_ ( 'SHOW_TERMS_AND_CONDITIONS_LBL' );?></label></span>
		</td>
		<td><?php echo $this->lists ['show_terms_and_conditions'];?><input type="button" onclick="javascript:resetTermsCondition();" value="<?php echo JText::_('RESET_FOR_ALL_USER');?>" /></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip" title="<?php echo JText::_( 'ALLOW_CUSTOMER_REGISTRATION_TYPE_LBL' ); ?>::<?php echo JText::_( 'TOOLTIP_ALLOW_CUSTOMER_REGISTRATION_TYPE_LBL' ); ?>">
		<label for="allow_customer_register_type"><?php echo JText::_ ( 'ALLOW_CUSTOMER_REGISTRATION_TYPE_LBL' );?></label>
		</td>
		<td><?php echo $this->lists ['allow_customer_register_type'];?></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip" title="<?php echo JText::_( 'DEFAULT_CUSTOMER_REGISTRATION_TYPE_LBL' ); ?>::<?php echo JText::_( 'TOOLTIP_DEFAULT_CUSTOMER_REGISTRATION_TYPE_LBL' ); ?>">
		<label for="default_customer_register_type"><?php echo JText::_ ( 'DEFAULT_CUSTOMER_REGISTRATION_TYPE_LBL' );?></label>
		</td>
		<td><?php echo $this->lists ['default_customer_register_type'];?></td>
	</tr>
	<tr><td colspan="2"><hr /></td></tr>
	<tr>
		<td align="right" class="key">
		<span class="editlinktip hasTip" title="<?php echo JText::_( 'WELCOMEPAGE_INTROTEXT_LBL' ); ?>::<?php echo JText::_( 'TOOLTIP_WELCOMEPAGE_INTROTEXT_LBL' ); ?>">
		<?php echo JText::_ ( 'WELCOMEPAGE_INTROTEXT_LBL' );?>:
		</td>
		<td>
		<textarea class="text_area" type="text" name="welcomepage_introtext" id="welcomepage_introtext" rows="4" cols="40" /><?php echo stripslashes(WELCOMEPAGE_INTROTEXT); ?></textarea>
		</td>
	</tr>
	<tr>
		<td align="right" class="key">
		<span class="editlinktip hasTip" title="<?php echo JText::_( 'REGISTRATION_PAGE_INTRO_LBL' ); ?>::<?php echo JText::_( 'TOOLTIP_REGISTRATION_PAGE_INTRO_LBL' ); ?>">
		<?php echo JText::_ ( 'REGISTRATION_PAGE_INTRO_LBL' );?>:
		</td>
		<td>
		<textarea class="text_area" type="text" name="registration_introtext" id="registration_introtext" rows="4" cols="40" /><?php echo stripslashes(REGISTRATION_INTROTEXT);?></textarea>
		</td>
	</tr>
	<tr>
		<td align="right" class="key">
		<span class="editlinktip hasTip" title="<?php echo JText::_( 'REGISTRATION_PAGE_COMP_INTRO_LBL' ); ?>::<?php echo JText::_( 'TOOLTIP_REGISTRATION_PAGE_COMP_INTRO_LBL' ); ?>">
		<?php echo JText::_ ( 'REGISTRATION_PAGE_COMP_INTRO_LBL' );?>:
		</td>
		<td>
		<textarea class="text_area" type="text" name="registration_comp_introtext" id="registration_comp_introtext" rows="4" cols="40" /><?php echo stripslashes(REGISTRATION_COMPANY_INTROTEXT);	?></textarea>
		</td>
	</tr>
</table>