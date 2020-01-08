<?php
/**
 * Create From PhpStrom
 * User:  <gaonan01@zuoyebang.com>
 * CreateTime:  2019/11/15 19:21
 * FileNname: HashMap.php
 * Desciption: 暂无文件用途描述
 */

class MyHashMap {
    
    private $table = [];
    private static $CAPACITY = 8;
    private $size = 0;
    
    public function MyHashMap() {
        $this->table = [];
    }
    
    public function size() {
        return $this->size;
    }
    
    public function get($key) {
        $hash = hashCode($key);
        $i = $hash % self::$CAPACITY;
        for ($e = $this->table[$i]; $e != null; $e=$e->next) {
            if ($e->k == $key) {
                return $e->v;
            }
        }
    }
    
    public function put($key, $value) {
        $hash = hashCode($key);
        $i = $hash % self::$CAPACITY;
        
        for ($e = $this->table[$i]; $e != null; $e=$e->next) {
            if ($e->k == $key) {
                $oldValue = $e->v;
                $e->v = $value;
                return $e->v;
            }
        }

        $this->addEntry($key, $value, $i);
        return null;
    }
    
    public function getAll() {
        echo '<pre>';
        print_r($this->table);
    }
    
    //增加一个节点
    private function addEntry($key, $value, $i) {
        $entry = new Entry($key, $value, $this->table[$i]);
        $this->table[$i] = $entry;
        $this->size++;
    }
}

class Entry {
    public $k;
    public $v;
    public $next;
    
    public function Entry($k, $v, $next) {
        $this->k = $k;
        $this->v = $v;
        $this->next = $next;
    }
}

function hashCode($s) {
    $h = 0;
    $len = strlen($s);
    for ($i = 0; $i < $len; $i++) {
            $h = overflow(31 * $h + ord($s[$i]));
    }
    return $h;
}

function overflow($str) {
    $str = (string)$str;
    $hash = 0;
    $len = strlen($str);
    if ($len == 0) return $hash;
    for ($i = 0; $i < $len; $i++) {
        $h = $hash << 5;
        $h-= $hash;
        $h+= ord($str[$i]);
        $hash = $h;
        $hash&= 0xFFFFFFFF;
    }
    return $hash;
}

$myHashMap = new MyHashMap();
for ($i = 0; $i < 10; $i++) {
    $myHashMap->put("周瑜" . $i, "周瑜" . $i);
}

$myHashMap->getAll();
echo $myHashMap->get('周瑜6');