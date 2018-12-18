<?php
    // 超商取貨物流訂單幕後建立
    include 'Config.php';
    include 'ECPay.Logistics.Integration.php';
    try {
        $AL = new ECPayLogistics();
        $AL->HashKey = config::ECPay_HashKey;
        $AL->HashIV = config::ECPay_HashIV;
        $AL->Send = array(
            'MerchantID' => config::ECPay_MerchantID,                 //廠商編號
            'MerchantTradeNo' => 'no' . date('YmdHis'),        //交易編號 需紀錄
            'MerchantTradeDate' => date('Y/m/d H:i:s'),        //交易時間 必須參數
            'LogisticsType' => LogisticsType::CVS,                    //CVS：超商取貨 Home：宅配
            'LogisticsSubType' => LogisticsSubType::UNIMART,          /*FAMI:全家 UNIMART:統一 HILIFE:萊爾富 || FAMIC2C:全家店到店 UNIMARTC2C:統一超商交貨便 HILIFEC2C:萊爾富店到店*/
            'GoodsAmount' => 1500,                                    //商品金額 1~20000
            'CollectionAmount' => 10,                                 //代收金額 UNIMARTC2C時須跟GoodsAmount一致
            'IsCollection' => IsCollection::YES,                      //是否代收貨款
            'GoodsName' => '測試商品',
            'SenderName' => '測試寄件者',                              //勿用公司名稱 長度中文2~5 英文4~10 整體4~10
            'SenderPhone' => '0226550115',                            //若為宅配 此與手機擇一不為空
            'SenderCellPhone' => '0911222333',                        //若為UNIMARTC2C , HILIFEC2C 時不可為空 只允許09開頭 10碼
            'ReceiverName' => '測試收件者',                            //同SenderName
            'ReceiverPhone' => '0226550115',                          //同SenderPhone
            'ReceiverCellPhone' => '0933222111',                      //超商取貨時不可為空
            'ReceiverEmail' => 'test_emjhdAJr@test.com.tw',
            'TradeDesc' => '測試交易敘述',
            'ServerReplyURL' => config::ECPay_API_URL . '/ServerReplyLogisticsStatus.php',     //Server回覆網址 物流狀態由此通知
            'LogisticsC2CReplyURL' => HOME_URL . '/LogisticsC2CReplyURL.php', //當取貨門市有問題時 由此通知特店 請特店通知User重選 UNIMARTC2C時不可為空
            'Remark' => '測試備註',
            'PlatformID' => '',                                       //平台商代號
        );
        //當物流類型[LogsticsType]為『超商取貨』時
        $AL->SendExtend = array(
            'ReceiverStoreID' => '991182',                            //收件人門市代號
            'ReturnStoreID' => '991182'                               //退貨門市代號 若無設定會退回原寄件門市 僅C2C適用
        );
        // BGCreateShippingOrder()
        $Result = $AL->BGCreateShippingOrder();
        echo '<pre>' . print_r($Result, true) . '</pre>';
    } catch(Exception $e) {
        echo $e->getMessage();
    }
?>
