<?php

/**
 * @uses QueryParser
 */
class SearchResultCollectionProvider_IBlock implements ISearchResultCollectionProvider {

	private function getURL($iID, $sIBlockType) {
		return "/bitrix/admin/iblock_section_admin.php?IBLOCK_ID={$iID}&type={$sIBlockType}";
	}

	private function initSearchResultsFromCDBResult(CDBResult $aIBlocks) {
		$aResult = array();
		while ($aIBlock = $aIBlocks->Fetch()) {
			$aResult[] = new SearchResult(
							$aIBlock['NAME'],
							$this->getURL(
									$aIBlock['ID'],
									$aIBlock['IBLOCK_TYPE_ID']
							),
							"iblock"
			);
		}
		return $aResult;
	}

	public function search($sString) {
		$oParser = new QueryParser($sString);
		$aResult = array();
		if ($oParser->isNumbersPresent()) {
			$aNumbers = $oParser->getNumbers();
			$aIBlocks = CIBlock::getList(array(), array("ID" => $aNumbers));
			$aResult = array_merge($aResult, $this->initSearchResultsFromCDBResult($aIBlocks));
		}

		if ($oParser->isWordsPresent()) {
			$aWords = $oParser->getWordsSearchPatterns();
			$aIBlocks = CIBlock::getList(array(), array("NAME" => $aWords));
			$aResult = array_merge($aResult, $this->initSearchResultsFromCDBResult($aIBlocks));
		}

		$aUniqueResult = array();
		foreach($aResult as $oItem){
			$aUniqueResult[$oItem->getURL()] = $oItem;
		}

		return SearchResultCollection::fromArray($aResult);
	}

}
?>