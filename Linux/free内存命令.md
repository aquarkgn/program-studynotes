free -h

```txt

total——总物理内存
used——已使用内存，一般情况这个值会比较大，因为这个值包括了cache+应用程序使用的内存
free——完全未被使用的内存
shared——应用程序共享内存
buffers——缓存，主要用于目录方面,inode值等（ls大目录可看到这个值增加）
cached——缓存，用于已打开的文件
```