###

```php
/**
 * formatNoticeTime
 * 将通知时间格式化 按照自然 分时日月年 计算
 * 60 秒内 的通知显示   10秒前
 * 60 分钟内 的通知显示 10分钟 前
 * 60 分钟前 - 1日内的通知显示 10：20（当前日）
 * 1日前 - 1年内的通知显示 10月2日（自然日 - 自然年）
 * 1年前的通知显示 2009年10月2日11时23分（自然年）
 * @param $pTime
 */

function formatNoticeTime($pTime){
    $toTime = time();

    $before60s = strtotime('-60 seconds',$toTime);
    $before60m = strtotime('-60 minute',$toTime);
    $toDay = strtotime(date('Y-m-d 00:00:00',$toTime),$toTime);
    $toYear = strtotime(date('Y-01-01 00:00:00',$toTime),$toTime);
    
    if($pTime >= $before60s){
        $resTime = intval(date('s',$toTime - $pTime)) .'秒前';
    }elseif($pTime >= $before60m){
        $resTime = intval(date('i',$toTime - $pTime)) .'分钟前';
    }elseif($pTime >= $toDay){
        $resTime = date('G:i',$pTime);
    }elseif($pTime >= $toYear){
        $resTime = date('n月j日',$pTime);
    }else{
        $resTime = date('Y年n月j日',$pTime);
    }

    echo $resTime;
}
echo "\r\n 2019-11-05 14:46:14 \r\n";
@formatNoticeTime(1572936374);

echo "\r\n ---------------- \r\n";

echo "\r\n 2019-11-05 14:26:14 \r\n";
@formatNoticeTime(1572935174);

echo "\r\n ---------------- \r\n";

echo "\r\n 2019-11-05 08:07:09 \r\n";
@formatNoticeTime(1572912429);

echo "\r\n ---------------- \r\n";


echo "\r\n 2019-11-02 15:16:14 \r\n";
@formatNoticeTime(1572678974);

echo "\r\n ---------------- \r\n";

echo "\r\n 2019-04-02 15:16:14 \r\n";
@formatNoticeTime(1554189374);

echo "\r\n ---------------- \r\n";

echo "\r\n 2018-04-02 15:16:14 \r\n";
@formatNoticeTime(1522653374);

echo "\r\n ---------------- \r\n";

```