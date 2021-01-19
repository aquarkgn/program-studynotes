添加子模块 add
默认使用项目的master分支作为子模块：
```bash
git submodule add git@git.zuoyebang.cc:pkg/phplibcommon.git library/phplibcommon
```


指定分支添加方式：
```bash
git submodule add -b conf git@git.zuoyebang.cc:pkg/phplibcommon.git library/common
```

执行add命令主要执行了：

clone一份子模块repo到主repo的git缓存目录里，例如 .git/modules/library/phplibcommon/config

创建坑位空目录，并把子模块repo的最新commit hash与之关联

在主repo根目录按需创建.gitmodules文件，记录子模块repo地址url，分支名branch(默认master), 以及坑位路径path
```bash
✗ cat .gitmodules
 
[submodule "library/phplibcommon"]
    path = library/phplibcommon
    url = git@git.zuoyebang.cc:pkg/phplibcommon.git
[submodule "library/common"]
    path = library/common
    url = git@git.zuoyebang.cc:pkg/phplibcommon.git
    branch = conf
```

初始化子模块 init
当新clone一个包含submodule的模块时，比如上述的phpdemo，其library/phplibcommon 目录(submodule所在目录)是空的。需要进行初始化：

子模块注册
➜  phpdemo git:(master) git submodule init
子模组 'library/phplibcommon'（git@git.zuoyebang.cc:pkg/phplibcommon.git）已对路径 'library/phplibcommon' 注册

创建一些本地配置 (执行完后library/phplibcommon仍然是空目录)
此时查看.git/config ，发现增加了submodule的信息：

```bash
[core]
    repositoryformatversion = 0
    filemode = true
    bare = false
    logallrefupdates = true
    ignorecase = true
    precomposeunicode = true
[remote "origin"]
    url = git@git.zuoyebang.cc:jiangshuai02/phpdemo.git
    fetch = +refs/heads/*:refs/remotes/origin/*
[branch "master"]
    remote = origin
    merge = refs/heads/master
[submodule "library/phplibcommon"]
    active = true
    url = git@git.zuoyebang.cc:pkg/phplibcommon.git
```

拉取各子模块repo
git submodule update --init
正克隆到 '/Users/jiangshuai/code/php/jiangshuai02/test/phpdemo/library/phplibcommon'...
子模组路径 'library/phplibcommon'：检出 'f6a2a7516aff06d5b2d7b7090f601f006299f3cf'


此时.git/modules/library/phplibcommon/ 下已有相关repo的branch的git信息。

library/phplibcommon 下已有对应分支的完整代码信息。

recursive
也可以在clone主repo时，通过–recursive选项也能完成上面两步工作：
```bash
git clone git@git.zuoyebang.cc:jiangshuai02/phpdemo.git --recursive
 
正克隆到 'phpdemo'...
remote: Enumerating objects: 228, done.
remote: Counting objects: 100% (228/228), done.
remote: Compressing objects: 100% (135/135), done.
remote: Total 228 (delta 59), reused 174 (delta 53)
接收对象中: 100% (228/228), 45.84 KiB | 1.48 MiB/s, 完成.
处理 delta 中: 100% (59/59), 完成.
 
子模组 'library/phplibcommon'（git@git.zuoyebang.cc:pkg/phplibcommon.git）已对路径 'library/phplibcommon' 注册
 
正克隆到 '/Users/jiangshuai/code/php/jiangshuai02/test/test/phpdemo/library/phplibcommon'...
remote: Enumerating objects: 268, done.       
remote: Counting objects: 100% (268/268), done.       
remote: Compressing objects: 100% (159/159), done.       
remote: Total 1702 (delta 146), reused 191 (delta 105)       
接收对象中: 100% (1702/1702), 1.26 MiB | 2.26 MiB/s, 完成.
处理 delta 中: 100% (759/759), 完成.
子模组路径 'library/phplibcommon'：检出 'f6a2a7516aff06d5b2d7b7090f601f006299f3cf'

```

通过日志也可以看出来 recursive 方式完成了 init + update 两步操作



更新子模块 update --remote
子模块的维护者提交了更新后，使用子模块的项目必须手动更新才能包含最新的提交。

