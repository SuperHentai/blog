<META http-equiv="content-type" content="text/html; charset=utf-8">
<?
define('IN_SYS',true);
include 'class_weixin.php';
$wxmoli = new WX_Remote_Opera();
$wxmoli->init('pan_pureflame@qq.com','YXHFfudan1');
echo $wxmoli->token;
$a = array();
$a = $wxmoli->get_account_info();
//echo $a['fakeid']."\n".$a['nickname']."\n".$a['ghid'];

//print_r($result);
/*$msglist = $wxmoli->getimgmaterial(); //获取图文消息
for($i = 0;$i < count($msglist);$i++){
    echo $msglist[$i]['title'];
    echo $msglist[$i]['app_id']."</br>";
}*/
//群发消息
$sResult = $wxmoli->getsumcontactlist();
$sum = 0;
for($i = 0;$i < count($sResult);$i++){
    $sum  =$sum + $sResult[$i]['cnt'];
}
$page = ceil($sum/10);
for($i = 0;$i < $page;$i++){
$grest = $wxmoli->getcontactlist(10,$i);
    for($m = 0;$m < count($grest);$m++){
        //$wxmoli->sendmsg('中午好',$grest[$m]['id'],'');
        //echo $grest[$m][1]."\n";
    }

}
?>