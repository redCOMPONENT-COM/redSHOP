<?php
/**
 * @package     ${NAMESPACE}
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */
$item  = $displayData['item'];
$index = $displayData['index'];
?>

<?php if ($item->isHacked() || $item->isOverrided() || $item->isMissed()): ?>
    <tr class="is-hacked-<?php echo (int) $item->isHacked(); ?> is-overrided-<?php echo (int) $item->isOverrided(); ?> is-missed-<?php echo (int) $item->isMissed(); ?>">
        <th scope="row"><?php echo $index++; ?></th>
        <td>
			<?php
			$layout = new JLayoutFile('troubleshoots.item.name', $basePath = JPATH_ADMINISTRATOR . '/components/com_redshop/layouts');
			echo $layout->render(array('item' => $item, 'index' => $index));
			?>
        </td>
        <td class="center">
			<?php echo (int) $item->isHacked(); ?>
        </td>
        <td class="center">
			<?php echo (int) $item->isOverrided(); ?>
        </td>
        <td class="center">
	        <?php echo (int) $item->isMissed(); ?>
        </td>
    </tr>
<?php endif; ?>