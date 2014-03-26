<?php defined('C5_EXECUTE') or die;

class DashboardSearchbloxController extends Controller {	
	
	public function on_start(){
		$this->message = Loader::helper('validation/error');
		$this->error = Loader::helper('validation/error');
		parent::on_start();	
	}
	
	public function on_before_render(){
		$this->set('message', $this->message->getList());
		$this->set('error', $this->error);
		parent::on_before_render();	
	}
	
	public function getPackage(){
		return Package::getByHandle('searchblox');	
	}
	
	public function update_settings(){
		$this->error->add(t('TODO: controller "update_settings" method.'));
	}
	
	public function clear_collection($collection){		
		$api = $this->getPackage()->api();
		$api->clearCollection($collection);
		$this->message->add(t('Collection "%s" successfully cleared.', $collection));
	}
}