<?php
	class SearchbloxBlockController extends BlockController {
		
		protected $btDescription = "Displays the page name, a custom attribute, or a specific override.";
		protected $btName = "Searchblox Search";
		protected $btTable = 'btSearchblox';
		protected $btInterfaceWidth = "500";
		protected $btInterfaceHeight = "150";
		
		
		function getPackage(){
			return Package::getByHandle('searchblox');	
		}
		
		function getSelectedCollection(){
			$coll = $this->sbCollection;
			if(!$this->sbCollection){
				//Fallback to default collection
				$coll = $this->getPackage()->config('default_collection');
			}
			$this->set('sbCollection', $coll);
			return $coll;
		}
		
		function getAvailableCollections(){
			$collections = $this->getPackage()->api()->getCollections(); 
			$this->set('availableCollections', $collections);
			return $collections;
		}
		
		function edit(){
			$this->getSelectedCollection();
			$this->getAvailableCollections();
		}
		
		function view(){
			$this->getSelectedCollection();
			//TODO
		}
		
		function save($data){
			if(empty($data['override'])){
				$data['override'] = NULL;	
			}
			
			parent::save($data);
		}
		
	}
	
?>