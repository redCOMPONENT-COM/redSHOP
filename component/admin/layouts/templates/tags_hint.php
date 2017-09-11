<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

/**
 * Layout variables
 * @var  array  $displayData Available data.
 * @var  array  $tags        Tag data.
 * @var  string $header      Heading.
 */
extract($displayData);

$domId = uniqid();
$header = empty($header) ? JText::_('COM_REDSHOP_AVAILABLE_TEMPLATE_TAGS') : $header;
?>
<div class="panel panel-default">
    <div class="panel-heading" role="tab" id="heading<?php echo $domId ?>">
        <h4 class="panel-title">
            <a role="button" data-toggle="collapse" href="#collapse<?php echo $domId ?>" aria-expanded="true" aria-controls="collapse<?php echo $domId ?>">
				<?php echo $header ?>
            </a>
        </h4>
    </div>
    <div id="collapse<?php echo $domId ?>" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading<?php echo $domId ?>">
        <div class="panel-body">
            <?php if (empty($tags)): ?>
                <p><?php echo JText::_('COM_REDSHOP_TEMPLATE_TAG_NO_TAGS_AVAILABLE') ?></p>
            <?php else: ?>
            <table class="table table-striped">
                <tbody>
				<?php foreach ($tags as $tag => $desc): ?>
                    <tr>
                        <td width="30%">
                            <strong class="text-primary">{<?php echo $tag ?>}</strong>
                        </td>
                        <td>
							<?php echo $desc ?>
                        </td>
                    </tr>
				<?php endforeach; ?>
                </tbody>
            </table>
            <?php endif; ?>
        </div>
    </div>
</div>
