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

$params = JRequest::getVar('params');
?>
<div>
<form action="?option=com_redshop" method="POST" name="installform" id="installform">
<table class="admintable">
	<tr>
		<td colspan="2" class="tandc_intro_text">
			<?php echo JText::_('TERM_AND_CONDITION_INTRO_TEXT'); ?>
		</td>
	</tr>
	<tr>
		<td align="right" class="key">
			<span class="editlinktip hasTip" title="<?php echo JText::_( 'TOOLTIP_TERMS_AND_CONDITIONS_LBL' ); ?>::<?php echo JText::_( 'TERMS_AND_CONDITIONS_LBL' ); ?>">
			<label for="showprice"><?php echo JText::_ ( 'TERMS_AND_CONDITIONS_LBL' );?></label></span>
		</td>
		<td>
			<?php

			$doc 		=& JFactory::getDocument();

			$article =& JTable::getInstance('content');
			$article_id = $this->temparray['terms_article_id'];
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
		$html .= '<div class="button2-left"><div class="blank"><a class="modal" title="'.JText::_('Select an Article').'"  href="'.$link.'" rel="{handler: \'iframe\', size: {x: 900, y: 500}}">'.JText::_('Select').'</a></div></div>'."\n";
		$html .= "\n".'<input type="hidden" id="terms_article_id_id" name="terms_article_id" value="'.$article_id.'" />';

		echo $html;
			?>
		</td>
	</tr>
	<tr>
		<td align="right" class="key">
			<label for="showprice"><?php echo JText::_ ( 'ADD_TERMS_AND_CONDITIONS_LBL' );?></label></span>
		</td>
		<td>
			<a href="index.php?option=com_content&task=edit&cid[]=0" target="_blank" name="<?php echo JText::_ ( 'ADD_TERMS_AND_CONDITIONS_LBL' );?>"><?php echo JText::_('ADD')?></a>
		</td>
	</tr>
	<tr>
		<td colspan="2">
		<input type="hidden" name="view" value="wizard" />
		<input type="hidden" name="task" value="save" />
		<input type="hidden" name="substep" value="<?php echo $params->step;?>"/>
		<input type="hidden" name="go" value=""/>
		</td>
	</tr>
</table>
</form>
</div>