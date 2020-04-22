<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * $displayData extract
 *
 * @var  array $displayData Layout data.
 * @var  array $data        Extra field data
 */
extract($displayData);
?>

<div class="redshop-field-joomla-article">
    <?php foreach ($data as $article) : ?>
        <?php $link = JRoute::_(
            'index.php?option=com_content&view=article' .
            '&id=' . $article->id . ':' . $article->alias .
            '&catid=' . $article->catid
        ) ?>
        <div class="redshop-field-joomla-article-item"><a href="<?php echo $link ?>"><?php echo $article->title ?></a>
        </div>
    <?php endforeach; ?>
</div>