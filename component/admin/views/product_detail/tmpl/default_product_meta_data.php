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
	<div class="col-sm-6">
		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title"><?php echo JText::_('COM_REDSHOP_META_DATA_TAB'); ?></h3>
			</div>
			<div class="box-body">
				<div class="form-group">
					<label for="append_to_global_seo">
						<?php echo JText::_('COM_REDSHOP_APPEND_TO_GLOBAL_SEO_LBL'); ?>
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
					</label>
					<?php echo $this->lists['append_to_global_seo']; ?>
				</div>

				<div class="form-group">
					<label for="pagetitle">
						<?php echo JText::_('COM_REDSHOP_PAGE_TITLE'); ?>
						<?php echo JHtml::tooltip(JText::_('COM_REDSHOP_TOOLTIP_PAGE_TITLE'), JText::_('COM_REDSHOP_PAGE_TITLE'), 'tooltip.png', '', '', false); ?>
					</label>
					<input class="form-control" type="text" name="pagetitle" id="pagetitle" size="75" value="<?php echo htmlspecialchars($this->detail->pagetitle); ?>"/>
				</div>

				<div class="form-group">
					<label for="pageheading">
						<?php echo JText::_('COM_REDSHOP_PAGE_HEADING'); ?>
						<?php echo JHtml::tooltip(JText::_('COM_REDSHOP_TOOLTIP_PAGE_HEADING'), JText::_('COM_REDSHOP_PAGE_HEADING'), 'tooltip.png', '', '', false); ?>
					</label>
					<input class="form-control" type="text" name="pageheading" id="pageheading" size="75" value="<?php echo $this->detail->pageheading; ?>"/>
				</div>

				<div class="form-group">
					<label for="sef_url">
						<?php echo JText::_('COM_REDSHOP_SEF_URL'); ?>
						<?php echo JHtml::tooltip(JText::_('COM_REDSHOP_TOOLTIP_SEF_URL'), JText::_('COM_REDSHOP_SEF_URL'), 'tooltip.png', '', '', false); ?>
					</label>
					<input class="form-control" type="text" name="sef_url" id="sef_url" size="75" value="<?php echo $this->detail->sef_url; ?>"/>
				</div>

				<div class="form-group">
					<label for="canonical_url">
						<?php echo JText::_('COM_REDSHOP_CANONICAL_URL_PRODUCT'); ?>
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
						<br />
						<span class="label label-important red">
							<?php echo JText::_('COM_REDSHOP_TOOLTIP_CANONICAL_URL_PRODUCT_PLUGIN'); ?>
						</span>
					</label>
					<input
						class="form-control"
						type="text"
						name="canonical_url"
						id="canonical_url"
						size="75"
						value="<?php echo isset($this->detail->canonical_url) ? $this->detail->canonical_url : ""; ?>"
					/>

				</div>

				<div class="form-group">
					<label for="cat_in_sefurl">
						<?php echo JText::_('COM_REDSHOP_SELECT_CATEGORY_TO_USEIN_SEF'); ?>
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
					</label>
					<?php echo $this->lists['cat_in_sefurl']; ?>
				</div>
			</div>
		</div>
	</div>

	<div class="col-sm-6">
		<div class="box box-primary">
			<div class="box-body">
				<div class="form-group">
					<label for="metakey">
						<?php echo JText::_('COM_REDSHOP_META_KEYWORDS'); ?>
						<?php echo JHtml::tooltip(JText::_('COM_REDSHOP_TOOLTIP_META_KEYWORDS'), JText::_('COM_REDSHOP_META_KEYWORDS'), 'tooltip.png', '', '', false); ?>
					</label>
					<textarea class="text_area" name="metakey" id="metakey" rows="4" cols="40"><?php echo $this->detail->metakey; ?></textarea>
				</div>

				<div class="form-group">
					<label for="metadesc">
						<?php echo JText::_('COM_REDSHOP_META_DESCRIPTION'); ?>
						<?php
						echo JHtml::tooltip(JText::_('COM_REDSHOP_TOOLTIP_META_DESCRIPTION'), JText::_('COM_REDSHOP_META_DESCRIPTION'), 'tooltip.png', '', '', false);
						?>
					</label>
					<textarea class="text_area" name="metadesc" id="metadesc" rows="4" cols="40"><?php echo htmlspecialchars($this->detail->metadesc); ?></textarea>
				</div>

				<div class="form-group">
					<label for="metalanguage_setting">
						<?php echo JText::_('COM_REDSHOP_META_LANG_SETTING'); ?>
						<?php
						echo JHtml::tooltip(JText::_('COM_REDSHOP_TOOLTIP_META_LANG_SETTING'), JText::_('COM_REDSHOP_META_LANG_SETTING'), 'tooltip.png', '', '', false);
						?>
					</label>
					<textarea class="text_area"
							  name="metalanguage_setting"
							  id="metalanguage_setting"
							  rows="4"
							  cols="40"><?php echo $this->detail->metalanguage_setting; ?></textarea>
				</div>

				<div class="form-group">
					<label for="metarobot_info">
						<?php echo JText::_('COM_REDSHOP_META_ROBOT_INFO'); ?>
						<?php
						echo JHtml::tooltip(JText::_('COM_REDSHOP_TOOLTIP_META_ROBOT_INFO'), JText::_('COM_REDSHOP_META_ROBOT_INFO'), 'tooltip.png', '', '', false);
						?>
					</label>
					<textarea class="text_area" name="metarobot_info" id="metarobot_info" rows="4" cols="40"><?php echo $this->detail->metarobot_info; ?></textarea>
				</div>
			</div>
		</div>
	</div>
</div>
