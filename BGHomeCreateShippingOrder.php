<?php
    // 宅配物流訂單幕後建立
    //define('HOME_URL', 'http://www.sample.com.tw/logistics_dev');
    require('ECPay.Logistics.Integration.php');
    require ('Config.php');
    try {
        $AL = new ECPayLogistics();
        $AL->HashKey = config::ECPay_HashKey;
        $AL->HashIV = config::ECPay_HashIV;
        $AL->Send = array(
            'MerchantID' => config::ECPay_MerchantID,                 //廠商編號
            'MerchantTradeNo' => 'no' . date('YmdHis'),        //交易編號 需紀錄
            'MerchantTradeDate' => date('Y/m/d H:i:s'),        //交易時間 必須參數
            'LogisticsType' => LogisticsType::Home,                    //CVS：超商取貨 Home：宅配
            'LogisticsSubType' => LogisticsSubType::TCAT,             /*TCAT：黑貓  ECAN：宅配通*/
            'GoodsAmount' => 1500,                                    //商品金額 1~20000
            'CollectionAmount' => 10,                                 //代收金額 UNIMARTC2C時須跟GoodsAmount一致
            'IsCollection' => IsCollection::NO,                       //是否代收貨款
            'GoodsName' => '測試商品',
            'SenderName' => '測試寄件者',                              //勿用公司名稱 長度中文2~5 英文4~10 整體4~10
            'SenderPhone' => '0226550115',                            //若為宅配 此與手機擇一不為空
            'SenderCellPhone' => '0911222333',                        //若為UNIMARTC2C , HILIFEC2C 時不可為空 只允許09開頭 10碼
            'ReceiverName' => '測試收件者',                            //同SenderName
            'ReceiverPhone' => '0226550115',                          //同SenderPhone
            'ReceiverCellPhone' => '0933222111',                      //超商取貨時不可為空
            'ReceiverEmail' => 'test_emjhdAJr@test.com.tw',
            'TradeDesc' => '測試交易敘述',
            'ServerReplyURL' => config::ECPay_ReturnURL . '/ServerReplyLogisticsStatus.php',     //Server回覆網址 物流狀態由此通知
            'LogisticsC2CReplyURL' => config::ECPay_ReturnURL  . '/LogisticsC2CReplyURL.php',                  //當取貨門市有問題時 由此通知特店 請特店通知User重選 UNIMARTC2C時不可為空
            'Remark' => '測試備註',
            'PlatformID' => '',                                       //平台商代號
        );

        $AL->SendExtend = array(
            'SenderZipCode' => '11560',                               //寄件人郵遞區號
            'SenderAddress' => '台北市南港區三重路19-2號10樓D棟',       //寄件人地址
            'ReceiverZipCode' => '11560',                             //收件人郵遞區號
            'ReceiverAddress' => '台北市南港區三重路19-2號5樓D棟',      //收件人地址
            'Temperature' => Temperature::ROOM,                       //ROOM 室溫  REFRIGERATION 冷藏 FREEZE 冷凍
            'Distance' => Distance::SAME,                             //SAME 同縣市 OTHER 外縣市 ISLAND 離島
            'Specification' => Specification::CM_150,                 //貨物尺寸 有CM_60 CM_90 CM_120 CM_150
            'ScheduledDeliveryTime' => ScheduledDeliveryTime::TIME_17_20,              //預定送達時段 TIME_9_12 9~12時| TIME_12_17 12~17時| TIME_17_20 17~20時| UNLIMITED 不限時| TIME_20_21 20~21時(需限定區域)| TIME_9_17 早午 9~17| TIME_9_12_17_20 早晚 9~12 & 17~20 |TIME_13_20 午晚 13~20
            'ScheduledDeliveryDate' => date('Y/m/d', strtotime('+3 day')) // ECAN only
        );
        // BGCreateShippingOrder()
        $Result = $AL->BGCreateShippingOrder();
        echo '<pre>' . print_r($Result, true) . '</pre>';
    } catch(Exception $e) {
        echo $e->getMessage();
    }
?>
