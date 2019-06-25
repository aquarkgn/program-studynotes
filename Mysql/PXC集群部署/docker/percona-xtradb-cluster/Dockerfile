FROM gnofdocker/centos-basic:v7.6

# 替换中科大源并更新 apt-get 
RUN yum -y update && mkdir -p /root/pxc /backup

RUN yum -y remove maria*

# 安装 Percona-XtraDB-Cluster-5.6.43-28.32
COPY Percona-XtraDB-Cluster-5.6.43-28.32-r93-el7-x86_64-bundle.tar /root/pxc

RUN cd /root/pxc && tar -xvf Percona-XtraDB-Cluster-5.6.43-28.32-r93-el7-x86_64-bundle.tar && \
    yum -y localinstall ./*.rpm && \
    rm -rf /root/pxc && \ 
    sed -i '/my.cnf.d/!d' /etc/my.cnf

# 对外开放 3306 4567 4568 端口
EXPOSE 3306/tcp
EXPOSE 4567/tcp
EXPOSE 4568/tcp

# 在前台执行 mycat
CMD ["/bin/bash"]