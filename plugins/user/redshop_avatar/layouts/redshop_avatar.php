<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JHtml::_('bootstrap.tooltip');

/**
 * $displayData extract
 *
 * @param   array  $displayData Extra field data
 * @param   string $image       Image file name
 * @param   string $key         Image file name
 */
extract($displayData);

$user = JFactory::getUser();
RedshopHelperMediaImage::requireDependencies();
?>
<?php if (!empty($image)): ?>
    <img src="<?php echo REDSHOP_FRONT_IMAGES_ABSPATH . $key . '/' . $user->id . '/' . $image ?>"/>
<?php endif; ?>
<?php if (!$user->guest): ?>
    <!-- Button to trigger modal -->
    <a href="#myModal" role="button" class="btn" data-toggle="modal">Launch demo modal</a>

    <!-- Modal -->
    <div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h3 id="myModalLabel">Modal header</h3>
        </div>
        <div class="modal-body">
			<?php echo RedshopLayoutHelper::render(
				'dropzone',
				array(
					'id'           => $key,
					'type'         => $key,
					'sectionId'    => $user->id,
					'mediaSection' => $key,
					'file'         => null
				),
				__DIR__
			);
			?>
        </div>
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
            <button class="btn btn-primary">Save changes</button>
        </div>
    </div>
<?php endif;
