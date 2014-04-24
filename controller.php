<?php       

defined('C5_EXECUTE') or die(_("Access Denied."));

class SearchbloxPackage extends Package {

	protected $pkgHandle = 'searchblox';
	protected $appVersionRequired = '5.5.1';
	protected $pkgVersion = '0.9.0';
	
	public function getPackageDescription() {
		return t(" .");
	}
	
	public function getPackageName() {
		return t("SearchBlox");
	}	
	
	public function install() {		
		$pkg = parent::install();
		$this->configurePackage($pkg);
	}
	
	public function upgrade() {
        $pkg = $this;
        parent::upgrade();
        $this->configurePackage($pkg);
    }
	
	public function configurePackage($pkg){
		$helper = Loader::helper('searchblox/package', 'searchblox');
		
		$helper->addBlockType('searchblox', $pkg);
		
		$helper->addSinglePage('/dashboard/searchblox', array(
			'cName'=>t('SearchBlox'),
			'cDescription'=>t('Manage SearchBlox configuration.')
		), $pkg);
		
	}
}