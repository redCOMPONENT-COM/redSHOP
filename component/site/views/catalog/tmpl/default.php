<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

$redTemplate = Redtemplate::getInstance();
$app         = JFactory::getApplication();

$Itemid = $app->input->getInt('Itemid');
$layout = $app->input->getCmd('layout', 'default');

/** @var RedshopModelCatalog $model */
$model = $this->getModel('catalog');

$template = RedshopHelperTemplate::getTemplate("catalog");

if (count($template) > 0 && $template[0]->template_desc != "") {
    $templateDesc = $template[0]->template_desc;
} else {
    $templateDesc = RedshopHelperTemplate::getDefaultTemplateContent("catalog");
}

if ($this->params->get('show_page_heading', 1)) {
    if ($this->params->get('page_title')) {
        ?>
    <h1 class="componentheading<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>">
        <?php echo $this->escape($this->params->get('page_title')); ?>
        </h1><?php
    }
}

$catalogTemplateWapper = \RedshopTagsReplacer::_(
    'catalog',
    $templateDesc,
    array(
        'itemId' => $Itemid
    )
);

echo $catalogTemplateWapper;
?>