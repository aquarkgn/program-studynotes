# Mysql的root密码找回
```bash
# my.conf 配置 文件 中的 [mysqld] 块 添加如下配置
[mysqld]
skip-grant-tables


#  重启 mysql 服务
systemctl restart mysqld

# 空密码进入命令行
mysql

mysql > use mysql;
mysql > update user set password=password('newpassword') where user="root";
mysql > flush privileges;

# 修改完密码后 进入 my.conf 配置文件删除
skip-grant-tables

#  重启 mysql 服务
systemctl restart mysqld

# 使用新密码登录命令行
mysql -uroot -pnewpassword
```