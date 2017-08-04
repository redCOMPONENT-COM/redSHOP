<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

?>

<div class="row">
	<div class="col-sm-12">
		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title"><?php echo JText::_('COM_REDSHOP_META_DATA_TAB'); ?></h3>
			</div>
			<div class="box-body">
				<table class="admintable table">
					<tr>
						<td align="right" class="key">
							<?php echo JText::_('COM_REDSHOP_PAGE_TITLE'); ?>:
						</td>
						<td>
							<input class="text_area" type="text" name="pagetitle" id="pagetitle" size="75" maxlength="250"
							       value="<?php echo $this->detail->pagetitle; ?>"/>
							<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_PAGE_TITLE'), JText::_('COM_REDSHOP_PAGE_TITLE'), 'tooltip.png', '', '', false); ?>
						</td>
					</tr>
					<tr>
						<td align="right" class="key">
							<?php echo JText::_('COM_REDSHOP_PAGE_HEADING'); ?>:
						</td>
						<td>
							<input class="text_area" type="text" name="pageheading" id="pageheading" size="75" maxlength="250"
							       value="<?php echo $this->detail->pageheading; ?>"/>
							<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_PAGE_HEADING'), JText::_('COM_REDSHOP_PAGE_HEADING'), 'tooltip.png', '', '', false); ?>
						</td>
					</tr>
					<tr>
						<td align="right" class="key">
							<?php echo JText::_('COM_REDSHOP_SEF_URL'); ?>:
						</td>
						<td>
							<input class="text_area" type="text" name="sef_url" id="sef_url" size="75" maxlength="250"
							       value="<?php echo $this->detail->sef_url; ?>"/>
							<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_SEF_URL'), JText::_('COM_REDSHOP_SEF_URL'), 'tooltip.png', '', '', false); ?>
						</td>
					</tr>
					<tr>
						<td valign="top" align="right" class="key">
							<?php echo JText::_('COM_REDSHOP_META_KEYWORDS'); ?>:
						</td>
						<td>
							<textarea class="text_area" type="text" name="metakey" id="metakey" rows="4"
							          cols="40"/><?php echo $this->detail->metakey; ?></textarea>
							<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_META_KEYWORDS'), JText::_('COM_REDSHOP_META_KEYWORDS'), 'tooltip.png', '', '', false); ?>

						</td>
					</tr>
					<tr>
						<td valign="top" align="right" class="key">
							<?php echo JText::_('COM_REDSHOP_META_DESCRIPTION'); ?>:
						</td>
						<td>
							<textarea class="text_area" type="text" name="metadesc" id="metadesc" rows="4"
							          cols="40"/><?php echo $this->detail->metadesc; ?></textarea>
							<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_META_DESCRIPTION'), JText::_('COM_REDSHOP_META_DESCRIPTION'), 'tooltip.png', '', '', false); ?>
						</td>
					</tr>
					<tr>
						<td valign="top" align="right" class="key">
							<?php echo JText::_('COM_REDSHOP_META_LANG_SETTING'); ?>:
						</td>
						<td>
							<textarea class="text_area" type="text" name="metalanguage_setting" id="metalanguage_setting" rows="4"
							          cols="40"/><?php echo $this->detail->metalanguage_setting; ?></textarea>
							<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_META_LANG_SETTING'), JText::_('COM_REDSHOP_META_LANG_SETTING'), 'tooltip.png', '', '', false); ?>
						</td>
					</tr>
					<tr>
						<td valign="top" align="right" class="key">
							<?php echo JText::_('COM_REDSHOP_META_ROBOT_INFO'); ?>:
						</td>
						<td>
							<textarea class="text_area" type="text" name="metarobot_info" id="metarobot_info" rows="4"
							          cols="40"/><?php echo $this->detail->metarobot_info; ?></textarea>
							<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_META_ROBOT_INFO'), JText::_('COM_REDSHOP_META_ROBOT_INFO'), 'tooltip.png', '', '', false); ?>
						</td>
					</tr>
				</table>
			</div>
		</div>
	</div>
</div>

