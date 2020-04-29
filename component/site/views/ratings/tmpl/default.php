<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

$mainTemplate = RedshopHelperTemplate::getTemplate("review");

if (count($mainTemplate) > 0 && $mainTemplate[0]->template_desc) {
    $mainTemplate = $mainTemplate[0]->template_desc;
} else {
    $mainTemplate = RedshopHelperTemplate::getDefaultTemplateContent('review');
}

if ($this->params->get('show_page_heading', 1)) {
    if ($this->params->get('page_title')) {
        ?>
        <h1 class="componentheading<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>">
            <?php echo $this->escape($this->params->get('page_title')); ?>
        </h1>
        <?php
    }
}


$mainTemplate = RedshopTagsReplacer::_(
    'reviews',
    $mainTemplate,
    array(
        'products' => $this->detail,
        'model'    => $this->getModel('ratings'),
        'params'   => $this->params
    )
);

echo eval("?>" . $mainTemplate . "<?php ");
