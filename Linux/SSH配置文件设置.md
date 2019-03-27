```bash
# 
HostKey /usr/local/openssh/etc/ssh_host_rsa_key
HostKey /usr/local/openssh/etc/ssh_host_ecdsa_key
HostKey /usr/local/openssh/etc/ssh_host_ed25519_key

# 日志等级
LogLevel INFO

# 是否允许root用户登录
PermitRootLogin yes

# 是否打开公钥验证
PubkeyAuthentication yes

# 公钥验证时调用的 key 验证文件
AuthorizedKeysFile      .ssh/authorized_keys

# 是否开启密码验证
PasswordAuthentication no

# 是否开启请求的其它系统验证方式
ChallengeResponseAuthentication no

# X11 转发开启
X11Forwarding yes
X11DisplayOffset 10
X11UseLocalhost yes

# 600s内最多使用5次
ClientAliveInterval 600
ClientAliveCountMax 5

# 开启sftp传输验证
Subsystem       sftp    /usr/local/openssh/libexec/sftp-server
```