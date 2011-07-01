<?php

class SearchResultCollectionPresenter_SimpleList extends SearchResultCollectionPresenter {

	public function getPresentation(SearchResultCollection $oSearchResultCollection) {
		if($oSearchResultCollection->isEmpty()):?>
		<p>К сожалению, по вашему запросу ничего не найдено</p>
		<?
		else:
		?>
		<ul>
			<?foreach($oSearchResultCollection as $oSearchResult):?>
			<li>
				<?
				$oPresenter = $this->oSearchResultPresenterFactory->get($oSearchResult);
				echo $oPresenter->getPresentation($oSearchResult);
				?>
			</li>
			<?endforeach;?>
		</ul>
		<?
		endif;
	}

}
?>