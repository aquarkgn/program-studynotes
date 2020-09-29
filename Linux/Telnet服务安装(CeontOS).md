## CentOS 安装 Telnet 网络访问工具包
>确保可以进行网络访问

#### 1.安装telnet相关服务
[root@localhost ~]# yum -y install xinetd telnet telnet-server

#### 2.设置root登录
[root@localhost ~]# vi /etc/xinetd.d/telnet
```
# default: on
# description: The telnet server serves telnet sessions; it uses \
#	unencrypted username/password pairs for authentication.
service telnet
{
	flags		= REUSE
	socket_type	= stream        
	wait		= no
	user		= root
	server		= /usr/sbin/in.telnetd
	log_on_failure	+= USERID
	disable		= no
}
```

### 3. 添加访问连接数
```
vi /etc/securetty
pts/0
pts/1
pts/2
pts/3
```
#### 4.在防火墙开启23端口
- Centos 6 操作
```
vi /etc/sysconfig/iptables
-A INPUT -p tcp -m state --state NEW -m tcp --dport 23 -j ACCEPT
```
- Centos 7 操作
```
firewall-cmd --permanent --zone=public --add-port=23/tcp
```


#### 5.重启telnet服务和防火墙 
- Centos 6 操作
```
/etc/init.d/xinetd restart
service iptables reload
```
- Centos 7 操作
```
# 注册并开启telnet相关服务
systemctl enable telnet.socket
systemctl start telnet.socket
systemctl enable xinetd
systemctl start xinetd

# 重新加载防火墙
firewall-cmd --reload
```

#注意 telnet 只能作为临时的登陆方案使用，本身并不安全，测试完后尽量关闭服务，关闭23端口。