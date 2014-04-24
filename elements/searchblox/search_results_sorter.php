<div class="searchblox-sorter <?php if(is_array($cssClasses)) echo implode(' ', $cssClasses) ?>">
	<?php
	if(!isset($baseURL)){
		global $c;
		$baseURL = Loader::helper('navigation')->getLinkToCollection($c);
	}
	
	if(!is_array($labels)){
		$labels = array(
			'date'=>t('Date'),
			'alpha'=>t('Alphabetical'),
			'relevance'=>t('Relevance')
		);
	}

	if(!is_array($itemClasses)){
		$itemClasses = array();
	}

	$currentsort = (string)$xml->results['sort'];

	if(!isset($currentCssClass)) $currentCssClass = 'active';
	?>
	<dl class="sub-nav">
	<dt><?php echo t('Sort by:') ?></dt>
	<?php foreach($xml->links->link as $node){
		$url = preg_replace('/^.+\?/', $baseURL.'?', (string)$node['url']);
		$page = (string)$node['page'];
		$label = $labels[$page] ? $labels[$page] : $page;
		$itemClass = array($itemClasses[$page]);
		if($page == $currentsort) $itemClass[]=$currentCssClass;

		if($page != $label){ //Make sure there's a label for it
			echo t('<dd class="%s"><a href="%s">%s</a></dd> ', implode(' ', $itemClass), $url, $label);
		}
	 } ?>
	</dl>
</div>