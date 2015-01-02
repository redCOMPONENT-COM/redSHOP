<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;
?>

<table class="admintable">

	<tr>
		<td class="key">
			<label for="append_to_global_seo">
				<?php echo JText::_('COM_REDSHOP_APPEND_TO_GLOBAL_SEO_LBL'); ?>
			</label>
		</td>
		<td>
			<?php echo $this->lists['append_to_global_seo']; ?>
		</td>
		<td>
			<?php
			echo JHtml::tooltip(
				JText::_('COM_REDSHOP_TOOLTIP_APPEND_TO_GLOBAL_SEO_LBL'),
				JText::_('COM_REDSHOP_APPEND_TO_GLOBAL_SEO_LBL'),
				'tooltip.png',
				'',
				'',
				false
			);
			?>
		</td>
	</tr>

	<tr>
		<td class="key">
			<label for="pagetitle">
				<?php echo JText::_('COM_REDSHOP_PAGE_TITLE'); ?>
			</label>
		</td>
		<td>
			<input class="text_area" type="text" name="pagetitle" id="pagetitle" size="75" value="<?php echo htmlspecialchars($this->detail->pagetitle); ?>"/>
		</td>
		<td>
			<?php echo JHtml::tooltip(JText::_('COM_REDSHOP_TOOLTIP_PAGE_TITLE'), JText::_('COM_REDSHOP_PAGE_TITLE'), 'tooltip.png', '', '', false); ?>
		</td>
	</tr>

	<tr>
		<td class="key">
			<label for="pageheading">
				<?php echo JText::_('COM_REDSHOP_PAGE_HEADING'); ?>
			</label>
		</td>
		<td>
			<input class="text_area" type="text" name="pageheading" id="pageheading" size="75" value="<?php echo $this->detail->pageheading; ?>"/>
		</td>
		<td>
			<?php echo JHtml::tooltip(JText::_('COM_REDSHOP_TOOLTIP_PAGE_HEADING'), JText::_('COM_REDSHOP_PAGE_HEADING'), 'tooltip.png', '', '', false); ?>
		</td>
	</tr>

	<tr>
		<td class="key">
			<label for="sef_url">
				<?php echo JText::_('COM_REDSHOP_SEF_URL'); ?>
			</label>
		</td>
		<td>
			<input class="text_area" type="text" name="sef_url" id="sef_url" size="75" value="<?php echo $this->detail->sef_url; ?>"/>
		</td>
		<td>
			<?php echo JHtml::tooltip(JText::_('COM_REDSHOP_TOOLTIP_SEF_URL'), JText::_('COM_REDSHOP_SEF_URL'), 'tooltip.png', '', '', false); ?>
		</td>
	</tr>

	<tr>
		<td class="key">
			<label for="canonical_url">
				<?php echo JText::_('COM_REDSHOP_CANONICAL_URL_PRODUCT'); ?>
			</label>
		</td>
		<td>
			<input
				class="text_area"
				type="text"
				name="canonical_url"
				id="canonical_url"
				size="75"
				value="<?php echo isset($this->detail->canonical_url) ? $this->detail->canonical_url : ""; ?>"
			/><br />
			<span class="label label-important red">
				<?php echo JText::_('COM_REDSHOP_TOOLTIP_CANONICAL_URL_PRODUCT_PLUGIN'); ?>
			</span>
		</td>
		<td>
			<?php
			echo JHtml::tooltip(
				JText::_('COM_REDSHOP_TOOLTIP_CANONICAL_URL_PRODUCT'),
				JText::_('COM_REDSHOP_CANONICAL_URL_PRODUCT'),
				'tooltip.png',
				'',
				'',
				false
			);
			?>
		</td>
	</tr>

	<tr>
		<td class="key">
			<label for="cat_in_sefurl">
				<?php echo JText::_('COM_REDSHOP_SELECT_CATEGORY_TO_USEIN_SEF'); ?>
			</label>
		</td>
		<td>
			<?php echo $this->lists['cat_in_sefurl']; ?>
		</td>
		<td>
			<?php
			echo JHtml::tooltip(
				JText::_('COM_REDSHOP_TOOLTIP_SELECT_CATEGORY_TO_USEIN_SEF'),
				JText::_('COM_REDSHOP_SELECT_CATEGORY_TO_USEIN_SEF'),
				'tooltip.png',
				'',
				'',
				false
			);
			?>
		</td>
	</tr>

	<tr>
		<td colspan="2">
			<hr/>
		</td>
	</tr>

	<tr>
		<td class="key">
			<label for="metakey">
				<?php echo JText::_('COM_REDSHOP_META_KEYWORDS'); ?>
			</label>
		</td>
		<td>
			<textarea class="text_area" name="metakey" id="metakey" rows="4" cols="40"><?php echo $this->detail->metakey; ?></textarea>
		</td>
		<td>
			<?php echo JHtml::tooltip(JText::_('COM_REDSHOP_TOOLTIP_META_KEYWORDS'), JText::_('COM_REDSHOP_META_KEYWORDS'), 'tooltip.png', '', '', false); ?>
		</td>
	</tr>

	<tr>
		<td class="key">
			<label for="metadesc">
				<?php echo JText::_('COM_REDSHOP_META_DESCRIPTION'); ?>
			</label>
		</td>
		<td>
			<textarea class="text_area" name="metadesc" id="metadesc" rows="4" cols="40"><?php echo htmlspecialchars($this->detail->metadesc); ?></textarea>
		</td>
		<td>
			<?php
			echo JHtml::tooltip(JText::_('COM_REDSHOP_TOOLTIP_META_DESCRIPTION'), JText::_('COM_REDSHOP_META_DESCRIPTION'), 'tooltip.png', '', '', false);
			?>
		</td>
	</tr>

	<tr>
		<td class="key">
			<label for="metalanguage_setting">
				<?php echo JText::_('COM_REDSHOP_META_LANG_SETTING'); ?>
			</label>
		</td>
		<td>
			<textarea class="text_area"
					  name="metalanguage_setting"
					  id="metalanguage_setting"
					  rows="4"
					  cols="40"><?php echo $this->detail->metalanguage_setting; ?></textarea>
		</td>
		<td>
			<?php
			echo JHtml::tooltip(JText::_('COM_REDSHOP_TOOLTIP_META_LANG_SETTING'), JText::_('COM_REDSHOP_META_LANG_SETTING'), 'tooltip.png', '', '', false);
			?>
		</td>
	</tr>

	<tr>
		<td class="key">
			<label for="metarobot_info">
				<?php echo JText::_('COM_REDSHOP_META_ROBOT_INFO'); ?>
			</label>
		</td>
		<td>
			<textarea class="text_area" name="metarobot_info" id="metarobot_info" rows="4" cols="40"><?php echo $this->detail->metarobot_info; ?></textarea>
		</td>
		<td>
			<?php
			echo JHtml::tooltip(JText::_('COM_REDSHOP_TOOLTIP_META_ROBOT_INFO'), JText::_('COM_REDSHOP_META_ROBOT_INFO'), 'tooltip.png', '', '', false);
			?>
		</td>
	</tr>

</table>
