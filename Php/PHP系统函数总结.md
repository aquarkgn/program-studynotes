###### memory_get_usage() 返回的单位是byt,/1024得到kb,/(1024*1024)得到mb
```php
$startMemory = memory_get_usage();

// ----- 中间代码 ------------

$endMemory = memory_get_usage();
$usedMemory = ($endMemory - $startMemory) / 1024;
echo "耗费内存: {$usedMemory} K";
```

###### microtime() 函数返回当前 Unix 时间戳的微秒数。
- 参数	当设置为 TRUE 时，规定函数应该返回一个浮点数，否则返回一个字符串。默认为 FALSE。
```php
//输出时间秒
$stratTime1 = microtime(true);

// ----- 中间代码 ------------
$stratTime2 = microtime(true);
$runtime1 = $stratTime2 - $stratTime1;
var_dump(number_format($runtime1, 10, '.', ''));

// ----- 中间代码 ------------
$stratTime3 = microtime(true);
$runtime2 = $stratTime3 - $stratTime2; 
var_dump(number_format($runtime2, 10, '.', ''));

// ----- 中间代码 ------------
$stratTime4 = microtime(true);
$runtime3 = $stratTime4 - $stratTime3;
var_dump(number_format($runtime3, 10, '.', ''));
```

```php
echo date('Y-m-d H:i:s',$currentTime);
echo "\r\n ----------------------------- \r\n ";// 2019-11-01 20:54:03


$timeBefore60s = strtotime('-60 seconds',$currentTime);
echo date('Y-m-d H:i:s',$timeBefore60s);
echo "\r\n ----------------------------- \r\n ";// 2019-11-01 20:53:03

$timeBefore60min = strtotime('-60 minute',$currentTime);
echo date('Y-m-d H:i:s',$timeBefore60min);
echo "\r\n ----------------------------- \r\n "; // 2019-11-01 19:54:03

$timeLasthours = strtotime('last hours',$currentTime);
echo date('Y-m-d H:i:s',$timeLasthours);
echo "\r\n ----------------------------- \r\n ";// 2019-11-01 19:54:03

$timeLastDay = strtotime('last day',$currentTime);
echo date('Y-m-d H:i:s',$timeLastDay);
echo "\r\n ----------------------------- \r\n "; // 2019-10-31 20:54:03

$timeBefore3Day = strtotime('-3 day',$currentTime);
echo date('Y-m-d H:i:s',$timeBefore3Day);
echo "\r\n ----------------------------- \r\n "; // 2019-10-29 20:54:03

$timeBefore1week = strtotime('-1 week',$currentTime);
echo date('Y-m-d H:i:s',$timeBefore1week); // 2019-10-25 20:54:03
```