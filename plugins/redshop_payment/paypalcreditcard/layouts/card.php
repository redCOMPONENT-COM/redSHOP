<?php
/**
 * @package     Redshop.Layouts
 * @subpackage  Payment.PaypalCreditCard
 * @copyright   Copyright (C) 2008-2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU/GPL, see LICENSE
 */

defined('_JEXEC') or die;

extract($displayData);

?>
<tr id="card-<?php echo $card->id; ?>">
	<?php if ($selectable) : ?>
		<td>
			<input type="radio" name="selectedCard" value="<?php echo $card->id; ?>">
		</td>
	<?php endif; ?>
	<td>
		<?php
		$name = $card->first_name . ' ' . $card->last_name;
		echo $name;
		?>
	</td>
	<td><?php echo $card->type; ?></td>
	<td><?php echo $card->number; ?></td>
	<td><?php echo $card->expire_month; ?></td>
	<td><?php echo $card->expire_year; ?></td>
	<td>
		<a href="javascript:;" class="edit" cardId="<?php echo $card->id; ?>">
			<i class="icon-pencil"></i>
		</a>
		<a href="javascript:;" class="delete" id="delete-<?php echo $card->id; ?>">
			<i class="icon-trash"></i>
		</a>
	<td>
</tr>
<tr id="card-edit-<?php echo $card->id; ?>">
	<td colspan="6">
		<?php
		echo RedshopLayoutHelper::render(
			'form',
			array(
				'id'              => $card->id,
				'name'            => $name,
				'type'            => $card->type,
				'number'          => $card->number,
				'expireMonth'     => $card->expire_month,
				'expireYear'      => $card->expire_year,
				'creditCardTypes' => $creditCardTypes
			),
			__DIR__
		);
		?>
	</td>
</tr>
