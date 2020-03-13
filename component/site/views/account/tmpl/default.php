<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


/** @var RedshopModelAccount $model */
$model    = $this->getModel('account');
$template = RedshopHelperTemplate::getTemplate("account_template");

if (count($template) > 0 && $template[0]->template_desc != "") {
	$templateDesc = $template[0]->template_desc;
} else {
	$templateDesc = RedshopHelperTemplate::getDefaultTemplateContent('account_template');
}

echo RedshopTagsReplacer::_(
	'account',
	$templateDesc,
	[
		'params'   => $this->params,
		'userData' => $this->userdata
	]
);

