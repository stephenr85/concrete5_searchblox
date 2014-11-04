<?php defined('C5_EXECUTE') or die;

class DashboardSearchbloxController extends Controller {	
	
	public function on_start(){
		$this->message = array();
		$this->error = Loader::helper('validation/error');
		parent::on_start();	
	}
	
	public function on_before_render(){
		$this->set('message', !empty($this->message) ? implode('<br>', $this->message) : NULL);
		$this->set('error', $this->error);
		parent::on_before_render();	
	}
	
	public function getPackage(){
		return Package::getByHandle('searchblox');	
	}

	public function view(){
		$pkg = $this->getPackage();
		
		$this->set('default_collection', $pkg->config('default_collection'));
		$this->set('api_key', $pkg->config('api_key'));

		$api_url = $pkg->config('api_url');
		if(!empty($api_url)){
			$this->set('api_url', $api_url);
			$collections = Loader::helper('searchblox', 'searchblox')->api()->getCollections();
			$this->set('availableCollections', $collections);
		}
	}
	
	public function update_settings(){
		$pkg = $this->getPackage();
		
		if($this->isPost()){
			$pkg->saveConfig('api_url', $_POST['api_url']);
			$pkg->saveConfig('api_key', $_POST['api_key']);
			$pkg->saveConfig('default_collection', $_POST['default_collection']);
			$this->message[]= t('Settings updated successfully.');
		}
		
		$this->view();
	}
}