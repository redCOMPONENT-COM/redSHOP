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
 * @since  2.1.5
 */
class RedshopTagsSectionsWrapper extends RedshopTagsAbstract
{
	public $tags = array(
		'{wrapper_dropdown}',
		'{wrapper_image}',
		'{wrapper_price}',
		'{wrapper_add_checkbox}'
	);

    /**
     * Init function
     * @return mixed|void
     *
     * @throws Exception
     * @since 2.1.5
     */
	public function init()
	{
	}

    /**
     * Executing replace
     * @return string
     *
     * @throws Exception
     * @since 2.1.5
     */
	public function replace()
	{
		$data = $this->data['data'];
		$wrapper = $this->data['wrapper'];
		$wrapperStart = explode("{product_wrapper_start}", $this->template);

		if (isset ($wrapperStart[1]))
		{
			$wrapperStart = explode("{product_wrapper_end}", $wrapperStart[1]);
			$this->template = $wrapperStart[0];
		}

		$hidden = RedshopLayoutHelper::render(
			'tags.common.input',
			array(
				'name' => 'wrapper_price',
				'id' => 'wrapper_price',
				'type' => 'hidden',
				'value' => 0,
				'attr' => '',
				'class' => ''
			),
			'',
            RedshopLayoutHelper::$layoutOption
		);

		$this->template .= $hidden;

		$hidden = RedshopLayoutHelper::render(
			'tags.common.input',
			array(
				'name' => 'wrapper_price_withoutvat',
				'id' => 'wrapper_price_withoutvat',
				'type' => 'hidden',
				'value' => 0,
				'attr' => '',
				'class' => ''
			),
			'',
            RedshopLayoutHelper::$layoutOption
		);

		$this->template .= $hidden;
		$wObj = new stdClass;
		$wObj->wrapper_id   = 0;
		$wObj->wrapper_name = JText::_('COM_REDSHOP_SELECT_WRAPPER');
		$warray[] = $wObj;
		$wrapperimageDiv  = "";
		$wrapperimageDiv .= "<table><tr>";

		for ($i = 0, $in = count($wrapper); $i < $in; $i++)
		{
			$wrapperVat = 0;

			if ($wrapper[$i]->wrapper_price > 0 && !strstr($this->template, "{without_vat}"))
			{
				$wrapperVat = RedshopHelperProduct::getProductTax($data->product_id, $wrapper[$i]->wrapper_price);
			}

			$wp = $wrapper[$i]->wrapper_price + $wrapperVat;
			$wpWithoutvat = $wrapper[$i]->wrapper_price;
			$wid   = $wrapper[$i]->wrapper_id;
			$title = $wrapper[$i]->wrapper_name;
			$alt = $wrapper[$i]->wrapper_name;

			if (Redshop::getConfig()->get('SHOW_PRICE') && (!Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE') || (Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE') && SHOW_QUOTATION_PRICE)))
			{
				$wrapper[$i]->wrapper_name = $wrapper[$i]->wrapper_name . " (" . strip_tags(RedshopHelperProductPrice::formattedPrice($wp)) . ")";
			}

			$wrapperimageDiv .= "<td id='wrappertd" . $wid . "'>";

