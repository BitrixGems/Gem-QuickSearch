<?php

/**
 * Быстрый поиск по админке
 *
 * @author Alexey Kalinin <hsalkaline@gmail.com>
 *
 */
class BitrixGem_QuickSearch extends BaseBitrixGem {

	protected $aGemInfo = array(
		'GEM' => 'QuickSearch',
		'AUTHOR' => 'Alexey Kalinin <hsalkaline@gmail.com>',
		'DATE' => '20.04.2011',
		'VERSION' => '0.2',
		'NAME' => 'QuickSearch',
		'DESCRIPTION' => "Быстрый поиск по админке Битрикса",
		'CHANGELOG' => '',
		'REQUIREMENTS' => 'jQuery',
	);
	private $aSearchResultCollectionProviders;
	private $aSearchResultCollectionPresenters;
	private $aSearchResultPresenters;
	private $oSearchResultPresenterFactory;

	private function getSearchResultCollectionProviders() {
		if (empty($this->aSearchResultCollectionProviders)) {
			$aProviders = $this->createObjectsFromFilesInDirectory(dirname(__FILE__) . '/classes/SearchResultCollectionProviders/');
			$this->aSearchResultCollectionProviders = $aProviders;
		}
		return $this->aSearchResultCollectionProviders;
	}

	private function getSearchResultPresenters() {
		if (empty($this->aSearchResultPresenters)) {
			$aPresenters = $this->createObjectsFromFilesInDirectory(dirname(__FILE__) . '/classes/SearchResultPresenters/');
			$this->aSearchResultPresenters = $aPresenters;
		}
		return $this->aSearchResultPresenters;
	}

	public function getSearchResultCollectionPresenters() {
		if (empty($this->aSearchResultCollectionPresenters)) {
			$aPresenters = $this->createObjectsFromFilesInDirectory(dirname(__FILE__) . '/classes/SearchResultCollectionPresenters/');
			$this->aSearchResultCollectionPresenters = $aPresenters;
		}
		return $this->aSearchResultCollectionPresenters;
	}

	/**
	 * Читает файлы по указанному пути и создает объекты прочитанных классов
	 */
	private function createObjectsFromFilesInDirectory($sPath) {
		$aResult = array();
		$aFiles = glob($sPath . DIRECTORY_SEPARATOR . '*.class.php');
		foreach ($aFiles as $sFile) {
			$sBasename = basename($sFile);
			$sClassName = substr($sBasename, 0, strpos($sBasename, ".class.php"));
			$aResult[] = new $sClassName();
		}
		return $aResult;
	}

	private function getSearchResultPresenterFactory() {
		if (empty($this->oSearchResultPresenterFactory)) {
			$aSearchResultPresenters = $this->getSearchResultPresenters();
			$this->oSearchResultPresenterFactory = new SearchResultPresenterFactory($aSearchResultPresenters);
		}
		return $this->oSearchResultPresenterFactory;
	}

	public function initGem() {
		if (defined('ADMIN_SECTION')) {
			AddEventHandler(
					'main',
					'OnProlog',
					array(__CLASS__, 'initQuickSearch')
			);
		}
	}

	public static function initQuickSearch() {
		global $APPLICATION;
		$APPLICATION->AddHeadScript('/bitrix/js/iv.bitrixgems/QuickSearch/quickSearch.gem.js');
		$APPLICATION->AddHeadScript('/bitrix/js/iv.bitrixgems/QuickSearch/shortcut.js');
		$APPLICATION->AddHeadString(
				'<style type="text/css">
				.bitrixgems_quickSearch { position:absolute !important; }
				.bitrixgems_quickSearch .bitrixgems_quickSearch_inner { width:600px !important;}
				</style>
				<script type="text/javascript">
				if( typeof jQuery != "undefined" ){
					jQuery(function(){
						jQuery("#bx-panel-admin-toolbar-inner").append(\'<span class="bx-panel-admin-button-separator"></span><a class="bx-panel-admin-button bitrixgems_quickSearch_trigger" hidefocus="true" href="#"><span class="bx-panel-admin-button-text">Поиск</span></a>\');
						jQuery(".bitrixgems_quickSearch_trigger").click(function(){
							BitrixGem_quickSearch_toggleSwitch(this);
							jQuery(".bitrixgems_quickSearch_search").focus();
						});
						shortcut.add(
							"Shift+F",
							function(){
								jQuery(".bitrixgems_quickSearch_trigger").click();
							},
							{disable_in_input: true}
						);
					})
				}
				</script>
				'
		);
		set_include_path(get_include_path() . PATH_SEPARATOR . dirname(__FILE__) . "/classes/general/");
		set_include_path(get_include_path() . PATH_SEPARATOR . dirname(__FILE__) . "/classes/SearchResultPresenters/");
		set_include_path(get_include_path() . PATH_SEPARATOR . dirname(__FILE__) . "/classes/SearchResultCollectionProviders/");
		set_include_path(get_include_path() . PATH_SEPARATOR . dirname(__FILE__) . "/classes/SearchResultCollectionPresenters/");
		spl_autoload_extensions('.class.php, .interface.php');
		spl_autoload_register("__autoload");  //не затираем ту, которую использует Битрикс
		spl_autoload_register("QuickSearch_autoload");
		CAjax::Init(); //для jsAjaxUtil
	}

	private function search($sString) {
		$aSearchResultProviders = $this->getSearchResultCollectionProviders();
		$oResultCollection = new SearchResultCollection();
		foreach ($aSearchResultProviders as $oProvider) {
			$oResultCollection->append($oProvider->search($sString));
		}
		return $oResultCollection;
	}

	public function processAjaxRequest($aOptions) {
		$sSearchString = html_entity_decode($aOptions['queryString']);
		$oSearchResultColection = $this->search($sSearchString);
		$oSearchResultPresenterFactory = $this->getSearchResultPresenterFactory();
		$oSearchResultCollectionPresenter = new SearchResultCollectionPresenter_GroupBlocks($oSearchResultPresenterFactory);
		echo $oSearchResultCollectionPresenter->getStyle(); //@TODO: кошерно стиль вкрячить
		echo $oSearchResultCollectionPresenter->getPresentation($oSearchResultColection);
	}

	protected function getDefaultOptions() {
		return array(
			"Shortcuts" => array(
				"ShowSearchWindow" => "Shift+F"
			),
			"SearchResultCollectionPresenter" => "SearchResultCollectionPresenter_SimpleList"
		);
	}

}

function QuickSearch_autoload($sClassName) {
	$aExtensions = explode(', ', spl_autoload_extensions());
	$aPaths = explode(PATH_SEPARATOR, get_include_path());
	foreach ($aPaths as $sPath) {
		foreach ($aExtensions as $sExtension) {
			$sFile = $sPath . DIRECTORY_SEPARATOR . $sClassName . $sExtension;
			if (is_readable($sFile)) {
				require_once $sFile;
				return;
			}
		}
	}
}
?>