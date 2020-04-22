<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JHtml::_('behavior.modal');

$watched = $this->session->get('watched_product', array());

if (in_array($this->pid, $watched) == 0) {
	array_push($watched, $this->pid);
	$this->session->set('watched_product', $watched);
}

$template = $this->template;

if (!empty($template) && !empty($template->template_desc)) {
	$templateDesc = $template->template_desc;
} else {
	$templateDesc = RedshopHelperTemplate::getDefaultTemplateContent('product');
}

echo $templateDesc = \RedshopTagsReplacer::_(
	'product',
	$templateDesc,
	[
		'data'               => $this->data,
		'pageHeadingTag'     => $this->pageheadingtag,
		'isViewProduct'      => true,
		'params'             => $this->params,
		'calculatorTemplate' => $this->loadTemplate('calculator')
	]
);

/**
 * Trigger event onAfterDisplayProduct will display content after product display.
 * Will we change only $this->>template inside a plugin, that's why only $this->>template should be
 * passed by reference.
 */
$this->dispatcher->trigger('onAfterDisplayProduct', array(&$templateDesc, $this->params, $this->data));


