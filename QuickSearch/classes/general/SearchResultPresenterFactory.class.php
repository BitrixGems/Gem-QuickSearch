<?php

class SearchResultPresenterFactory {

	private $aPresenters;

	function __construct(array $aSearchResultPresenters) {
		$this->aPresenters = array();
		foreach ($aSearchResultPresenters as $oPresenter) {
			$this->aPresenters[$oPresenter->getAssociatedTypeName()] = $oPresenter;
		}
	}

	public function get($mData) {
		$sType = "";
		switch (true) {
			case is_string($mData):
				$sType = $mData;
				break;

			case is_a($mData, "ISearchResult"):
				$sType = $mData->getType();
				break;
		}

		return $this->getByAssociatedTypeName($sType);
	}

	public function getByAssociatedTypeName($sType) {
		return $this->aPresenters[$sType];
	}

}
?>
