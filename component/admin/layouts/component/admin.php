<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Layouts
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


extract($displayData);

$data = $displayData;

$content                = $view->loadTemplate($tpl);
$displayData['content'] = $content;

$app   = JFactory::getApplication();
$input = $app->input;

/**
 * Handle raw format
 */
$format = $input->getString('format');

if ('raw' === $format)
{
	/** @var RView $view */
	$view = $data['view'];

	if (!($view instanceof RedshopViewAdmin || $view instanceof RedshopViewList || $view instanceof RedshopViewForm))
	{
		throw new InvalidArgumentException(
			sprintf(
				'Invalid view %s specified for the component layout',
				get_class($view)
			)
		);
	}

	$toolbar = $view->getToolbar();

	// Get the view render.
	return $content;
}

$templateComponent = 'component' === $input->get('tmpl');
$input->set('tmpl', 'component');

echo JLayoutHelper::render('component.assets');

// Do we have to display the sidebar ?
$displaySidebar = false;

if (isset($data['sidebar_display']))
{
	$displaySidebar = (bool) $data['sidebar_display'];
}

// Do we have to display the sidebar ?
$disableSidebar = false;

if (isset($data['sidebar_disable']))
{
	$disableSidebar = (bool) $data['sidebar_disable'];
}

// The view to render.
if (!isset($data['view']))
{
	throw new InvalidArgumentException('No view specified in the component layout.');
}

/** @var RView $view */
$view = $data['view'];

if (!($view instanceof RedshopViewAdmin || $view instanceof RedshopViewList || $view instanceof RedshopViewForm))
{
	throw new InvalidArgumentException(
		sprintf(
			'Invalid view %s specified for the component layout',
			get_class($view)
		)
	);
}

if ($content instanceof Exception)
{
	return $content;
}
?>
    <script type="text/javascript">
        jQuery(document).ready(function () {
			<?php if (!$displaySidebar && !$disableSidebar) : ?>
            jQuery('body').addClass('sidebar-collapse');
			<?php endif; ?>
        });
    </script>

<?php if ($view->getLayout() === 'modal' || $view->getName() == 'wizard') : ?>
    <div class="row-fluid RedSHOP">
        <section id="component">
            <div class="row-fluid message-sys" id="message-sys"></div>
            <div class="row-fluid">
				<?php echo $content ?>
            </div>
        </section>
    </div>
<?php elseif ($templateComponent) : ?>
    <div class="redSHOP">
        <section id="component">
            <div class="message-sys" id="message-sys"></div>
            <div class="popup">
				<?php echo $content ?>
            </div>
        </section>
    </div>
<?php else : ?>
	<?php echo JLayoutHelper::render('component.full', $displayData); ?>
<?php endif;
