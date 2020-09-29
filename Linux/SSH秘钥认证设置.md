# SSH秘钥认证设置

## 服务器

```txt
实例：
A的IP：192.168.10.1
B的IP：192.168.10.2

将服务器A上的文件通过scp免输入密码传输的方式推送到服务器B
```

### 1. 在主机A上执行如下命令来生成SSH配对密钥

```bash
[root@CMSDB2 .ssh]# ssh-keygen -t rsa
Generating public/private rsa key pair.
Enter file in which to save the key (/root/.ssh/id_rsa):
Enter passphrase (empty for no passphrase):
Enter same passphrase again:
Your identification has been saved in /root/.ssh/id_rsa.
Your public key has been saved in /root/.ssh/id_rsa.pub.
The key fingerprint is:
SHA256:np/sMdESXazgYiQ0CG/DpagQ2REEp7gm1s3IcdblfBc root@CMSDB2
The key's randomart image is:
+---[RSA 2048]----+
|o=*+ o+  .   E.  |
|o+.+.oo.= .. .o  |
|+ ..*o + +.o.o   |
|.oo.B.  o ooo    |
|+o + o .S.o .    |
|+      . . o     |
|        o o      |
|         o +     |
|         .=      |
+----[SHA256]-----+

[root@CMSDB2 .ssh]# ls
id_rsa  id_rsa.pub
```

### 2. 将主机A的 .ssh 目录中的 id_rsa.pub 文件复制到 主机B 的 ~/.ssh/ 目录中，并追加内容到 authorized_keys 文件

```bash
# 在A上操作：文件传送
cd ~/.ssh/
scp .ssh/id_rsa.pub 192.168.10.2:/root/.ssh/id_rsa.pub

# 在B上操作：追加公钥到 认证文件
cd ~/.ssh/
cat id_rsa.pub >> authorized_keys
```

### 3. 推送一个测试文件不需要输入密码 `scp test.t 192.168.10.2:/root/`
