<?php
/**
 * @package     redshop.iframe.php
 *
 * @copyright   Copyright (C) 2016 - redSHOP All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 * @since       __DEPLOY_VERSION__
 */

use Joomla\CMS\Language\Text;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\Utilities\ArrayHelper;

/**
 * Layout variables
 * =======================
 * @var  array  $displayData           // List of data.
 * @var  string $selector              // Id to trigger the modal
 * @var  string $params['title']       // Title for the modal
 * @var  string $params['buttonText']  // Text for the button
 * @var  string $params['buttonClass'] // Class for the buttom
 * @var  string $params['buttonId']    // Id for the buttom
 * @var  string $params['url']         // url for a iFrame modal
 * @var  string $params['backdrop']    // If user can close modal with click outside of the modal. static = cannot close with click outside
 * @var  string $params['animation']   // true or false. Allows the animation or not
 * @var  string $params['closebtn']    // true or false. Show or hide the closebtn in header
 * @var  string $params['keyboard']    // true or false. Allows closing the modal with the esc key
 * @var  string $params['width']       // Width for the iframe - Does not work
 * @var  string $params['height']      // Height for the iframe - Does not work
 * @var  string $params['modalWidth']  // Optional width of the modal body in viewport units (vh) (50 = 500px)
 * @var  string $params['bodyHeight']  // Optional height of the modal body in viewport units (vh) (50 = 500px)
 * @var  string $params['footer']      // Optional Text for the modal footer
 */

extract($displayData);

if (empty($selector)) {
    $selector = 'RedshopModalFrame';
}

if (empty($params['title'])) {
    $params['title'] = '';
}

if (empty($params['buttonText'])) {
    $params['buttonText'] = '';
}

if (empty($params['buttonClass'])) {
    $params['buttonClass'] = '';
}

if (empty($params['buttonId'])) {
    $params['buttonId'] = '';
}

if (empty($params['url'])) {
    $params['url'] = '';
}

if (empty($params['backdrop'])) {
    $params['backdrop'] = '';
}

if (empty($params['closeButton'])) {
    $params['closeButton'] = '';
}

if (empty($params['height'])) {
    $params['height'] = '';
}

if (empty($params['width'])) {
    $params['width'] = '';
}

if (empty($params['bodyHeight'])) {
    $params['bodyHeight'] = '';
}

if (empty($params['modalWidth'])) {
    $params['modalWidth'] = '';
}

if (empty($params['footer'])) {
    $params['footer'] = '';
}
?>

<button class="<?php echo $params['buttonClass'] ?> <?php echo $selector ?> hasTooltip" id="<?php echo $buttonId ?>"
    data-bs-toggle="modal" type="button" data-bs-target="#<?php echo $selector ?>"
    title="<?php echo $params['buttonText'] ?>">
    <?php echo $params['buttonText'] ?>
</button>

<?php
echo HTMLHelper::_(
    'bootstrap.renderModal',
    $selector,
    [
        'title'       => $params['title'],
        'url'         => $params['url'],
        'backdrop'    => $params['backdrop'],
        'keyboard'    => true,
        'closeButton' => true,
        'height'      => $params['height'],
        'width'       => $params['width'],
        'bodyHeight'  => $params['bodyHeight'],
        'modalWidth'  => $params['modalWidth'],
        'footer'      => $params['footer']
    ]
);