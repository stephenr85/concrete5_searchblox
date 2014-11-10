<?php defined('C5_EXECUTE') or die(_("Access Denied."));

class SearchbloxHelper {

	public function getPackage(){
		return Package::getByHandle('searchblox');
	}
	public function getSearchParams($arr, $ignore=array()){
		$params = array();
		$accept = array('query','filter','sort','col','startdate','page');
		foreach($arr as $key=>$value){
			if(in_array($key, $accept) && !in_array($key, $ignore)){
				$params[$key] = $value;
			}
		}
		return $params;
	}

	public function replaceHighlights($content){
		if($content instanceof SimpleXMLElement){
			$content = $this->innerXML($content);
		}
		$content = preg_replace('/highlight>/', 'mark>', $content);
		$content = preg_replace('/text>/', 'span>', $content);
		return $content;
	}

	public function innerXML($element){
        $tag = $element->getName();
        return preg_replace('!<'. $tag .'(?:[^>]*)>(.*)</'. $tag .'>!Ums', '$1', $element->asXml());
    }

    public function api(){
    	if(!is_object($this->api)){
			Loader::library('searchblox', 'searchblox');
			$pkg = $this->getPackage();
			$this->api = new SearchBloxClient($pkg->config('api_url'), $pkg->config('api_key'));
		}
		return $this->api;
    }

    public function search($params){
    	$api = $this->api();
    	if(!$params['col']) $params['col'] = $this->getPackage()->config('default_collection');
    	return $api->search($params);
    }

}