<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Tags
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') || die;

/**
 * Tags replacer abstract class
 *
 * @since  __DEPLOY_VERSION__
 */
class RedshopTagsSectionsShippingBox extends RedshopTagsAbstract
{
	public $tags = array('{shipping_box_heading}', '{shipping_box_list}');

	public function init()
	{

	}

	public function replace()
	{
		$shippingBoxes = RedshopHelperShipping::getShippingBox();
		$shippingBoxPostId = $this->data['shippingBoxPostId'];

		$this->addReplace('{shipping_box_heading}', JText::_('COM_REDSHOP_SHIPPING_BOXES'));

		if (count($shippingBoxes) == 1 || (count($shippingBoxes) > 0 && $shippingBoxPostId == 0))
		{
			$shippingBoxPostId = $shippingBoxes[0]->shipping_box_id;
		}

		$shippingBoxList = JText::_('COM_REDSHOP_NO_SHIPPING_BOX');

		if (count($shippingBoxes) > 0)
		{
			$shippingBoxList = "";

			for ($i = 0, $in = count($shippingBoxes); $i < $in; $i++)
			{
				$shippingBoxId          = $shippingBoxes[$i]->shipping_box_id;
				$shippingBoxPriorityPre = 0;

				// Previous priority
				if ($i > 0)
				{
					$shippingBoxPriorityPre = $shippingBoxes[$i - 1]->shipping_box_priority;
				}

				// Current priority
				$shippingBoxPriority = $shippingBoxes[$i]->shipping_box_priority;
				$checked               = ($shippingBoxPostId == $shippingBoxId) ? "checked='checked'" : "";

				if ($i == 0 || ($shippingBoxPriority == $shippingBoxPriorityPre))
				{
					$shippingBoxList .= RedshopLayoutHelper::render(
						'tags.shipping_box.shipping_box_list',
						array(
							'shippingBoxId' => $shippingBoxId,
							'checked' => $checked,
							'shippingBox' => $shippingBoxes[$i]
						),
						'',
						RedshopLayoutHelper::$layoutOption
					);
				}
			}
		}

		$this->addReplace('{shipping_box_list}', $shippingBoxList);

		$style = 'none';

		$shippingmethod = RedshopHelperOrder::getShippingMethodInfo();

		for ($s = 0, $sn = count($shippingmethod); $s < $sn; $s++)
		{
			if ($shippingmethod[$s]->element == 'bring' || $shippingmethod[$s]->element == 'ups' || $shippingmethod[$s]->element == 'uspsv4')
			{
				$style = 'block';
			}
		}

		if (count($shippingBoxes) <= 1 || count($shippingmethod) <= 1)
		{
			$style = 'none';
		}

		$this->template = RedshopLayoutHelper::render(
			'tags.common.tag',
			array(
				'tag' => 'div',
				'text' => $this->template,
				'attr' => 'style="display:' . $style . '"'
			),
			'',
			RedshopLayoutHelper::$layoutOption
		);

		return parent::replace();
	}
}