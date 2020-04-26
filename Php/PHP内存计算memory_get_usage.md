```php
// 单位byte  
$endMemory  = memory_get_usage();
$usedMemory = $endMemory / 1024 / 1024;
echo "耗费内存: {$usedMemory} MB \r\n";

/**
* 注意： 8bit = 1byte | 1024 byte = 1kb  | 1024 kb = 1mb | 1024mb = 1gb
 */
```