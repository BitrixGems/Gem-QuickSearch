<?php
class SearchResultPresenter_User_SwitchUser implements ISearchResultPresenter{
	public function getAssociatedTypeName() {
		return "user";
	}

	public function getPresentation(ISearchResult $oSearchResult) {?>
		<a href="<?=$oSearchResult->getURL()?>"><?=$oSearchResult->getTitle()?></a>
		<?
		if(BitrixGems::isGemInstalled("SwitchUser") && $oSearchResult->getType() == 'user'){
			$aAdditionalInfo = $oSearchResult->getAdditionalInfo();
			$aGetParams = array();
			$aURL = parse_url($_SERVER['HTTP_REFERER']);
			parse_str($aURL['query'], $aGetParams);
			$aGetParams['BITRIXGEM_SWITCH_USER_TO'] = $aAdditionalInfo['ID'];
			$aURL['query'] = urldecode(http_build_query($aGetParams));
			$sUrl = "{$aURL['scheme']}://{$aURL['host']}{$aURL['path']}?{$aURL['query']}" ;?>
			<a href="<?=$sUrl?>">Переключиться</a>
		<?}
	}

}
?>
