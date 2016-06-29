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

$user    = JFactory::getUser();
$auth    = JFactory::getSession()->get('auth');
$list    = array();

if ($user->id)
{
	$list = RedshopHelperUser::getUserInformation($user->id);
}
elseif ($auth['users_info_id'])
{
	$list = RedshopHelperUser::getUserInformation(0, 'BT', $auth['users_info_id']);
}

$DOBGroup = (in_array($list->country_code, array('AUT', 'DEU', 'NLD')));
$isNetherland = (in_array($list->country_code, array('NLD')));

$fields = $plugin->params->get('extrafield_payment', array());
?>
<?php if (count($fields) > 0) : ?>
	<div id="extraFields_<?php echo $plugin->name; ?>">
	<?php foreach ($fields as $name) : ?>
		<?php
			$isPNO            = (strpos($name, 'rs_pno') !== false);
			$DOBFields        = (in_array($name, array('rs_birthdate', 'rs_gender', 'rs_house_number')));
			$isHouseExtension = (in_array($name, array('rs_house_extension', 'rs_house_number')));

			if (($isPNO && !$DOBGroup) || ($DOBFields && $DOBGroup) || ($isHouseExtension && $isNetherland))
			{
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
			}
		?>
	<?php endforeach; ?>
	</div>
<?php endif; ?>
