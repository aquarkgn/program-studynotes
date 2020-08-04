## CentOS Linux release 7.5.1804 (Core) 防火墙firewalld命令使用

```
#yum install firewalld  //安装firewalld 防火墙
```

1.开启服务

```
# systemctl start firewalld.service
```

2.关闭防火墙

```
# systemctl stop firewalld.service
```

3.开机自动启动

```
# systemctl enable firewalld.service
```

4.关闭开机制动启动

```
# systemctl disable firewalld.service
```

5.查看状态 和 开启的端口

```
#firewall-cmd --state    //running 表示运行
#firewall-cmd --zone=public --list-ports
```

6.获取活动的区域

```
#firewall-cmd --get-active-zones
```

7.获取所有支持的服务

```
#firewall-cmd --get-service
```

8.在不改变状态的条件下重新加载防火墙：

```
#firewall-cmd --reload
```

9. 查看配置结果，验证配置

```
firewall-cmd --list-all
```

10.启动设置

```
在开机时启用一个服务：systemctl enable firewalld.service
在开机时禁用一个服务：systemctl disable firewalld.service
查看服务是否开机启动：systemctl is-enabled firewalld.service
查看已启动的服务列表：systemctl list-unit-files | grep enabled
查看启动失败的服务列表：systemctl --failed
```

# firewall防火墙配置

>命令修改的是/etc/firewalld/zones/public.xml文件
>firewall-cmd命令 --permanent 参数为永久生效

1.防火墙添加服务

```
firewall-cmd --zone=public --add-service=https              //临时生效
firewall-cmd --permanent --zone=public --add-service=https  //永久
```

2.防火墙添加端口号

```
firewall-cmd --permanent --zone=public --add-port=8080-8081/tcp
firewall-cmd --permanent --zone=public --add-port=22/tcp
```

3.防火墙配置规则
>添加ipv4规则，访问来源ip为192.168.0.4/24，访问服务为ssh 同意访问

```
firewall-cmd --permanent --zone=public --add-rich-rule="rule family="ipv4"  source address="192.168.0.0/24" service name="ssh" accept"
```

4.删除规则

```
firewall-cmd --permanent --zone=public --remove-rich-rule="rule family="ipv4"  source address="192.168.0.4/24" service name="ssh" accept"
```

5.设置ipv4的访问指定端口号

```
firewall-cmd --permanent --zone=public --add-rich-rule="rule family="ipv4" source address="192.168.0.0/24" port protocol="tcp" port="22" accept"
```

# 查看生效的配置文件，直接修改配置文件后重载也可以

```
cat /etc/firewalld/zones/public.xml

<?xml version="1.0" encoding="utf-8"?>
<zone>
  <short>Public</short>
  <description>For use in public areas. You do not trust the other computers on networks to not harm your computer. Only selected incoming connections are accepted.</description>
  <service name="ssh"/>
  <service name="dhcpv6-client"/>
  <port protocol="tcp" port="22"/>
  <rule>
        <source address="192.168.6.0/24"/>
        <server name="ssh"/>
        <accept/>
  </rule>
</zone>
```
