<?php

/**
 * @uses QueryParser
 */
class SearchResultCollectionProvider_User implements ISearchResultCollectionProvider {

	private function getURL($iID) {
		return "/bitrix/admin/user_edit.php?ID={$iID}";
	}

	private function initSearchResultsFromCDBResult(CDBResult $aUsers) {
		$aResult = array();
		while ($aUser = $aUsers->Fetch()) {
			$aResult[] = new SearchResult(
							$aUser['LOGIN'],
							$this->getURL($aUser['ID']),
							"user",
							array(
								"ID" => $aUser['ID']
							)
			);
		}
		return $aResult;
	}

	public function search($sString) {
		$oParser = new QueryParser($sString);
		$aResult = array();
		if ($oParser->isNumbersPresent()) {
			$aNumbers = $oParser->getNumbers();
			$aUsers = CUser::getList($by, $order, array("ID" => $aNumbers));
			$aResult = array_merge($aResult, $this->initSearchResultsFromCDBResult($aUsers));
		}

		if ($oParser->isWordsPresent()) {
			$aWords = $oParser->getWordsSearchPatterns();
			$aFields = array("LOGIN", "NAME", "EMAIL");
			foreach ($aWords as $sWord) {
				foreach ($aFields as $sField) {
					$aUsers = CUser::getList(
									$by,
									$order,
									array(
										$sField => $sWord,
									)
					);
					$aResult = array_merge($aResult, $this->initSearchResultsFromCDBResult($aUsers));
				}
			}
		}

		$aUniqueResult = array();
		foreach($aResult as $oItem){
			$aUniqueResult[$oItem->getURL()] = $oItem;
		}

		return SearchResultCollection::fromArray($aUniqueResult);
	}

}
?>