# 创建一个nginxlog日志分割文件，并编辑内容

#

```bash
touch /etc/logrotate.d/nginxlog
vim /etc/logrotate.d/nginxlog
```

```bash
/var/log/server/nginx/*.log {
 compress
 notifempty
        dateext
 daily
 rotate 90
 create
 sharedscripts
 postrotate
         /usr/local/server/nginx/sbin/nginx -s reload
 endscript
}
```

> 默认logrotate是通过crontab定期执行的，我们也可以手动执行查看结果

```bash
/usr/sbin/logrotate -vf /etc/logrotate.d/nginxlog
```

> 验证是否执行，查看cron的日志即可

```bash
vim /var/log/cron
```
