<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

?>

<div class="row">
	<div class="col-sm-6">
		<legend><?php echo JText::_('COM_REDSHOP_RATING_SETTING'); ?></legend>
		<div class="form-group">
			<span class="editlinktip hasTip"
								      title="<?php echo JText::_('COM_REDSHOP_RATING_DONE_MSG'); ?>::<?php echo JText::_('TOOLTIP_RATING_DONE_MSG'); ?>">
				<label for="name"><?php echo JText::_('COM_REDSHOP_RATING_DONE_MSG');?></label></span>
			<input type="text" name="rating_msg" id="rating_msg" value="<?php echo $this->config->get('RATING_MSG'); ?>"
										       size="50">
		</div>

		<div class="form-group">
			<span class="editlinktip hasTip"
								      title="<?php echo JText::_('COM_REDSHOP_FAVOURED_REVIEWS_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_FAVOURED_REVIEWS_LBL'); ?>">
								<label for="name"><?php echo JText::_('COM_REDSHOP_FAVOURED_REVIEWS_LBL');?></label>
			</span>
			<input type="text" name="favoured_reviews" id="favoured_reviews"
										       value="<?php echo $this->config->get('FAVOURED_REVIEWS'); ?>">
		</div>

		<div class="form-group">
			<span class="editlinktip hasTip"
								      title="<?php echo JText::_('COM_REDSHOP_RATING_REVIEW_LOGIN_REQUIRED_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_RATING_REVIEW_LOGIN_REQUIRED_LBL'); ?>">
								<label for="name"><?php echo JText::_('COM_REDSHOP_RATING_REVIEW_LOGIN_REQUIRED_LBL');?></label></span>
			<?php echo $this->lists['rating_review_login_required'];?>
		</div>
	</div>

	<div class="col-sm-6">

	</div>
</div>