```bash
git submodule update --remote
 
remote: Enumerating objects: 5, done.
remote: Counting objects: 100% (5/5), done.
remote: Total 3 (delta 0), reused 0 (delta 0)
展开对象中: 100% (3/3), 234 字节 | 78.00 KiB/s, 完成.
来自 git.zuoyebang.cc:jiangshuai02/mylib
   95147b7..ec0e071  dev        -> origin/dev
```

子模组路径 'library/mylib'：检出 'ec0e0719b89b4e5b24bb93f5ed6c001066944522'
会拉取子模块对应分支的最新代码 (git commit : ec0e0719b89b4e5b24bb93f5ed6c001066944522)，如有更新，占位目录的git状态会发生变化


```bash
git status
 
位于分支 master
您的分支与上游分支 'origin/master' 一致。
 
尚未暂存以备提交的变更：
  （使用 "git add <文件>..." 更新要提交的内容）
  （使用 "git restore <文件>..." 丢弃工作区的改动）
    修改：     library/mylib (新提交)
 
修改尚未加入提交（使用 "git add" 和/或 "git commit -a"）
 
 
 
diff --git a/library/mylib b/library/mylib
index ec0e071..bcc5488 160000
--- a/library/mylib
+++ b/library/mylib
@@ -1 +1 @@
-Subproject commit 9cfe29fc3abf52af9391f2e088ebf778f26370e1
+Subproject commit ec0e0719b89b4e5b24bb93f5ed6c001066944522

```

如果引用了多个子模块，只希望更新其中一个，可以通过制定 path 来实现：

git submodule update --remote library/mylib



回退更新 update
接上述示例，mylib当前 commitID 为：ec0e0719b89b4e5b24bb93f5ed6c001066944522， 使用 git submodule update --remote 后，通过git diff 可以看到 mylib 当前更新到了 bcc54882ddff0b6f4866a8836d96f61b868752d3 。

如果想回退版本，可以直接：

git submodule update
子模组路径 'library/mylib'：检出 'ec0e0719b89b4e5b24bb93f5ed6c001066944522'
回退后，git status 查看就没有对应的变更内容了。



删除子模块
删除子模块比较复杂，并没有一条命令可以直接把子模块的所有相关信息删除。

1. git rm library/common

此时查看 ls library/common 目录已被删除（源码文件）
使用 cat .gitmodules 查看，子模块信息也已被删除

2. 但是 .git/config 中仍然还有子模块的信息，需要手动删除。

3. .git/modules/library/common 也有该子模块的git信息，也需要手动删除该目录。



查看子模块信息


608b679cc7e7a9256da9c8a5590176a588ea058a library/common (remotes/origin/conf)
-ec0e0719b89b4e5b24bb93f5ed6c001066944522 library/mylib
 0a9039406f0199bd5df8fa1ca5549d91b011a8a3 library/phplibcommon (heads/master)
组成：
[+/-/U]commitID submoduleName (git branch)

+ ： commitID找不到匹配

- ： 子模块未初始化

U ： 子模块存在冲突

--cached : 指定后将打印 superproject中记录的commitID

--recursive ：指定该参数后表示会嵌套的打印子模块的信息



切换子模块分支
git submodule set-branch --branch conf library/phplibcommon


此时仅是更新了 .gitmodule 文件:

```bash
[submodule "library/phplibcommon"]
    path = library/phplibcommon
    url = git@git.zuoyebang.cc:pkg/phplibcommon.git
    branch = conf
```

library/phplibcommon 下的代码仍然对应master分支的最新commitID，如果要使用conf分支，需要同步更新代码：

git submodule update --remote


修改子模块地址
比如:

```bash
[submodule "library/common"]
    path = library/common
    url = git@git.zuoyebang.cc:inf/phplibcommon.git
```

修改library/common 的子模块的地址：

✗ git submodule set-url library/common git@git.zuoyebang.cc:pkg/phplibcommon.git
为 'library/common' 同步子模组 url
 
# 更新代码
```bash
✗ git submodule update --remote
```



进入每个子模块
当引入的子模块比较多是可以使用 git submodule foreach 命令，该命令可以进入到每个子模块中。

比如：
```bash
git submodule foreach git status
 
git submodule foreach rm README.md
```
