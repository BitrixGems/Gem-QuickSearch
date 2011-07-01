<?

class SearchResultCollection extends AppendIterator {

	public function isEmpty() {
		return iterator_count($this) == 0;
	}

	public function getGroups(){
		$aResult = array();
		foreach($this as $oItem){
			$aResult[$oItem->getType()][] = $oItem;
		}
		foreach($aResult as $sType => $aGroup){
			$aResult[$sType] = self::fromArray($aGroup);
		}
		return $aResult;
	}

	private function relevanceRank($sQuery, $sResult){
		return levenshtein($sQuery, $sResult);
	}

	public function getOrdered($sQuery){
		$aResult = array();
		foreach($this as $oItem){
			$aResult[$this->relevanceRank( $sQuery, $oItem->getTitle() )] = $oItem;
		}
		ksort($aResult);
		return self::fromArray($aResult);
	}

	public static function fromArray(array $aSearchResults) {
		if (!is_array($aSearchResults)) {
			$aSearchResults = array();
		}
		$sClassName = __CLASS__;
		$oElement = new $sClassName();
		$oElement->append(new ArrayIterator($aSearchResults));
		return $oElement;
	}

}
?>