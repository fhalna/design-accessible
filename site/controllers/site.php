<?php

return function ($page, $pages, $site, $kirby) {

	$phases = $page->ressources()->toStructure()->pluck('phase', ',', true);
	$langs = page('ressources')->blueprint()->field('ressources')['fields']['lang']['options'];

	// get counts for all tags as a measure of popularity
	$thematiques = $page->ressources()->toStructure()->pluck('thematique', ',', true);
	$thematiques = array_map(function($thematique) { 
	  $count = page('ressources')->ressources()->toStructure()->filterBy('thematique', $thematique, ",")->count();
	  return array('name' => $thematique, 'count' => $count);
	}, $thematiques);

	usort($thematiques, function($a, $b) {
	    return $b['count'] - $a['count'];
	});


	// Ressources filtered
	$ressources = page('ressources')->ressources()->toStructure()->sortBy('date', 'desc');
	if($tag = param('phase')) {
    	$ressources = $ressources->filterBy('phase', $tag, ',');
  	}
	if($tag = param('lang')) {
    	$ressources = $ressources->filterBy('lang', $tag, ',');
  	}
	if($tag = param('thematique')) {
    	$ressources = $ressources->filterBy('thematique', $tag, ',');
  	}

	return compact('phases', 'langs', 'thematiques', 'ressources');
};

?>