<?php
/**
 * RedshopLayout to display payment extra fields
 *
 * @package     Redshop.Layouts
 * @subpackage  Order.Payment
 * @copyright   Copyright (C) 2008-2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU/GPL, see LICENSE
 */

defined('_JEXEC') or die;

extract($displayData);

$fields = $plugin->params->get('extrafield_payment', array());
?>
<?php if (count($fields) > 0) : ?>
    <div id="extraFields_<?php echo $plugin->name; ?>">
		<?php foreach ($fields as $name) : ?>
			<?php
			$fieldInput = extraField::getInstance()->list_all_user_fields(
				$name,
				extraField::SECTION_PAYMENT_GATEWAY,
				'',
				0,
				0,
				0
			);
			echo $fieldInput[0] . " " . $fieldInput[1] . "<br>";
			echo '<input type="hidden" name="extrafields[]" value="' . $name . '">';
			?>
		<?php endforeach; ?>
    </div>
<?php endif; ?>
