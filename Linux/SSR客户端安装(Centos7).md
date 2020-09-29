# Centos7环境下ssr客户端安装
> root 权限下的安装
### 1. 安装依赖
```bash
yum -y update 
yum -y install vim curl wget python git 
```

### 2. ssr 代理服务
[sudo ssr 脚本](./source/ssr)
[root ssr 脚本](./source/ssr_root)
```bash
# 下载当前目录下的ssr脚本
wget ./source/ssr
mv ssr /usr/local/bin
chmod +x /usr/local/bin/ssr
# 安装配置
ssr install
ssr config 		# 配置文件路径 /usr/local/share/shadowsocksr/config.json
{
    "server": "0..0.0.0",	// ssr服务器ip
    "server_ipv6": "::",
    "server_port": 8080,	// ssr服务器端口
    "local_address": "127.0.0.1",
    "local_port": 1080,

    "password": "123456",		// 对应password
    "method": "none",			// 这里对应SSGlobal配置中的Encryption
    "protocol": "auth_chain_a",		//对应protocl
    "protocol_param": "",
    "obfs": "http_simple",		//对应obfs
    "obfs_param": "hello.world",	//对应obfs_param
    "speed_limit_per_con": 0,
    "speed_limit_per_user": 0,

    "additional_ports" : {}, // only works under multi-user mode
    "additional_ports_only" : false, // only works under multi-user mode
    "timeout": 120,
    "udp_timeout": 60,
    "dns_ipv6": false,
    "connect_verbose_info": 0,
    "redirect": "",
    "fast_open": false
}

# 启动/关闭/测试
ssr start
ssr stop
ssr test
```
### 3.  安装 privoxy proxychains4
```bash
yum install privoxy

vim /etc/privoxy/config
listen-address 127.0.0.1:8118 # 去除这一行的注释，没有则添加
forward-socks5 / 127.0.0.1:1080 . # 末尾添加这行代码

#添加环境变量
export http_proxy=http://127.0.0.1:8118
export https_proxy=http://127.0.0.1:8118

# 启动privoxy
systemctl start privoxy


# 测试
proxychains4 curl -sL www.google.com
```
日志文件位置： `/var/log/privoxy/logfile`
### 4. 安装使用proxychains4
```bash
yum -y install gcc automake autoconf libtool make #安装make编译工具
git clone https://github.com/rofl0r/proxychains-ng.git #下载，需要先安装git
cd proxychains-ng 
./configure #配置
make && make install #编译安装
cp ./src/proxychains.conf /etc/proxychains.conf #提取配置文件
cd .. && rm -rf proxychains-ng #删除安装文件
vim /etc/proxychains.conf #编辑配置文件（修改最后一行为 socks5 127.0.0.1 1080）这个对应你的代理地址
#socks4         127.0.0.1 9050 # 注释这一行，添加下面一行代码
socks5  127.0.0.1 1080
```

### 5. 停止代理
```bash
ssr stop
systemctl stop privoxy
#删除环境变量
unset http_proxy
unset https_proxy
```

### proxy 环境变量 和 代理测试
```bash
# privoxy默认监听端口为8118
export http_proxy=http://127.0.0.1:8118
export https_proxy=http://127.0.0.1:8118
export no_proxy=localhost

# no_proxy是不经过privoxy代理的地址
# 只能填写具体的ip、域名后缀，多个条目之间使用','逗号隔开
# 比如: export no_proxy="localhost, 192.168.1.1, ip.cn, chinaz.com"
# 访问 localhost、192.168.1.1、ip.cn、*.ip.cn、chinaz.com、*.chinaz.com 将不使用代理

# 访问各大网站，如果都有网页源码输出说明代理没问题
curl -sL www.baidu.com
curl -sL www.google.com
curl -sL www.google.com.hk
curl -sL www.google.co.jp
curl -sL www.youtube.com
curl -sL mail.google.com
curl -sL facebook.com
curl -sL twitter.com
curl -sL www.wikipedia.org

# 获取当前 IP 地址
# 如果使用 privoxy 全局模式，则应该显示 ss 服务器的 IP
# 如果使用 privoxy gfwlist模式，则应该显示本地公网 IP
curl -sL ip.chinaz.com/getip.aspx
```

### 管理脚本
**在以上部署操作完成后，应该已经可以正常科学上网了，但是如果需要进行管理时，需要分别管理ssr和privoxy，为了方便管理，这里写了一个shell脚本方便管理: ** [管理脚本ssr_manager](./source/ssr_manager)
```bash
mv ssr_manager /usr/local/bin
chmod +x ssr_manager

# 启动服务
ssr_manager start

# 关闭服务
ssr_manager stop 

# 添加开机自启动
ssr_manager autostart
```