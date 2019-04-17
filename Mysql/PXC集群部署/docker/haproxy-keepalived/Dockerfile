FROM ubuntu:latest

MAINTAINER aquark

# 更新源地址
RUN sed -i 's/archive.ubuntu.com/mirrors.ustc.edu.cn/g' /etc/apt/sources.list && \
  apt-get update

# 安装haproxy
RUN apt-get install -y haproxy

# 安装心跳监控
RUN apt-get install -y keepalived

# 拷贝配置文件
COPY conf/haproxy.cfg /etc/haproxy/haproxy.cfg
COPY conf/keepalived.conf /etc/keepalived/keepalived.conf
COPY conf/run.sh /etc/init.d/

# 设置开机启动脚本权限
RUN chmod +x /etc/init.d/run.sh

CMD [ "/etc/init.d/run.sh" ]