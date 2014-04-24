<?php defined('C5_EXECUTE') or die("Access Denied.") ?>

<?php

	//foreach $links as $link...
	//Loader::packageElement('searchblox/search_result_item', $link);

?>

<?php
$form = Loader::helper('form');
$searchbloxHelper = Loader::helper('searchblox', 'searchblox');

$params = array(
	'col'=>$sbCollection,
	'query'=>$query
);
$params = array_merge($params, $searchbloxHelper->getSearchParams($_REQUEST, array('col','xsl')));


if($params['query']){
	$xml = $searchbloxHelper->api()->search($params);
}
?>

<form class="searchblox-form panel">
	<div class="row">
		<div class="columns small-10">
			<?php echo $form->label('query', t('Keywords')); ?>
			<?php echo $form->text('query'); ?>
		</div>
		<div class="columns small-2">
			<label>&nbsp;</label>
			<button type="submit" class="small radius"><?php echo t('Search'); ?></button>
		</div>
	</div>
</form>

<?php if(is_object($xml)){ ?>
	<p class="searchblox-total-results">
		<strong class="hits"><?php echo $xml->results['hits'] ?></strong> results for 
		<strong class="query"><?php echo $xml->results['query'] ?></strong>
	</p>
	<?php if((string)$xml->results['hits'] > 0){ ?>
	<div class="row">
	<?php		
		Loader::packageElement('searchblox/search_results_sorter', 'searchblox', array('xml'=>$xml, 'cssClasses'=>array('top')));
		Loader::packageElement('searchblox/search_results_pager', 'searchblox', array('xml'=>$xml, 'cssClasses'=>array('top', 'pagination-centered')));		
	?>
	</div>
	<hr>
	<div class="searchblox-results row">
		
		<?php foreach($xml->results->result as $node){
			Loader::packageElement('searchblox/search_result_item', 'searchblox', array('result'=>$node, 'xml'=>$xml, 'cssClasses'=>array('columns', 'small-12')));
			
		} ?>
	</div>
	<div class="row">
	<?php
		Loader::packageElement('searchblox/search_results_pager', 'searchblox', array('xml'=>$xml, 'cssClasses'=>array('bottom', 'pagination-centered')));
		Loader::packageElement('searchblox/search_results_sorter', 'searchblox', array('xml'=>$xml, 'cssClasses'=>array('bottom')));
	?>
	</div>
	<?php } ?>
<?php } ?>