<?php

class SearchResultPresenter_IBlock_SimpleLink implements ISearchResultPresenter {

	public function getAssociatedTypeName() {
		return "iblock";
	}

	public function getPresentation(ISearchResult $oSearchResult){?>
		<a href="<?=$oSearchResult->getURL()?>"><?=$oSearchResult->getTitle()?></a>
		<?}
}
?>
