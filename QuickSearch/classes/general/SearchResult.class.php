<?

class SearchResult implements ISearchResult {

	private $sTitle, $sUrl, $sType, $aAdditionalInfo;

	function __construct($sTitle, $sUrl, $sType, $aAdditionalInfo = array()) {
		$this->sTitle = $sTitle;
		$this->sUrl = $sUrl;
		$this->sType = $sType;
		$this->aAdditionalInfo = $aAdditionalInfo;
	}

	public function getTitle() {
		return $this->sTitle;
	}

	public function getUrl() {
		return $this->sUrl;
	}

	public function getType(){
		return $this->sType;
	}

	public function getAdditionalInfo() {
		return $this->aAdditionalInfo;
	}

}
?>