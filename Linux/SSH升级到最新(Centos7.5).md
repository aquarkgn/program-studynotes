# Centos 7.5 SSH 升级到最新并开启相关配置
>一下为升级到7.9作为操作示例

> 升级前请开启 [Telnet](./CeontOS安装Telnet服务.md)相关服务，以避免升级失败后导致无法进行服务器连接

#### 1.删除旧版ssh包 危险操作，不删除也可以安装，建议跳过此操作。
```bash
rpm -e `rpm -qa | grep openssh`
```
#### 2.安装依赖包

```bash
yum -y install gcc zlib-devel openssl-devel pam-devel
```

#### 3.安装zlib依赖包
```bash
wget -c http://zlib.net/zlib-1.2.11.tar.gz

tar zxvf zlib-1.2.11.tar.gz

cd zlib-1.2.11

./configure --prefix=/usr/local/zlib && make && make install

```

#### 4.安装ssl依赖包

```bash
wget -c https://www.openssl.org/source/openssl-1.1.1a.tar.gz

tar zxvf openssl-1.1.1a.tar.gz

cd openssl-1.1.1a

./config --prefix=/usr/local/openssl && make && make install
```


#### 5.安装ssh包
> 也可以直接去下载最新版ssh包[下载地址](http://mirror.internode.on.net/pub/OpenBSD/OpenSSH/portable/)，但是需要注意最新的ssh包对以上依赖包的版本要求
```bash
wget -c http://mirror.internode.on.net/pub/OpenBSD/OpenSSH/portable/openssh-7.9p1.tar.gz

tar zxvf openssh-7.9p1.tar.gz

cd openssh-7.9p1

./configure --prefix=/usr/local/openssh --bindir=/usr/bin --sbindir=/usr/sbin --sysconfdir=/etc/ssh --with-ssl-dir=/usr/local/openssl/bin --with-zlib=/usr/local/zlib --with-md5-passwords  && make && make install
```
**注意事项： `./configure` 配置命令的参数 需要指定配置文件安装目录 `--sysconfdir=/etc/ssh`，如果不指定安装目录的话默认配置文件目录跟随安装在软件目录`--prefix=/usr/local/openssh`中即：`/usr/local/openssh/etc/`**
#### 6.查看是否安装成功
```bash
ssh -V
OpenSSH_7.9p1, OpenSSL 1.0.2k-fips  26 Jan 2017
```

#### 7.SSH配置文件设置（以下为常用配置），并测试是否配置正确
设置SSH配置文件，根据需要开启相关启动项
`vim /etc/ssh/sshd_config`
```bash
# SSH version 1 使用的私钥 HostKey /etc/ssh/ssh_host_key　     
# SSH version 2 使用的 RSA 私钥 HostKey /etc/ssh/ssh_host_rsa_key　
# SSH version 2 使用的 DSA 私钥 HostKey /etc/ssh/ssh_host_dsa_key　
HostKey /etc/ssh/ssh_host_key
HostKey /etc/ssh/ssh_host_rsa_key
HostKey /etc/ssh/ssh_host_dsa_key

# 当有人使用 SSH 登入系统的时候，SSH会记录信息
SyslogFacility AUTH
# 登录记录的等级 INFO 全部
LogLevel INFO

# 是否允许root用户登录
PermitRootLogin yes

# 是否开启密码验证
PasswordAuthentication no 

# 上面那一项如果设定为 yes 的话，这一项就最好设定
PermitEmptyPasswords no　　

# 是否打开公钥验证
PubkeyAuthentication yes

# 公钥验证时调用 授权秘钥key 的存放验证文件
AuthorizedKeysFile      .ssh/authorized_keys

# 挑战任何的密码认证！所以，任何 login.conf规定的认证方式，均可适用！
ChallengeResponseAuthentication no

# 有关在 X-Window 底下使用的相关设定
X11Forwarding yes
X11DisplayOffset 10
X11UseLocalhost yes

# 600s内最多使用5次
ClientAliveInterval 600
ClientAliveCountMax 5

# 开启sftp传输验证
Subsystem       sftp    /usr/local/openssh/libexec/sftp-server

# 登入后是否显示出一些信息呢？例如上次登入的时间、地点等，预设是 yes ，但是，如果为了安全，可以考虑改为 no ！
PrintMotd no

# 显示上次登入的信息！可以啊！预设也是 yes ！
PrintLastLog yes

# 一般而言，如果设定这项目的话，那么 SSH Server 会传送KeepAlive 的讯息给 Client 端，以确保两者的联机正常！在这个情况下，任何一端死掉后， SSH 可以立刻知道！而不会有僵尸程序的发生！
KeepAlive yes
```
**使用`sshd -t` 命令测试配置文件是否有语法错误，如果有错误的话会输出错误信息，没有错误则什么都不会输出**
```
# 测试命令
sshd -t
# 指定配置文件测试命令，包含有错误输出
sshd -t -f /etc/ssh/sshd_config
/etc/ssh/sshd_config line 3: Unsupported option UsePAM
```

#### 8.添加服务到开机启动，并重启服务
- 使用chkconfig服务管理开机启动项（主要在Centos 6中使用，Centos 7也可以使用此服务进行管理）
```bash
# 备份旧的sshd启动文件（没有可忽略）
mv -a /etc/init.d/sshd /etc/init.d/sshd.lod_$(date +%Y-%m-%d_%H-%M)

#拷贝新的sshd启动文件到开机启动目录
cp -a contrib/redhat/sshd.init /etc/init.d/sshd

# 天机ssh开机服务到启动项
chkconfig -add sshd
chkconfig sshd on

# sshd服务 重启
/etc/init.d/sshd restart
```
- 使用systemctl服务管理开机启动项（Centos 7默认使用此服务进行管理）
```bash
#开机时启用sshd服务
systemctl enable sshd.service
#开机时禁用一个服务
systemctl disable sshd.service
#查看服务是否开机启动
systemctl is-enabled sshd.service

# 查看已启动的服务列表
systemctl list-unit-files | grep enabled

#查看启动失败的服务列表
systemctl --failed

# sshd服务 重启
systemctl restart sshd.service
# sshd服务 状态查看
systemctl status sshd.service
```
```
# 当出现无法启动的时，通过查看 sshd.service 开机启动配置文件示例来处理问题
vim /usr/lib/systemd/system/sshd.service
[Unit]
Description=OpenSSH server daemon
Documentation=man:sshd(8) man:sshd_config(5)
After=network.target

[Service]
ExecStart=/usr/sbin/sshd $OPTIONS
ExecReload=/bin/kill -HUP $MAINPID
KillMode=process
Restart=on-failure
RestartSec=42s

[Install]
WantedBy=multi-user.target
```