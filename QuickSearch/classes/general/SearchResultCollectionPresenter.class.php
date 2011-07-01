<?php

abstract class SearchResultCollectionPresenter {

	protected $oSearchResultPresenterFactory;

	public function __construct($oFactory) {
		$this->oSearchResultPresenterFactory = $oFactory;
	}

	abstract public function getPresentation(SearchResultCollection $oSearchResultCollection);
}
?>