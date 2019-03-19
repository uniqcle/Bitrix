<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();  ?>

<?php 

CJSCore::Init(array('ajax', 'jquery'));
$this->setFrameMode(true);

$this->addExternalCss("/bitrix/css/main/bootstrap.css");
$this->addExternalCss("/bitrix/css/main/font-awesome.css");

//$this->addExternalJS("/bitrix/js/main/bootstrap.js");


 ?>

<style>
  
  .row-style{
    margin-bottom: 10px;
  }
</style>

    <div class="container">

      <div class="row row-style">

        <?php foreach( $arResult["ITEMS"]  as $item ): ?>

        <div class="col-md-4">
          <div class="card mb-4 shadow-sm">
           <a href = "<?=$item['DETAIL_PAGE_URL']?>"> <img src = "<?=$item['PREVIEW_IMG']['SRC'];?>"> </a>

            <div class="card-body">
              <h4><a href = "<?=$item['DETAIL_PAGE_URL']?>"> <?php echo $item['NAME'] ?> </a> </h4>
              <div class="d-flex justify-content-between align-items-center">
              
              <small class="text-muted"><?=$item['PREVIEW_TEXT']?></small></br> 

                <div class="btn-group">



                  <?
                  // Выведем цену типа $PRICE_TYPE_ID товара с кодом $PRODUCT_ID

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
                      echo "Цена не найдена!";
                  }
                  ?>



                  <form action="" method="POST">
                      <input type="number" autocomplete = "off" value="1" min="1" max="100000000000" class="order-input order-input-small" name="order-count">
                      <input type="hidden" value="<?php echo $item['ID']; ?>" name="order-id">
                      <input type="submit" value="Заказать" class="order-btn-small">
                    </form>
                     
                    <?
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
                    ?>



                 
                </div>
                
              </div>
            </div>
          </div>
        </div>

        <?php endforeach;  ?>

      </div>
    </div>


 
 
 