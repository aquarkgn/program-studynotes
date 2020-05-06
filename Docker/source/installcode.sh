docker pull centos


docker run -itd -p 8443:8443 -p9020:80 -v "$PWD:/home/zyb/" --name=zyb centos 

docker run -it -p 8443:8080 -v "/home/homework:/home/homework" codercom/code-server:v2
