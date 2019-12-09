<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>


<nav itemscope itemtype="http://schema.org/SiteNavigationElement">
    <?if (!empty($arResult)):?>
    <ul>

        <?
        foreach($arResult as $arItem):
        if($arParams["MAX_LEVEL"] == 1 && $arItem["DEPTH_LEVEL"] > 1)
            continue;
        ?>
        <?if($arItem["SELECTED"]):?>
        <li class="  popap-show active"><a href="<?=$arItem["LINK"]?>" itemprop="url"><?=$arItem["TEXT"]?></a></li>
        <?else:?>
        <li><a href="<?=$arItem["LINK"]?>" itemprop="url"><?=$arItem["TEXT"]?></a></li>
        <?endif?>

        <?endforeach?>
    </ul>
    <?endif?>
</nav>
