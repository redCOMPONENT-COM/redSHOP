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
 * @since __DEPLOY_VERSION__
 */
class RedshopTagsSectionsCategoryFrontPage extends RedshopTagsAbstract
{
	/**
	 * @var    array
	 *
	 * @since __DEPLOY_VERSION__
	 */
	public $category = array();

	/**
	 * @var    integer
	 *
	 * @since __DEPLOY_VERSION__
	 */
	public $itemId;

	/**
	 * @var    integer
	 *
	 * @since __DEPLOY_VERSION__
	 */
	public $model;

	/**
	 * Init
	 *
	 * @return  void
	 *
	 * @since __DEPLOY_VERSION__
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
	 * @since __DEPLOY_VERSION__
	 */
	public function replace()
	{
		$print = $this->data['print'];
		$extraFieldName  = Redshop\Helper\ExtraFields::getSectionFieldNames(2, 1, 1);

		if (isset($print) && $print)
		{
			$onclick = "onclick='window.print();'";
		}
		else
		{
			$print_url = JURI::base() . "index.php?option=com_redshop&view=category&print=1&tmpl=component&Itemid=" . $this->itemId;
			$onclick   = "onclick='window.open(\"$print_url\",\"mywindow\",\"scrollbars=1\",\"location=1\")'";
		}

		$cateogryFrontPageTemplate = $this->getTemplateBetweenLoop('{category_frontpage_loop_start}', '{category_frontpage_loop_end}');
		$categoryTemplate = '';

		foreach ($this->category as $category)
		{
			$data_add = RedshopTagsReplacer::_(
				'category',
				$cateogryFrontPageTemplate['template'],
				array(
					'category'  => $category,
					'itemId'    => $this->itemId
				)
			);

			$data_add = RedshopHelperProductTag::getExtraSectionTag($extraFieldName, $category->id, "2", $data_add);
			$categoryTemplate .= $data_add;
		}

		$this->template = $cateogryFrontPageTemplate['begin'] . $categoryTemplate . $cateogryFrontPageTemplate['end'];

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
			$this->template = $this->strReplace($this->replacements, $this->template);
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
			$this->template = $this->strReplace($this->replacements, $this->template);
		}

		if ($this->isTagExists('{pagination}'))
		{
			$this->replacements["{pagination}"] = $this->model->getCategoryPagination()->getPagesLinks();
			$this->template = $this->strReplace($this->replacements, $this->template);
		}

		return parent::replace();
	}

	/**
	 * Get template content between loop tags
	 *
	 * @param   string  $beginTag  Begin tag
	 * @param   string  $endTag    End tag
	 * @param   string  $template  Template
	 *
	 * @return  mixed
	 *
	 * @since __DEPLOY_VERSION__
	 */
	public function getTemplateBetweenLoop($beginTag, $endTag, $template = '')
	{
		if ($this->isTagExists($beginTag) && $this->isTagExists($endTag))
		{
			$templateStartData = explode($beginTag, $this->template);

			if (!empty($template))
			{
				$templateStartData = explode($beginTag, $template);
			}

			$templateStart     = $templateStartData [0];
			$templateEndData = explode($endTag, $templateStartData [1]);
			$templateEnd     = $templateEndData[1];
			$templateMain = $templateEndData[0];

			return array(
				'begin'    => $templateStart,
				'template' => $templateMain,
				'end'      => $templateEnd
			);
		}

		return false;
	}
}