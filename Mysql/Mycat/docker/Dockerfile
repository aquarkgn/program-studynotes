FROM openjdk:8-jdk-stretch

ADD http://dl.mycat.io/1.6.6.1/Mycat-server-1.6.6.1-release-20181031195535-linux.tar.gz /usr/local
RUN cd /usr/local && tar -zxvf Mycat-server-1.6.6.1-release-20181031195535-linux.tar.gz && ls -lna

COPY conf/rule.xml /usr/local/mycat/conf/
COPY conf/server.xml /usr/local/mycat/conf/
COPY conf/schema.xml /usr/local/mycat/conf/

EXPOSE 8066
EXPOSE 9066

CMD ["/usr/local/mycat/bin/mycat", "console"]