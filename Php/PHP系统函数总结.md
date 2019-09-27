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
$stratTime = microtime(true);

// ----- 中间代码 ------------

$endTime = microtime(true);
$runtime = ($endTime - $stratTime) * 1000 * 1000; //将时间转换为秒
echo "运行时间: {$runtime} 秒<br />";
```