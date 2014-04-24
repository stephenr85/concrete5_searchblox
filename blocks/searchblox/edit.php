<?php defined('C5_EXECUTE') or die("Access Denied.");


$form = Loader::helper('form');

$availableCollections = array(''=>'Default');// + $availableCollections;
?>

<div class="control-group">
	<?php $form->label('sbCollection', t('Search Collection')); ?>
    <?php $form->select('sbCollection', $availableCollections, $sbCollection); ?>
</div>