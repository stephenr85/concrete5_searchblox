<div class="searchblox-pager <?php if(is_array($cssClasses)) echo implode(' ', $cssClasses) ?>">
	<?php
	if(!isset($baseURL)){
		global $c;
		$baseURL = Loader::helper('navigation')->getLinkToCollection($c);
	}
	
	if(!is_array($labels)){
		$labels = array(
			'prev'=>'« '.t('Previous'),
			'next'=>t('Next').' »'
		);
	}

	if(!is_array($itemClasses)){
		$itemClasses = array(
			'prev'=>'arrow',
			'next'=>'arrow',
			'numeric'=>'numbers'
		);
	}

	$currentpage = (string)$xml->results['currentpage'];
	$currentsort = (string)$xml->results['sort'];
	$lastpage = (string)$xml->results['lastpage'];

	if(!isset($currentCssClass)) $currentCssClass = 'current';
	?>
	<ul class="pagination">
	<?php foreach($xml->links->link as $node){
		$url = preg_replace('/^.+\?/', $baseURL.'?', (string)$node['url']);
		$page = (string)$node['page'];
		$label = $labels[$page] ? $labels[$page] : $page;
		$itemClass = array($itemClasses[$page]);

		if(is_numeric($page)){
			$itemClass[]=$itemClasses['numeric'];
			if($page == $currentpage){
				$itemClass[]=$currentCssClass;
				echo t('<li class="%s"><a>%s</a></li>', implode(' ', $itemClass), $label);
			}else{
				echo t('<li class="%s"><a href="%s">%s</a></li>', implode(' ', $itemClass), $url, $label);
			}
			
		}else if($page != $label){ //If it's not a number, make sure there's a label for it
			if($page == 'next' && $lastpage != $prevpage){
				//Output last page link
				$itemClass = array();
			 	$itemClass[]=$itemClasses['numeric'];
			 	echo '<li class="unavailable"><a>&hellip;</a></li>';
			 	echo t('<li class="%s"><a href="%s">%s</a></li>', implode(' ', $itemClass), preg_replace('/page=\d+/', 'page='.$lastpage, $url), $lastpage); 
			}
			echo t('<li class="%s"><a href="%s">%s</a></li>', implode(' ', $itemClass), $url, $label);
		}
		$prevpage = $page;
	 }
	  ?>
	</ul>
</div>