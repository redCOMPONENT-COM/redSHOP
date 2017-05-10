<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

$params = JRequest::getVar('params');
?>
<div>
	<form action="?option=com_redshop" method="POST" name="installform" id="installform">
		<table class="admintable table">
			<tr>
				<td colspan="2" class="tandc_intro_text">
					<?php echo JText::_('COM_REDSHOP_TERM_AND_CONDITION_INTRO_TEXT'); ?>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
			<span class="editlinktip hasTip"
			      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_TERMS_AND_CONDITIONS_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TERMS_AND_CONDITIONS_LBL'); ?>">
			<label for="showprice"><?php echo JText::_('COM_REDSHOP_TERMS_AND_CONDITIONS_LBL');?></label></span>
				</td>
				<td>
					<?php

					$doc = JFactory::getDocument();

					$article = JTable::getInstance('content');
					$article_id = $this->temparray['TERMS_ARTICLE_ID'];
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
					JHtml::_('behavior.modal', 'a.joom-box');
					$html = "\n" . '<div class="fltlft"><input type="text" id="terms_article_id_name" value="' . htmlspecialchars($article->title, ENT_QUOTES, 'UTF-8') . '" disabled="disabled" /></div>';
					$html .= '<div class="button2-left"><div class="blank"><a class="joom-box" title="' . JText::_('COM_CONTENT_SELECT_AN_ARTICLE') . '"  href="' . $link . '" 
rel="{handler: \'iframe\', size: {x: 650, y: 375}}">' . JText::_('COM_REDSHOP_Select') . '</a></div></div>' . "\n";
					$html .= "\n" . '<input type="hidden" id="terms_article_id_id" name="terms_article_id" value="' . $article_id . '" />';

					echo $html;
					?>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<label
						for="showprice"><?php echo JText::_('COM_REDSHOP_ADD_TERMS_AND_CONDITIONS_LBL');?></label></span>
				</td>
				<td>
					<a href="index.php?option=com_content&task=edit&cid[]=0" target="_blank" class="btn btn-small btn-info"
					   name="<?php echo JText::_('COM_REDSHOP_ADD_TERMS_AND_CONDITIONS_LBL'); ?>"><?php echo JText::_('COM_REDSHOP_ADD')?></a>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<input type="hidden" name="view" value="wizard"/>
					<input type="hidden" name="task" value="save"/>
					<input type="hidden" name="substep" value="<?php echo $params->step; ?>"/>
					<input type="hidden" name="go" value=""/>
				</td>
			</tr>
		</table>
	</form>
</div>
