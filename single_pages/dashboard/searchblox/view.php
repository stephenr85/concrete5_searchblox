<?php
$form = Loader::helper('form');
?>

<?php echo Loader::helper('concrete/dashboard')->getDashboardPaneHeaderWrapper(t('SearchBlox Settings'), '', false, false); ?>

<div class="ccm-pane-body">
	<form method="post" action="<?php echo $this->action('update_settings')?>">
		<div class="clearfix">
		<?php echo $form->label('api_url', t('SearchBlox URL')); ?>
		<div class="input"><?php echo $form->text('api_url', $api_url, array('style'=>'width:95%;')); ?></div>
		</div>
		<?php /*
		<div class="control-group">
		<?php echo $form->label('api_key', t('API Key')); ?>
		<?php echo $form->text('api_key', $api_key); ?>
		</div>
		*/ ?>

		<?php if($api_url && is_array($availableCollections)){ ?>
		<div class="clearfix">
		<?php echo $form->label('default_collection', t('Default Collection')); ?>
		<div class="input"><?php echo $form->select('default_collection', array(''=>'Select...')+$availableCollections, $default_collection); ?></div>
		</div>
		<?php }else{ ?>

		<?php } ?>
		<?php echo $form->submit('save', t('Save Settings')); ?>
	</form>
	<div class="clearfix"></div>
</div>

<div class="ccm-pane-footer"></div>

<?php echo Loader::helper('concrete/dashboard')->getDashboardPaneFooterWrapper(false);?>
