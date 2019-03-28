<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();  ?>

<?php 

//CJSCore::Init(array('ajax', 'jquery'));
$this->setFrameMode(true);

$this->addExternalCss("/bitrix/css/main/bootstrap.css");
$this->addExternalCss("/bitrix/css/main/font-awesome.css");
//$this->addExternalJS("http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"); 

?>


     <table class="table table-hover table-bordered">
      <caption>Ранее заказанные товары</caption>
      <thead>
        <tr>
          <th scope="col">#</th>
          <th scope="col">Наименование</th>
          <th scope="col">Артикул</th>
          <th scope="col">Производитель</th>
          <th scope="col">Цена</th>
          <th scope="col">Инфо. о заказе</th>
          <th scope="col">Заказ</th>
        </tr>
      </thead>
      <tbody  >

<?php //debug( $arResult["ITEMS"]) ?>



<?php foreach( $arResult["ITEMS"]  as $item ): ?>

 


        <tr>
          <th scope="row"><a href = "<?=$item['DETAIL_PAGE_URL']?>"> <img src = "<?=$item['PREVIEW_IMG']['SRC'];?>"> </a></th>
          <td><a href = "<?=$item['DETAIL_PAGE_URL']?>"> <?php echo $item['NAME'] ?> </a> </td>
          <td><?php echo $item["PROPERTIES"]["CML2_ARTICLE"]["VALUE"] ?></td>
          <td><?php echo $item["PROPERTIES"]["CML2_MANUFACTURER"]["VALUE"] ?></td>
          <td>

          <?           
                  $db_res = CPrice::GetList(
                          array(),
                          array(
                                  "PRODUCT_ID" => $item['ID'],
                                  "CATALOG_GROUP_ID" => 2
                              )
                      );
                  if ($ar_res = $db_res->Fetch())
                  {
                      echo CurrencyFormat($ar_res["PRICE"], $ar_res["CURRENCY"]);
                  }
                  else
                  {
                      echo "Р·Р°РєР°Р·Р°С‚СЊ";
                  }
                  ?>



          </td>
          <td>
            <?php $flag = false;  ?>      

            <?php 

            $ar_res = CCatalogProduct::GetByID($item["ID"]);

            

            if( $ar_res["QUANTITY"]   > 0  ){
              $flag = true; 
              echo 'есть'; 
            }  else {
              echo '<span style = "color: red; "> нет в наличии</span> '; 
            }

            //echo $a; 

           // debug($ar_res["QUANTITY"] ); 


            ?>



          </td>
          <td>

          <?php if( $flag ){ ?>


          
          <form action="" method="POST" class = "form_ajax">
                      <input type="number" autocomplete = "off" value="1" min="1" max="1000000" class="order-input order-input-small" name="order-count">

                      <input type="hidden" id = "<?php echo $item['ID']; ?>" value="<?php echo $item['ID']; ?>" name="order-id">

                      <button type="submit" value="Заказать" class="order-btn-small">Заказать</button>

          </form>


                    
                    <?
                    /*
                    $PRODUCT_ID = $_POST['order-id'];
                    $PRODUCT_COUNT = $_POST['order-count'];
                     
                    if (CModule::IncludeModule("catalog")) {
                      if ($PRODUCT_ID && $PRODUCT_COUNT) {
                        Add2BasketByProductID(
                          $PRODUCT_ID,
                          $PRODUCT_COUNT,
                          false
                        );
                     
                        LocalRedirect("/opt/personal/cart/");
                      }
                    }
                    */ 
              ?>
            <?php } else {  ?>

           <a rel="nofollow" class="js_fancy_window" href="#fancyfeedback"><i class="icon multimage_icons"></i>Уточняйте у менеджера</a>


          <?php    } ?>


          </td>
        </tr>
<?php endforeach;  ?>

         
      </tbody>
    </table>
  


<!-- Связаться с менеджером --> 
<div id="email2friend" style="display:none;"><?
    $APPLICATION->IncludeComponent(
      "redsign:email.to.friend",
      "fancybox",
      Array(
        "ALFA_EMAIL_FROM" => "sale_opt@zoosat.tmweb.ru",
        "ALFA_MESSAGE_THEMES" => "#AUTHOR# отправил вам ссылку на сайт site.com",
        "SHOW_FIELDS" => array(
          0 => "RS_AUTHOR_NAME",
          1 => "RS_AUTHOR_COMMENT",
        ),
        "REQUIRED_FIELDS" => array(),
        "ALFA_USE_CAPTCHA" => "Y",
        "ALFA_MESSAGE_AGREE" => "Ваше сообщение успешно отправлено!",
        "ALFA_LINK" => "",
        "AJAX_MODE" => "Y",
        "AJAX_OPTION_JUMP" => "N",
        "AJAX_OPTION_STYLE" => "Y",
        "AJAX_OPTION_HISTORY" => "N",
        "CACHE_TYPE" => "A",
        "CACHE_TIME" => "3600",
        "AJAX_OPTION_ADDITIONAL" => ""
      )
    );
  ?></div>