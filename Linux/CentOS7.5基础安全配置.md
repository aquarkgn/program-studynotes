## CentOS Linux release 7.5.1804 (Core) 工具包安装
>确保可以进行网络访问

版本查看 `cat /etc/redhat-release`

### 1.更新yum源
```
[root@localhost ~]# yum update
```
### 2.安装net-tools工具包：包含ifconfig命令

```
[root@localhost ~]# yum -y install net-tools
```

### 3.安装常用命令(根据需要选择安装)
1.安装wget下载命令
```
[root@localhost src]# yum -y install wget
```
2.安装上传下载命令 rz 上传，sz filename 下载
```
[root@localhost src]# yum -y install lrzsz
```
3.安装zip压缩命令
```
[root@localhost src]# yum -y install zip unzip
```
4.安装Screen窗口命令
```
[root@localhost src]# yum -y install screen 
#1.创建一个新的会话：命令中test为新建会话的名称
[root@localhost src]# screen -S test
#2.查看已有会话
[root@localhost src]# screen -ls
#3.重连会话（在这里我们重连test）
[root@localhost src]# screen  -r test //可以是名字test也可以是session ID
#4.退出会话
[root@localhost src]# screen -d  <session ID 或者 名字>
#5.清除会话：如果由于某种原因其中一个会话死掉了（例如人为杀掉该会话），这时screen -list会显示该会话为dead状态。
[root@localhost src]# screen -wipe
```
5.安装Vim编辑器
```
[root@localhost src]# yum -y install vim 
```

## CentOS Linux release 7.5.1804 (Core) 系统安全配置
#### 1.添加管理员账户qlgl
```
useradd qlgl
passwd qlgl
```
#### 2.添加qlgl管理员账户的sudo权限
>注意使用root用户登录系统进行操作
```
visudo
root    ALL=(ALL)       ALL 
后添加一行（sudo 时无需输入密码）：
qlgl    ALL=(ALL)       NOPASSWD:ALL
```
#### 3.用户登录失败限制：失败5次禁止180秒

```
#在文件末尾添加一行代码，或修改对应的配置
vi /etc/pam.d/login 
auth required pam_tally2.so deny=5 unlock_time=180 #登录失败5次锁定180秒，不包含root
auth required pam_tally2.so deny=5 unlock_time=180 even_deny_root root_unlock_time=180 #包含root
```
#### 4.用户登录超时设置
```
#登录后无操作，600秒后自动退出登录
vi /etc/profile
export TMOUT=600
```
#### 5.用户密码复杂度设置
>参数含义：尝试次数：5  最少不同字符：3 最小密码长度：12  最少大写字母：1 最少小写字母：1 最少数字：1 最少其它字符：1
>>注意：此设置对root用户无效。

```
vi /etc/pam.d/system-auth
password    requisite     pam_cracklib.so try_first_pass retry=5 difok=3 minlen=12 ucredit=-1 lcredit=-1 dcredit=-1 ocredit=-1 type=
```
#### 6.用户密码失效超时设置
```
#此操作对root用户无效
#umask 值表示：创建的目录权限为400，文件权限为400
vi /etc/login.defs
UMASK           377 #权限基础值
PASS_MIN_DAYS   10  #最小间隔天数
PASS_MAX_DAYS   90  #最大间隔天数
PASS_WARN_AGE   15  #提前15天提醒
PASS_MIN_LEN    12  #最小密码长度

#针对root用户生效
chage -l root           #查看权限的命令
chage -M 90 -W 15 root  #修改权限的命令 最大间隔60天，提前15天提醒
```
#### 7.Linux利用script命令保存用户操作记录的方法
>#请在运行完部署脚本后执行此操作，否则会导致脚本中souce执行错误

>在/etc/profile 文件末尾添加一行script命令
```
#创建存放操作记录的目录，并给目录设置读写权限（需要其他用户有写的权限）
mkdir -p /var/log/server/script ; chmod 743 /var/log/server/script

#打开/etc/profile 文件末尾添加一行script命令
vi /etc/profile
exec script -t 2>/var/log/server/script/$USER-$UID-`date +%F-%T`.date -a -q -f /var/log/server/script/$USER-$UID-`date +%F-%T`.log
```
#### 8.telnet配置（当调试ssh服务可以临时开启，使用过后关闭相关服务）
>如果不存在telnet命令，查看[telnet服务安装](https://blog.csdn.net/zhouzme/article/details/46461177)
```
systemctl status telnet.socket  #查看状态
systemctl start telnet.socket   #开始
systemctl stop telnet.socket    #关闭
systemctl status xinetd         #查看状态
systemctl start xinetd          #开始
systemctl stop xinetd           #关闭
```

#### 9.设置SSH安全配置（使用telnet连接服务器成功后再去配置ssh，否则一旦配置失败ssh重启生效后服务器就连不上了）
###### 1.查看ssh版本
>如果ssh版本是7.0以下需要升级到最新版本
>>版本升级文档：[SSH升级到7.5](http://www.cnblogs.com/GNblog/p/7126966.html)
```
[root@localhost ~]# ssh -V
OpenSSH_7.4p1, OpenSSL 1.0.2k-fips  26 Jan 2017
```
###### 2.设置ssh使用秘钥登录，禁止密码登录

```
[root@localhost ~]# vi /etc/ssh/sshd_config
PubkeyAuthentication yes                        #开启公钥登录验证
AuthorizedKeysFile      .ssh/authorized_keys    #默认的秘钥存放文件
PasswordAuthentication no                       #禁止密码登录
```
###### 3.检查修改后的配饰是否生效

```
[root@localhost ~]# sshd -t -f /etc/ssh/sshd_config #没有任何提示，说明配置文件没有错误，有提示根据提示查找错误处理方案
```
###### 4.重启SSH服务，使配置生效（centos7.5系统）

```
systemctl status sshd.service   #查看状态
systemctl start sshd.service    #启动
systemctl stop sshd.service     #停止
systemctl restart sshd.service  #重启
```
>>>以上设置完后就可以生成SSH秘钥，然后使用SSH工具进行测试，具体方法可以查看
[设置 SSH 通过密钥登录](http://www.cnblogs.com/GNblog/p/7127555.html)

#### 10.打开SELinux配置
>注意: 如果需要跑部署脚本，需要先关闭，部署完业务后再来操作此步骤

```
#查看SELinux状态 Enforcing 开启 Disabled 关闭，如果不是Enforcing开启状态，需要手动开启，设置完后需要重启服务器后生效
[root@localhost ~]# getenforce
Enforcing
[root@localhost ~]# vi /etc/selinux/config
SELINUX=Enforcing
```