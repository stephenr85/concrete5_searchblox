<?php 
	$searchbloxHelper = Loader::helper('searchblox', 'searchblox');
?>
<div class="searchblox-result <?php if(is_array($cssClasses)) echo implode(' ', $cssClasses) ?>">	
	<h4 class="title"><a href="<?php echo $result->url ?>"><?php echo $searchbloxHelper->replaceHighlights($result->title) ?></a></h4>	
	<!--div class="desc"><?php echo $result->description ?></div-->
	<p class="info">
		<span class="context"><?php echo $searchbloxHelper->replaceHighlights($result->context) ?></span>
		<br>
		<span class="meta">
			<small>
				<span class="contenttype"><?php echo $result->contenttype ?></span> - 
				<span class="lastmodified"><?php echo $result->lastmodified ?></span> - 
				<a class="url" href="<?php echo $result->url ?>"><?php echo $result->url ?></a>
			</small>
		</span>
	</p>	
	<hr>
</div>