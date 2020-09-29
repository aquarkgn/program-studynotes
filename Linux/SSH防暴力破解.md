# SSH防暴力破解

## [SSH防暴力破解]

### 1. 设置原理

将通过SSH登录失败的IP放入到黑名单中，将管理IP放入到白名单

### 2.设置步骤

#### 2.1先把始终允许的IP填入 /etc/hosts.allow ，比如

sshd:19.16.18.1:allow
sshd:19.16.18.2:allow
经过测试，此文件相当于一个白名单，但是作用不大，一旦ip被记录到/etc/hosts.deny 黑名单中，还是以黑名单为第一优先级。

#### 2.2 脚本 secure_ssh.sh

```bsh
#! /bin/bash
cat /var/log/secure|awk '/Failed/{print $(NF-3)}'|sort|uniq -c|awk '{print $2"="$1;}' > /usr/local/bin/black.list
for i in `cat  /usr/local/bin/black.list`
do
  IP=`echo $i |awk -F= '{print $1}'`
  NUM=`echo $i|awk -F= '{print $2}'`
  if [ ${#NUM} -gt 1 ]; then
    grep $IP /etc/hosts.deny > /dev/null
    if [ $? -gt 0 ];then
      echo "sshd:$IP:deny" >> /etc/hosts.deny
    fi
  fi
done
```

#### 2.3将secure_ssh.sh脚本放入cron计划任务，每1分钟执行一次

```bsh
# crontab -e
*/1 * * * *  sh /usr/local/bin/secure_ssh.sh
```

脚本的作用：从/var/log/secure文件中提取ssh登陆错误的ip地址，如果次数达到10次(脚本中判断次数字符长度是否大于1)则将该IP写到 /etc/hosts.deny中。

### 3.恢复方案：当发生ssh访问无法使用时

#### 3.1 可以查看/etc/hosts.deny 文件中是否存在访问者的ip如果有的话进行下面的操作，没有说明不是这个设置导致的SSH无法连接

#### 3.2  vim命令进入/var/log/secure 文件中，删除文件中含有访问者ip 中和Failed字符串的所有行，保存退出

#### 3.3  重启syslog守护进程  此项很重要，因为一旦vim修改了3.2中的文件，日志就不会再进行记录了必须要重启。执行命令：/etc/init.d/rsyslog restart

#### 3.4  vim命令进入/etc/hosts.deny 删除包含有 sshd:11.64.11.5:deny 的行。其中11.64.11.5是访问者的ip，根据实际情况删除

#### 3.5  重新ssh链接限制就取消了
