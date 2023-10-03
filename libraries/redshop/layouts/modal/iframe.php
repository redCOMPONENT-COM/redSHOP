<?php
/**
 * @package     Aesir.iframe.php
 *
 * @copyright   Copyright (C) 2016 - 2022 Aesir. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 * @since       __DEPLOY_VERSION__
 */

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\Utilities\ArrayHelper;

/**
* Layout variables
* =======================
* @var  array  $displayData          // List of data.
* @var  string $modalButton          // Selector class on the button to trigger the modal
* @var  string $selector             // Id to render the modal
* @var  string $params['title']      // Title for the modal
* @var  string $params['footer']     // Optional Text for the modal footer
* @var  string $params['backdrop']   // true or false. Sets the dark background behind the modal
* @var  string $params['animation']  // true or false. Allows the animation or not
* @var  string $params['closebtn']   // true or false. Show or hide the closebtn
* @var  string $params['keyboard']   // true or false. Allows closing the modal with the esc key
* @var  string $params['width']      // Width for the iframe - Does not work
* @var  string $params['height']     // Height for the iframe - Does not work
* @var  string $params['modalWidth'] // Optional width of the modal body in viewport units (vh)
* @var  string $params['bodyHeight'] // Optional height of the modal body in viewport units (vh)
* @var  string $params['modalCss']   // Size class.
*/

extract($displayData);

if (empty($modalButton)) {
    $modalButton = '.RedshopModalButton';
}

if (empty($selector)) {
    $selector = 'RedshopModalFrame';
}

$iframeAttributes = [];

if (isset($params['height'])) {
    $iframeAttributes['height'] = $params['height'];
}

if (isset($params['width'])) {
    $iframeAttributes['width'] = $params['width'];
}

?>
<script>
    (function($){
        $(document).ready(function () {
            $('<?php echo $modalButton ?>').on('click', function () {
                var modal = $('#<?php echo $selector ?>');
                var frame = modal.find('iframe');
                if (frame.length) {
                    frame.remove();
                }
                modal.find('.modal-body').append('<iframe <?php echo ArrayHelper::toString($iframeAttributes); ?> src="'+$(this).data('url')+'" style="margin:10px" />')
                modal.modal('show');
            });
        });
    })(jQuery);
</script>
<?php

echo HTMLHelper::_(
    'bootstrap.renderModal', $selector, $params
);