<?php
    // 電子地圖
    //define('HOME_URL', 'http://www.sample.com.tw/logistics_dev');
    require ('Config.php');
    require('ECPay.Logistics.Integration.php');
    try {
        $AL = new ECPayLogistics();
        $AL->Send = array(
            'MerchantID' => config::ECPay_MerchantID,
            'MerchantTradeNo' => 'no' . date('YmdHis'),
            'LogisticsSubType' => LogisticsSubType::UNIMART,
            'IsCollection' => IsCollection::NO,
            'ServerReplyURL' => config::ECPay_ReturnURL . '/ServerReplyURL.php',
            'ExtraData' => '測試額外資訊',
            'Device' => Device::PC
        );
        // CvsMap(Button名稱, Form target)
        $html = $AL->CvsMap('電子地圖(統一)');
        echo $html;
    } catch(Exception $e) {
        echo $e->getMessage();
    }
?>
