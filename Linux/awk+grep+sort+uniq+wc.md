# awk+grep+sort+uniq+wc 日志分析

## 基本命令规则

[正则](https://baike.baidu.com/item/%E6%AD%A3%E5%88%99%E8%A1%A8%E8%BE%BE%E5%BC%8F/1700215?fr=aladdin)

[`awk` 命令](https://www.cnblogs.com/ginvip/p/6352157.html)

[`grep` 命令](https://www.cnblogs.com/flyor/p/6411140.html)

[`sort` 命令](https://www.cnblogs.com/GNblog/p/6932355.html)

[`uniq` 命令](https://www.cnblogs.com/ftl1012/p/uniq.html)

[`wc` 命令](https://www.cnblogs.com/peida/archive/2012/12/18/2822758.html)

```bash
awk -F" " 'BEGIN{}{}END{}'
grep
    -P 调用的perl正则
    -i 不区分大小写
    -v 显示不匹配的行
    -o 只显示一行中匹配PATTERN 的部分
    -c 显示匹配的行数
uniq
    去重：-c 临近相同的行去重并统计
sort
    排序：-r 倒叙
    -n 按照数字排序
cut 分割：
    -f 指定列
    -d " " 指定分隔符
wc 统计：
    -c 字节数
    -l 行数
    -w 单词数

```

## 命令记录

### 1. 计算日志中 ip 所在列（第二列）的访问量，取出前10

`awk -F" " '{print $2}' test.cnf | sort -n | uniq -c | sort -r| head -n 10'`
`cut -d " " -f2 test.cnf | sort -n | uniq -c | sort -r | head -n 10'`
`awk -F " " '{a[$2] +=1} END {for(i in a) printf("%d %s\n",a[i],i)}' test.cnf | sort -rn | head -n 10'`

### 2. 计算文件中 区域3 > 10 的行数

`awk -F":" '{if($3>10) NUMBER +=1;print number;}END{print $NUMBER;}' /etc/passwd`

### 3. 计算文件中每个单词出现的次数

`awk -F" " 'BEGIN{RS=" |\r|\r\n|\n"}{a[$1]++}END{for(i in a) print a[i]" "i}' test.cnf | sort -rn`

### 4. 统计日志分析

```bash
fsh 192.168.133.208 fwyymis ssh "grep -P '19:00' tt.log| grep 'err_info=Talk.*Failed' | grep 'log_type=E_SUM' | grep 'module=assistantdesk' | head -10" |awk '{match($0,/.*(user_ip=.*) local_ip.*(uri=.*?)req_start/,arr);print arr[1],arr[2]}'

#排查全部指令
fsh na fwyymis ssh 'cat tt.log | grep rankcomplexdata'

#ral-worker.log.wf 日志： 时间 logid 接口耗时
cat tt.log |awk '{match($0,/.*11-11 (15:.*) ral-worker.*logid=(.*) worker_id.*cost=(.*) talk.*/,a);print a[1]" "a[2]" "a[3]}' | sort -rnk3 |head -10

#正则匹配排序
cat tt.log |awk '{match($0,/.*11-11 (15:.*) ral-worker.*logid=(.*) worker_id.*cost=(.*) talk.*/,a);print a[1]" "a[2]" "a[3]}' | sort -rnk3 |head -10

#打印多列并逻辑计算
grep '2019:15:23' tt.log |awk '{print $4" "$7" "$9" "$24" "$29" "$30" "$31" "$33}' | awk '{if($3 > 499) print $0}'| head -10

#日志采集匹配
cat tt.log | grep --color dbname | grep --color -P 'read=\d{1,4}.*?dbname=(homework_fudao|homework_tutormis|homework_pay|homework_gnmis|homework_kunpeng|homew ork_teach resource|homework_zhibo)' |awk'{match($0,/.*read=(.*) dbname=/,a);print a[1]" "$0}' | sort -k1rn |head -10
grep -P '11-14 13:2[2-4]' tt.log| grep --color -P 'caller=Bd_DB.*method=query.*dbname=(homework_fudao|homework_tutormis|homework_pay|homework_gnmis|homework_kunpeng|homework_teach resource|homework_zhibo|dataware).*sql='

#人数统计
fsh na fwyymis ssh "grep studentinfo tt.log" | grep -oP 'uid\[\w*\]' | sort -nr | uniq -c | wc -l

#报警分析
fsh 192.168.133.208 fwyymis ssh "grep -P '19:00' tt.log | grep 'err_info=Conn.*Failed' | grep 'log_type=E_SUM' | grep 'module=assistantdesk' | head -10" |awk '{match($0,/.*.*(uri=.*?)req_start/,arr);print arr[1],arr[2]}' | sort -rnk2 | uniq -c | sort -r

fsh na fwyymis ssh "grep -P '19:00' tt.log | grep 'err_info=Conn.*Failed' | grep 'log_type=E_SUM' | grep 'module=assistantdesk'" | awk '{match($0,/.*(logid=[0-9]+).*(uri=[^0-9 ]+).*/,a);print a[1]" "a[2]}'


fsh na fwyymis ssh "grep -P '20:00' /home/homework/log/ral/ral-worker.log.wf | grep 'err_info=Talk.*Failed' | grep 'log_type=E_SUM' | grep 'module=assistantdesk'" | awk '{match($0,/.*(uri=[^0-9 ]+).*/,a);print a[1]}' | sort -rn
```

### 5.分析sql慢查询

- 1.匹配出日志中的 sql 语句关键字

```bash
cat tt.log | grep sql= | head -n 1
```

- 2.关键词内容输出：使用awk匹配出 `logid const sql=`  (分表表示 日志ID 耗时 sql语句)

>awk '{match($0,/.*logid=(.*)worker_id.*cost=(.*) talk.*sql=(.*).*/,a);print a[1]" "a[2]" "a[3]}'   //使用正则匹配出三个关键字内容并输出

```bash
cat tt.log  | grep sql= | awk '{match($0,/.*logid=(.*)worker_id.*cost=(.*) talk.*sql=(.*).*/,a);print a[1]" "a[2]" "a[3]}' | head -n 1
```

- 3.将匹配出测sql语句按照第二列 const耗时时间 进行排序

>sort -rn -k 2 -t' '  // -r 倒序 -n  依照数值的大小排序 -k  选择以哪个区间进行排序  -t<分隔字符>   指定排序时所用的栏位分隔字符。  

```bash
[rd@na-fwyymis-8-241 ral]$ cat ral-worker.log.2019110602  | grep sql= | awk '{match($0,/.*logid=(.*)worker_id.*cost=(.*) talk.*sql=(.*).*/,a);print a[1]" "a[2]" "a[3]}' | sort -rn -k 2 -t' '
```

### 6
