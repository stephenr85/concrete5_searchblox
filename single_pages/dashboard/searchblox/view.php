<?php
$form = Loader::helper('form');
?>

<div class="control-group">
<?php $form->label('api_url', t('SearchBlox URL')); ?>
<?php $form->text('api_url'); ?>
</div>

<div class="control-group">
<?php $form->label('api_key', t('API Key')); ?>
<?php $form->text('api_key'); ?>
</div>

<div class="control-group">
<?php $form->label('default_collection', t('Default Collection')); ?>
<?php $form->select('default_collection', array()); ?>
</div>

<?php $form->submit(t('Save Settings')) ?>