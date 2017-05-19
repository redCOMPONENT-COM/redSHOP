<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

/**
 * Layout variables
 * ---------------------
 *    $msgList      : (array)    The Multi-Dimentional array having system messages
 *    $showHeading  : (boolean)  An optional variable to show/hide heading text in system message
 *    $allowClose   : (boolean)  An optional variable to allow close system message
 */

$msgList     = $displayData['msgList'];
$showHeading = (isset($displayData['showHeading'])) ? $displayData['showHeading'] : true;
$allowClose  = (isset($displayData['allowClose'])) ? $displayData['allowClose'] : true;

?>
<div id="system-message-container">
	<?php if (is_array($msgList) && !empty($msgList)) : ?>
		<?php if (version_compare(JVERSION, '3.0', '>=')): ?>
            <div id="system-message">
				<?php foreach ($msgList as $type => $msgs) : ?>
                    <div class="alert alert-<?php echo $type; ?>">
						<?php // This requires JS so we should add it trough JS. Progressive enhancement and stuff. ?>
						<?php if ($allowClose) : ?>
                            <a class="close" data-dismiss="alert">×</a>
						<?php endif; ?>
						<?php if (!empty($msgs)) : ?>

							<?php if ($showHeading) : ?>
                                <h4 class="alert-heading"><?php echo JText::_($type); ?></h4>
							<?php endif; ?>
                            <div>
								<?php foreach ($msgs as $msg) : ?>
                                    <p><?php echo $msg; ?></p>
								<?php endforeach; ?>
                            </div>
						<?php endif; ?>
                    </div>
				<?php endforeach; ?>
            </div>
		<?php else: ?>
            <dl id="system-message">
				<?php foreach ($msgList as $type => $msgs) : ?>
					<?php if (!empty($msgs)) : ?>

						<?php if ($showHeading) : ?>
                            <dt class="<?php echo strtolower($type); ?>"><?php echo JText::_($type); ?></dt>
						<?php endif; ?>

                        <dd class="<?php echo strtolower($type); ?> message">
                            <ul>
								<?php foreach ($msgs as $msg) : ?>
                                    <li><?php echo $msg; ?></li>
								<?php endforeach; ?>
                            </ul>
                        </dd>
					<?php endif; ?>
				<?php endforeach; ?>
            </dl>
		<?php endif; ?>
	<?php endif; ?>
</div>
