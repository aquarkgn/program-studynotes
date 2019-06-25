# Mycat1.6启动报NumberFormatException解决方案

## 问题现象：
  - 使用 dockerfile 文件构建了一个mycat镜像，docker run 启动后自动退出
  - dockerfile构建的镜像在测试机上运行时正常的，也可使用docker run正常启动
  - 后来在正式环境下安装的时候出现了启动后退出的问题
  - 以下为dockfile 构建代码
  ```bash
  FROM ubuntu:latest

  MAINTAINER aquarkgn

  # 替换中科大源并更新 apt-get 
  RUN sed -i 's/archive.ubuntu.com/mirrors.ustc.edu.cn/g' /etc/apt/sources.list && \
  apt-get update

  # 安装 java 服务
  RUN apt-get install -y openjdk-8-jdk && \
    echo "export JAVA_HOME=/usr/lib/jvm/java-8-openjdk-amd64" >> /etc/profile && \
    /bin/bash -c "source /etc/profile"

  # 安装 mycat 服务
  ADD http://dl.mycat.io/1.6.6.1/Mycat-server-1.6.6.1-release-20181031195535-linux.tar.gz /usr/local
  RUN cd /usr/local && tar -zxvf Mycat-server-1.6.6.1-release-20181031195535-linux.tar.gz && \
      rm -f Mycat-server-1.6.6.1-release-20181031195535-linux.tar.gz && ls -lna

  # 拷贝 mycat 配置文件
  COPY conf/rule.xml /usr/local/mycat/conf/
  COPY conf/server.xml /usr/local/mycat/conf/
  COPY conf/schema.xml /usr/local/mycat/conf/

  # 对外开放 8066 9066 端口
  EXPOSE 8066
  EXPOSE 9066

  # 在前台执行 mycat
  CMD ["/usr/local/mycat/bin/mycat", "console"]

  ```

## 排查过程：
  - 在正式环境创建一个测试容器，进入容器排查执行过程，通过查看mycat错误日志，发现有报错信息
  - /usr/local/mycat/logs/mycat.log
  ```bash
  java.lang.NumberFormatException: Size must be specified as bytes (b), kibibytes (k), mebibytes (m), gibibytes (g), tebibytes (t), or pebibytes(p). E.g. 50b, 100k, or 250m.
  Failed to parse byte string: -53215232B

  at io.mycat.memory.unsafe.utils.JavaUtils.byteStringAs(JavaUtils.java:223)

  at io.mycat.memory.unsafe.utils.JavaUtils.byteStringAsBytes(JavaUtils.java:234)

  at io.mycat.memory.unsafe.utils.MycatPropertyConf.byteStringAsBytes(MycatPropertyConf.java:92)

  at io.mycat.memory.unsafe.utils.MycatPropertyConf.getSizeAsBytes(MycatPropertyConf.java:50)

  at io.mycat.memory.unsafe.memory.mm.MemoryManager.<init>(MemoryManager.java:30)

  at io.mycat.memory.unsafe.memory.mm.ResultMergeMemoryManager.<init>(ResultMergeMemoryManager.java:15)

  at io.mycat.memory.MyCatMemory.<init>(MyCatMemory.java:126)

  at io.mycat.MycatServer.startup(MycatServer.java:345)

  at io.mycat.MycatStartup.main(MycatStartup.java:57)

  (io.mycat.MycatStartup:MycatStartup.java:62) 
  ```
  - 通过搜索百度，发现可以通过修改mycat配置文件server.xml进行处理:修改配置文件值为： 0
  ```xml
  <!--
          off heap for merge/order/group/limit      1开启   0关闭
  -->
  <property name="useOffHeapForMerge">0</property>
  ```
  - 按照网上方案修改后，重启mycat 可着正常启动

  - 疑问?
    怀疑是黄了正式系统后对进程使用的资源有限制，具体原因还在排查中

## 处理方案：
  - 修改mycat配置文件server.xml进行处理:修改配置文件值为： 0
  ```xml
  <!--
          off heap for merge/order/group/limit      1开启   0关闭
  -->
  <property name="useOffHeapForMerge">0</property>
  ```