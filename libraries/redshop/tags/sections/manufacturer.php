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
 * @since  3.0
 */
class RedshopTagsSectionsManufacturer extends RedshopTagsAbstract
{
	/**
	 * @var    array
	 *
	 * @since   3.0
	 */
	public $tags = array('{print}', '{order_by}', '{pagination}');

	/**
	 * Init
	 *
	 * @return  mixed
	 *
	 * @since   3.0
	 */
	public function init()
	{

	}

	/**
	 * Execute replace
	 *
	 * @return  string
	 *
	 * @since   3.0
	 */
	public function replace()
	{
		$url    = JURI::base();
		$app    = JFactory::getApplication();
		$print  = $app->input->getInt('print');
		$itemId = $app->input->getInt('Itemid');
		$detail = $this->data['detail'];

		if ($print)
		{
			$onClick = "onclick='window.print();'";
		}
		else
		{
			$printUrl = $url . "index.php?option=com_redshop&view=manufacturers&print=1&tmpl=component&Itemid=" . $itemId;
			$onClick  = "onclick='window.open(\"$printUrl\",\"mywindow\",\"scrollbars=1\",\"location=1\")'";
		}

		$printTag = RedshopLayoutHelper::render(
			'tags.common.print',
			array(
				'onClick' => $onClick
			),
			'',
			RedshopLayoutHelper::$layoutOption
		);

		$templateMiddle = "";
		$templateData   = $this->getTemplateBetweenLoop('{manufacturer_loop_start}', '{manufacturer_loop_end}');

		if (!empty($templateData))
		{
			$templateMiddle = $templateData['template'];
		}

		$replaceMiddledata = '';

		if ($detail && $templateMiddle != "")
		{
			// Limit the number of manufacturers shown
			$maxCount = $this->data['params']->get('maxmanufacturer');
			if (count($detail) < $maxCount)
			{
				$maxCount = count($detail);
			}

			for ($i = 0; $i < $maxCount; $i++)
			{
				$replaceMiddledata .= $this->replaceManufacturerItem($templateMiddle, $detail[$i], $itemId);
			}
		}

		$this->template = $templateData['begin'] . $replaceMiddledata . $templateData['end'];

		$this->addReplace('{print}', $printTag);

		if ($this->isTagExists('{order_by}'))
		{
			$orderbyForm  = RedshopLayoutHelper::render(
				'tags.manufacturer.order_by',
				array('orderSelect' => $this->data['lists']['order_select']),
				'',
				RedshopLayoutHelper::$layoutOption
			);

			$this->addReplace('{order_by}', $orderbyForm);
		}

		if ($this->isTagExists('{pagination}'))
		{
			if ($print)
			{
				$this->addReplace('{pagination}', '');
			}
			else
			{
				$this->addReplace('{pagination}', $this->data['pagination']->getPagesLinks());
			}
		}

		return parent::replace();
	}

	/**
	 * Replace manufacturer
	 *
	 * @param   string   $template
	 * @param   object   $data
	 * @param   integer  $itemId

	 * @return  string
	 *
	 * @since   3.0
	 */
	public function replaceManufacturerItem($template, $data, $itemId)
	{
		$extraFieldName = Redshop\Helper\ExtraFields::getSectionFieldNames(10, 1, 1);
		$this->replacements = [];

		if ($data != '')
		{
			$mimgTag = '{manufacturer_image}';
			$mhThumb = Redshop::getConfig()->getInt('MANUFACTURER_THUMB_HEIGHT');
			$mwThumb = Redshop::getConfig()->getInt('MANUFACTURER_THUMB_WIDTH');

			$link        = JRoute::_('index.php?option=com_redshop&view=manufacturers&layout=detail&mid=' . $data->id . '&Itemid=' . $itemId);
			$manProducts = JRoute::_('index.php?option=com_redshop&view=manufacturers&layout=products&mid=' . $data->id . '&Itemid=' . $itemId);

			$manufacturerName = RedshopLayoutHelper::render(
				'tags.common.link',
				array(
					'link' => $manProducts,
					'content' => RedshopHelperUtility::maxChars($data->name, Redshop::getConfig()->get('MANUFACTURER_TITLE_MAX_CHARS'), Redshop::getConfig()->get('MANUFACTURER_TITLE_END_SUFFIX'))
				),
				'',
				RedshopLayoutHelper::$layoutOption
			);

			$this->replacements['{manufacturer_name}'] = $manufacturerName;

			// Extra field display
			$template = RedshopHelperProductTag::getExtraSectionTag(
				$extraFieldName, $data->id, RedshopHelperExtrafields::SECTION_MANUFACTURER, $template
			);

			if (strpos($template, $mimgTag) !== false)
			{
				$media     = RedshopEntityManufacturer::getInstance($data->id)->getMedia();
				$thumImage = "";

				if ($media->isValid() && !empty($media->get('media_name'))
					&& JFile::exists(REDSHOP_MEDIA_IMAGE_RELPATH . 'manufacturer/' . $data->id . '/' . $media->get('media_name')))
				{
					$altText = $media->get('media_alternate_text', $data->name);

					if (Redshop::getConfig()->get('WATERMARK_MANUFACTURER_IMAGE') || Redshop::getConfig()->get('WATERMARK_MANUFACTURER_THUMB_IMAGE'))
					{
						$manufacturerImg = RedshopHelperMedia::watermark(
							'manufacturer',
							$media->get('media_name'),
							$mwThumb,
							$mhThumb,
							Redshop::getConfig()->get('WATERMARK_MANUFACTURER_IMAGE')
						);
					}
					else
					{
						$manufacturerImg = RedshopHelperMedia::getImagePath(
							$media->get('media_name'),
							'',
							'thumb',
							'manufacturer',
							$mwThumb,
							$mhThumb,
							Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING'),
							'manufacturer',
							$data->id
						);
					}

					$thumImage = RedshopLayoutHelper::render(
						'tags.manufacturer.image',
						array(
							'altText' => $altText,
							'manufacturerId' => $data->id,
							'media' => $media,
							'manufacturerImg' => $manufacturerImg
						),
						'',
						RedshopLayoutHelper::$layoutOption
					);
				}

				$this->replacements[$mimgTag] = $thumImage;
			}

			$this->replacements['{manufacturer_description}'] = $data->description;
			$this->replacements['{manufacturer_link}'] = $link;
			$this->replacements['{manufacturer_allproductslink}'] = $manProducts;
			$this->replacements['{manufacturer_allproductslink_lbl}'] = JText::_('COM_REDSHOP_MANUFACTURER_ALLPRODUCTSLINK_LBL');
			$this->replacements['{manufacturer_link_lbl}'] = JText::_('COM_REDSHOP_MANUFACTURER_LINK_LBL');
		}
		else
		{
			return null;
		}

		return $this->strReplace($this->replacements, $template);
	}
}