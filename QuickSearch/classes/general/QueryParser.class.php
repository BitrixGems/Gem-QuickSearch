<?

class QueryParser {

	private $sQueryString;

	function __construct($sQueryString) {
		$this->sQueryString = $sQueryString;
	}

	private $aNumbers, $aWords;

	public function getNumbers() {
		if (!$this->aNumbers) {
			$aNumbers = array();
			preg_match_all("/\d+/", $this->sQueryString, $aNumbers);
			$aNumbers = array_shift($aNumbers);
			$this->aNumbers = $aNumbers;
		}
		return $this->aNumbers;
	}

	public function getWords() {
		if (!$this->aWords) {
			$aWords = array();
			preg_match_all("/\S+/", $this->sQueryString, $aWords);
			$aWords = array_shift($aWords);
			$this->aWords = $aWords;
		}
		return $this->aWords;
	}

	private function wrapWithPercentSymbols(&$sItem) {
		$sItem = "%{$sItem}%";
	}

	public function getWordsSearchPatterns() {
		$aWords = $this->getWords();
		if (CModule::IncludeModule("search")) {
			$aWords = array_keys(stemming(implode(" ", $aWords)));
			array_walk(&$aWords, array($this, 'wrapWithPercentSymbols'));
		}
		return $aWords;
	}

	public function isNumbersPresent() {
		$aNumbers = $this->getNumbers();
		return (bool) $aNumbers;
	}

	public function isWordsPresent() {
		$aWords = $this->getWords();
		return (bool) $aWords;
	}

}
?>