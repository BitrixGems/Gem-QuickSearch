<?php

/**
 * @uses QueryParser
 */
class SearchResultCollectionProvider_IBlockElement implements ISearchResultCollectionProvider {

	private function getURL($iID, $iIBlockID, $sIBlockType) {
		return "/bitrix/admin/iblock_element_edit.php?ID={$iID}&type={$sIBlockType}&IBLOCK_ID={$iIBlockID}";
	}

	private function initSearchResultsFromCDBResult(CDBResult $aIBlockElements) {
		$aResult = array();
		while ($aIBlockElement = $aIBlockElements->Fetch()) {
			$aResult[] = new SearchResult(
							$aIBlockElement['NAME'],
							$this->getURL(
									$aIBlockElement['ID'],
									$aIBlockElement['IBLOCK_ID'],
									$aIBlockElement['IBLOCK_TYPE_ID']
							),
							"iblockelement"
			);
		}
		return $aResult;
	}

	public function search($sString) {

		$oParser = new QueryParser($sString);
		$aResult = array();

		if ($oParser->isNumbersPresent()) {
			$aNumbers = $oParser->getNumbers();
			$aIBlockElements = CIBlockElement::getList(array(), array("ID" => $aNumbers));
			$aResult = array_merge($aResult, $this->initSearchResultsFromCDBResult($aIBlockElements));
		}

		if ($oParser->isWordsPresent()) {
			$aWords = $oParser->getWordsSearchPatterns();
			$aIBlockElements = CIBlockElement::getList(array(), array("NAME" => $aWords));
			$aResult = array_merge($aResult, $this->initSearchResultsFromCDBResult($aIBlockElements));
		}

		$aUniqueResult = array();
		foreach($aResult as $oItem){
			$aUniqueResult[$oItem->getURL()] = $oItem;
		}

		return SearchResultCollection::fromArray($aResult);
	}

}
?>