			if (JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . "wrapper/" . $wrapper[$i]->wrapper_image))
			{
				$thumbUrl         = RedshopHelperMedia::getImagePath(
					$wrapper[$i]->wrapper_image,
					'',
					'thumb',
					'wrapper',
					Redshop::getConfig()->get('DEFAULT_WRAPPER_THUMB_WIDTH'),
					Redshop::getConfig()->get('DEFAULT_WRAPPER_THUMB_HEIGHT'),
					Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
				);

				$wrapperImage = RedshopLayoutHelper::render(
					'tags.common.img_link',
					array(
                        'link'=>'javascript:void(0)',
                        'linkAttr' => '
                            onclick="setWrapper(' . $wid . ',' . $wp . ',' . $wpWithoutvat .',' . $data->product_id . ')" title="'. $title . '"                   
                        ',
                        'src' => $thumbUrl,
                        'alt' => $alt,
                        'imgAttr' => 'title="'. $title . '"'
					),
					'',
                    RedshopLayoutHelper::$layoutOption
				);

				$wrapperimageDiv .= $wrapperImage;
			}

			if (strstr($this->template, "{wrapper_price}"))
			{

				$brTag = RedshopLayoutHelper::render(
					'tags.common.short_tag',
					array(
						'tag' => 'br'
					),
					'',
					RedshopLayoutHelper::$layoutOption
				);

				$wrapperimageDiv .= $brTag;
				$wrapperPriceFormatted = '';

				if (Redshop::getConfig()->get('SHOW_PRICE') && (!Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE') || (Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE') && SHOW_QUOTATION_PRICE)))
				{
					$wrapperPriceFormatted .= RedshopHelperProductPrice::formattedPrice($wp);
				}

				$wrapperDiv = RedshopLayoutHelper::render(
					'tags.common.tag',
					array(
						'text' => $wrapperPriceFormatted,
						'tag' => 'div',
						'class' => '',
						'attr' => 'onclick="setWrapper(' . $wid . ',' . $wp . ',' . $wpWithoutvat . ',' . $data->product_id . ')" align="center"',
					),
					'',
					RedshopLayoutHelper::$layoutOption
				);

				$wrapperimageDiv .= $wrapperDiv;
			}

			$wrapperimageDiv .= "</td>";

			$hidden = RedshopLayoutHelper::render(
				'tags.common.input',
				array(
					'name' => 'w_price',
					'id' => 'w_price' . $wid,
					'type' => 'hidden',
					'value' => $wp,
					'attr' => '',
					'class' => ''
				),
				'',
                RedshopLayoutHelper::$layoutOption
			);

			$this->template .= $hidden;

			$hidden = RedshopLayoutHelper::render(
				'tags.common.input',
				array(
					'name' => 'w_price_withoutvat',
					'id' => 'w_price_withoutvat' . $wid,
					'type' => 'hidden',
					'value' => $wp,
					'attr' => '',
					'class' => ''
				),
				'',
                RedshopLayoutHelper::$layoutOption
			);

			$this->template  .= $hidden;

			if (!Redshop::getConfig()->get('AUTO_SCROLL_WRAPPER'))
			{
				if (($i + 1) % 3 == 0)
				{
					$wrapperimageDiv .= "</tr><tr>";
				}
			}
		}

		$wrapperimageDiv .= "</tr></table>";

		if (Redshop::getConfig()->get('AUTO_SCROLL_WRAPPER'))
		{
            $wrapperimageDiv = "<marquee behavior='scroll'
                direction='left'
                onmouseover='this.stop()'
                onmouseout='this.start()'
                scrolldelay='200' width='200'
                > $wrapperimageDiv </marquee>";
		}

		if (!empty($wrapper))
		{
			$wrapper = array_merge($warray, $wrapper);

			$lists['wrapper_id'] = JHtml::_(
				'select.genericlist',
				$wrapper,
				'wrapper_id',
				'class="inputbox" onchange="calculateTotalPrice(' . $data->product_id . ',0);" ',
				'wrapper_id',
				'wrapper_name',
				0
			);

			$this->addReplace('{wrapper_dropdown}', $lists ['wrapper_id']);
			$this->addReplace('{wrapper_image}', $wrapperimageDiv);
			$this->addReplace('{wrapper_price}', '');
			$wrapperCheckbox = JText::_('COM_REDSHOP_Add_WRAPPER');

			$checkbox = RedshopLayoutHelper::render(
				'tags.common.input',
				array(
					'name' => 'wrapper_check',
					'id' => 'wrapper_check',
					'type' => 'checkbox',
					'value' => '1',
					'attr' => 'onclick="calculateTotalPrice(' . $data->product_id . ', 0);" ',
					'class' => ''
				),
				'',
				RedshopLayoutHelper::$layoutOption
			);

			$wrapperCheckbox .= $checkbox;
			$this->addReplace('{wrapper_add_checkbox}', $wrapperCheckbox);
		}
		else
		{
			return '';
		}

		return parent::replace();
	}
}