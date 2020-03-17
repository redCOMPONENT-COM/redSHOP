<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Tags
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Tags replacer abstract class
 *
 * @since 3.0
 */
class RedshopTagsSectionsCategoryFrontPage extends RedshopTagsAbstract
{
	/**
	 * @var    array
	 *
	 * @since 3.0
	 */
	public $category = array();

	/**
	 * @var    integer
	 *
	 * @since 3.0
	 */
	public $itemId;

	/**
	 * @var    integer
	 *
	 * @since 3.0
	 */
	public $model;

	/**
	 * Init
	 *
	 * @return  void
	 *
	 * @since 3.0
	 */
	public function init()
	{
		$this->category = $this->data['category'];
		$input = JFactory::getApplication()->input;
		$this->itemId = $input->get('Itemid');
		$this->model = $this->data['model'];
	}

	/**
	 * Override parent replace with own category replacing
	 *
	 * @return  string
	 * @throws  Exception
	 *
	 * @since 3.0
	 */
	public function replace()
	{
		$print = $this->data['print'];
		$extraFieldName  = \Redshop\Helper\ExtraFields::getSectionFieldNames(2, 1, 1);

		if (isset($print) && $print)
		{
			$onclick = "onclick='window.print();'";
		}
		else
		{
			$print_url = JURI::base() . "index.php?option=com_redshop&view=category&print=1&tmpl=component&Itemid=" . $this->itemId;
			$onclick   = "onclick='window.open(\"$print_url\",\"mywindow\",\"scrollbars=1\",\"location=1\")'";
		}

		$cateogoryFrontPageTemplate = $this->getTemplateBetweenLoop('{category_frontpage_loop_start}', '{category_frontpage_loop_end}');
		$categoryTemplate = '';

		if (isset($this->category) && is_array($this->category) && count($this->category) > 0)
		{
			foreach ($this->category as $category)
			{
				$dataAdd = RedshopTagsReplacer::_(
					'category',
					$cateogoryFrontPageTemplate['template'] ?? '',
					array(
						'category'  => $category,
						'itemId'    => $this->itemId
					)
				);

				$dataAdd = RedshopHelperProductTag::getExtraSectionTag($extraFieldName, $category->id, "2", $dataAdd);
				$categoryTemplate .= $dataAdd;
			}
		}

		$template = $cateogoryFrontPageTemplate['begin'] . $categoryTemplate . $cateogoryFrontPageTemplate['end'];

		if ($this->isTagExists('{print}'))
		{
			$image = RedshopLayoutHelper::render(
				'tags.common.img',
				array(
					'src'      => JSYSTEM_IMAGES_PATH . "printButton.png",
					'atl'      => JText::_('COM_REDSHOP_PRINT_LBL'),
					'attr'     => 'title="' . JText::_('COM_REDSHOP_PRINT_LBL') . '"'
				),
				'',
				RedshopLayoutHelper::$layoutOption
			);

			$link = RedshopLayoutHelper::render(
				'tags.common.link',
				array(
					'link'      => $onclick,
					'attr'     => 'title="' . JText::_("COM_REDSHOP_PRINT_LBL") . '"',
					'content'   => $image
				),
				'',
				RedshopLayoutHelper::$layoutOption
			);

			$this->replacements["{print}"] = $link;
            $template = $this->strReplace($this->replacements, $template);
		}

		if ($this->isTagExists('{category_frontpage_introtext}'))
		{
			$introText = RedshopLayoutHelper::render(
				'tags.common.tag',
				array(
					'tag'   => 'div',
					'class' => 'introtext',
					'id'    => 'introtext',
					'text'  => Redshop::getConfig()->get('CATEGORY_FRONTPAGE_INTROTEXT')
				),
				'',
				RedshopLayoutHelper::$layoutOption
			);

			$this->replacements["{category_frontpage_introtext}"] = $introText;
            $template = $this->strReplace($this->replacements, $template);
		}

		if ($this->isTagExists('{pagination}'))
		{
			$this->replacements["{pagination}"] = $this->model->getCategoryPagination()->getPagesLinks();
            $template = $this->strReplace($this->replacements, $template);
		}

		$this->template = $template ? $template : $this->template;

		return parent::replace();
	}
}