<?php

class SearchResultCollectionPresenter_GroupBlocks extends SearchResultCollectionPresenter {

	public function getPresentation(SearchResultCollection $oSearchResultCollection) {
		if($oSearchResultCollection->isEmpty()):?>
		<p>К сожалению, по вашему запросу ничего не найдено</p>
		<?else:?>
			<?$aGroups = $oSearchResultCollection->getGroups();
			foreach($aGroups as $sType => $aGroup):?>
				<?$oPresenter = $this->oSearchResultPresenterFactory->get($sType);?>

					<h2 style="text-align: left;"><?=$sType?></h2>
					<?$aOrderedGroup = $aGroup->getOrdered();?>
					<?foreach($aOrderedGroup as $oSearchResult):?>
					<span class="quicksearch-groupblocks-result-item">
						<?=$oPresenter->getPresentation($oSearchResult);?>
					</span>
					<?endforeach;?>

			<?endforeach;
		endif;
	}

	public function getStyle(){
		return
				'<style type="text/css">
					.quicksearch-groupblocks-result-group {
						-moz-border-radius: 5px;
						-webkit-border-radius: 5px;
						border-radius:5px;
						border:solid lightgray 1px;
						margin:2px;
						float:left;
						width:98%;
					}
					.quicksearch-groupblocks-result-group_header {
						display:block;
						text-align:center;
						font-size:120%;
						line-height:2em;
						background-image: -moz-linear-gradient(100% 100% 90deg, #DFF1FF, #FFFFFF);
						background-image: -webkit-gradient(linear, 0% 100%, 0% 0%, from(#DFF1FF), to(#FFFFFF));
					}
					.quicksearch-groupblocks-result-item {
						display:block;
						margin:2px 6px;
					}
				</style>';
	}

}
?>