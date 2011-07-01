<?php

class SearchResultPresenter_IBlockElement_SimpleLink implements ISearchResultPresenter {

	public function getAssociatedTypeName() {
		return "iblockelement";
	}

	public function getPresentation(ISearchResult $oSearchResult){?>
		<a href="<?=$oSearchResult->getURL()?>"><?=$oSearchResult->getTitle()?></a>
		<?}
}
?>
