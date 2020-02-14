<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;
JHTML::_('behavior.modal');

// Page Title Start
$pageTitle = JText::_('COM_REDSHOP_MANUFACTURER');

if ($this->pageheadingtag != '')
{
	$pageTitle = $this->pageheadingtag;
} ?>
    <h1 class="componentheading<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>">
		<?php
		if ($this->params->get('show_page_heading', 1))
		{
			if ($this->params->get('page_title') != $pageTitle)
			{
				echo $this->escape($this->params->get('page_title'));
			}
			else
			{
				echo $pageTitle;
			}
		} ?>
    </h1>
<?php
// Page title end
$manufacturersTemplate = RedshopHelperTemplate::getTemplate("manufacturer");

if (count($manufacturersTemplate) > 0 && $manufacturersTemplate[0]->template_desc != "")
{
	$templateDesc = $manufacturersTemplate[0]->template_desc;
}
else
{
    $templateDesc = RedshopHelperTemplate::getDefaultTemplateContent('manufacturer');
}

$templateDesc = RedshopTagsReplacer::_(
        'manufacturer',
        $templateDesc,
        array(
            'detail' => $this->detail,
            'pagination' => $this->pagination,
            'params' => $this->params,
            'lists' => $this->lists
        )
);


$templateDesc = RedshopHelperTemplate::parseRedshopPlugin($templateDesc);
echo eval("?>" . $templateDesc . "<?php ");
