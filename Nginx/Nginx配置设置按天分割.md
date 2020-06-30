# Nginx 配置设置按天分割日志

nginx日志分割是很常见的运维工作，关于这方面的文章也很多，通常无外乎两种做法：一是采用cron定期执行shell脚本对日志文件进行归档；二是使用专门日志归档工作logrotate。

第一种写shell脚本的方法用得不多，毕竟太原始。相比之下，使用logrotate则要省心得多，配置logrotate很简单。关于如何配置logrotate不是本文要讲的内容，感兴趣的话可以自行搜索。

虽然大多数Linux发行版都自带了logrotate，但在有些情况下不见得安装了logrotate，比如nginx的docker镜像、较老版本的Linux发行版。虽然我们可以使用包管理器安装logrotate，但前提是服务器能够访问互联网，企业内部的服务器可不一定能够联网。

其实我们有更简单的方法，从nginx 0.7.6版本开始access_log的路径配置可以包含变量，我们可以利用这个特性来实现日志分割。例如，我们想按天来分割日志，那么我们可以这样配置：

### 1. 使用内置函数$time_iso8601 在http块中设置$logdate

map指令通过设置默认值，保证$logdate始终有值，并且可以出现在http块中，完美地解决了if指令的问题。

```shell script
map $time_iso8601 $logdate {
  '~^(?<ymd>\d{4}-\d{2}-\d{2})' $ymd;
  default                       'date-not-found';
}

access_log logs/access-$logdate.log;

```
