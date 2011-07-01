<?php

interface ISearchResultPresenter {

	public function getAssociatedTypeName();

	public function getPresentation(ISearchResult $oSearchResult);
}
?>