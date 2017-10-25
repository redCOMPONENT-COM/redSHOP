<?php
/**
 * @package     Redshop.Layouts
 * @subpackage  Payment.PaypalCreditCard
 * @copyright   Copyright (C) 2008-2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU/GPL, see LICENSE
 */

defined('_JEXEC') or die;

extract($displayData);

JHtml::_('formbehavior.chosen', 'select');

?>
<div class="credit-card-form">
	<div class="control-group">
		<label class="control-label" for="cardName">Name</label>
		<div class="controls">
			<input
					class="input-medium"
					type="text"
					placeholder="Name"
					name="cardName"
					id="cardName"
					value="<?php echo $name; ?>"
			/>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="cardType">Type</label>
		<div class="controls">
			<?php if ($id) : ?>
				<?php echo $type; ?>
			<?php else : ?>
				<?php
				echo JHtml::_('select.genericlist', $creditCardTypes, 'cardType', array('class' => 'input-small'), 'value', 'text', $type);
				?>
			<?php endif; ?>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="cardNumber">Number</label>
		<div class="controls">
			<input
					class="input-medium"
					type="text"
					placeholder="Number"
					name="cardNumber"
					id="cardNumber"
					value="<?php echo $number; ?>"
			/>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="cardExpireMonth">Month/Year</label>
		<div class="controls">
			<input
					class="input-mini"
					type="text"
					placeholder="Month"
					name="cardExpireMonth"
					id="cardExpireMonth"
					maxlength="2"
					value="<?php echo $expireMonth; ?>"
			/>
			<input
					class="input-mini"
					type="text"
					placeholder="Year"
					maxlength="4"
					name="cardExpireYear"
					value="<?php echo $expireYear; ?>"
			/>
		</div>
	</div>
	<?php if (!$id) : ?>
		<div class="control-group">
			<label class="control-label" for="cardCvv">Cvv2</label>
			<div class="controls">
				<input
						class="input-mini"
						type="text"
						placeholder="Cvv2"
						maxlength="4"
						name="cardCvv"
						id="cardCvv"
						value=""
				/>
			</div>
		</div>
	<?php endif; ?>
	<div class="control-group">
		<div class="controls">
			<a href="javascript:;" class="btn btn-success" id="save-<?php echo $id; ?>">
				<i class="icon-ok"></i>Save
			</a>
			<a href="javascript:;" class="btn cancel" cardId="<?php echo $id; ?>">
				<i class="icon-remove"></i>Cancel
			</a>
		</div>
	</div>
</div>
