# Git 常用配置和相关问题处理

#### 1. 中文乱码问题(`git status`不能显示中文)

- 原因:在默认设置下，中文文件名在工作区状态输出，中文名不能正确显示，而是显示为八进制的字符编码。

```bash
git config --global core.quotepath false
```

- 现象
![数据库设计范式](./images/work-1.jpg)

#### 2. 设置编辑器为VIM(全局生效)

```
git config --global core.editor "vim"
```

#### 3. 忽略文件权限的修改引起的状态变化

```
git config core.filemode false  // 当前版本库
git config --global core.fileMode false // 所有版本库
```

#### 4. 换行符号设置

```
git config --global core.autocrlf false
git config --global core.safecrlf true
含义：
AutoCRLF
#提交时转换为LF，检出时转换为CRLF
git config --global core.autocrlf true

#提交时转换为LF，检出时不转换
git config --global core.autocrlf input

#提交检出均不转换
git config --global core.autocrlf false
SafeCRLF
#拒绝提交包含混合换行符的文件
git config --global core.safecrlf true

#允许提交包含混合换行符的文件
git config --global core.safecrlf false

#提交包含混合换行符的文件时给出警告
git config --global core.safecrlf warn
```

### Git 稀疏检出模式

1.进入git版本库，使用命令开启 Git 稀疏检出模式

```
git config core.sparseCheckout true
```

2.编辑检出规则文件

```
vi .git/info/sparse-checkout
```

### Git过滤规则[（使用和 .gitignore 相同的匹配模式）](Git忽略提交规则gitignore配置总结.md)

```
/dir/*

!filename
```
