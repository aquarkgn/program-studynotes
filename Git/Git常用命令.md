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

### git 关闭所以分页和分屏/命令别名设置

```bash
vim ~/.gitconfig

[user]
        name = GN
        email = gaonan01@zuoyebang.com
[alias]
ck = checkout
cm = commit
st = status
br = branch
sh = stash
sh = stash list
ps = push
pl = pull -r
rb = rebase
lg = log -p
[core]
        pager = cat
                   
```

# 二、常用命令

#### 版本库状态

- 0 未做任何修改，本地是分支是干净的
- 1 工作区：已经修改文件，此时文件还没有 git add 到缓存区
- 2 缓存区：git add 已完成，文件被提交到缓存区，但是还没有 git commit 形成一个提交版本
- 3 指针区：git commit 文件已提交为一个版本，生成了一个版本号，此时还没有推送到线上
- 0 git push 将本地版本推送到线上，此时回到状态 0
- s 暂存区：将所有本地修改存放到暂存区，git stash，存放后回到了状态0

#### 1.分支的管理

```bash
git branch -a       查看所有：本地和远程分支
git branch test     创建test分支
git branch -d test  删除test分支

git checkout test   切换到test分支
```

#### 2.版本回退

git reset

- --soft 缓存区和工作区都没有改变 ->2
- --mixed (default) 回退到工作区的文件修改状态 ->1
- --hard 回退到指定版本，之前做的修改全部丢失 ->0（新增的文件，就是不在版本控制内的文件不受此命令影响此命令）

```bash
git reset --soft commitid  
```

git revert 撤回到指定版本，所有的修改都会丢失，->0 (新增文件也会丢失)
> revert的版本会新增一个版本，新增的版本是指定commit的版本，版本会递增，之前提交的版本记录依然存在

```bash
git revert commitid  
```

#### 3.清理命令的使用

git clean

```bash
git clean -n        告诉你哪些文件会被删除,但是不会真正的删除文件,只是一个提醒

git clean -f　  　  删除当前目录下所有没有track过的文件 不管这些文件有没有被track过

git clean -f <path> 删除指定路径下的没有被track过的文件

git clean -df       删除当前目录下没有被track过的文件和文件夹

git clean -xf       删除当前目录下所有没有track过的文件. 包括.gitignore文件里面指定的文件夹和文件
```

#### 4.本地文件暂存处理（不想提交git，但是也不想丢失对文件的修改就需要用到这个设置）

```bash
git stash save "这里填写保存的进度日志" 对当前的暂存区和工作区状态进行保存 1->0
git stash list                          列出所有保存的进度列表。
git stash pop                           [--index] [<stash>] 恢复工作进度 0->1
git stash apply                         [--index] [<stash>] 不删除已恢复的进度，其他同git stash pop
git stash drop                          [<stash>] 删除某一个进度，默认删除最新进度
git stash clear                         删除所有进度
git stash branch <branchname> <stash>   基于进度创建分支
```

#### 5. rebase分支合并命令的使用

- merge 和rebase什么关系
git rebase 和git merge 做的事其实是一样的。它们都被设计来将一个分支的更改并入另一个分支，只不过方式有些不同。
- 如何选择使用merge还是rebase
你使用 rebase 之前需要知道的知识点都在这了。如果你想要一个干净的、线性的提交历史，没有不必要的合并提交，你应该使用 git rebase 而不是 git merge 来并入其他分支上的更改。
另一方面，如果你想要保存项目完整的历史，并且避免重写公共分支上的 commit， 你可以使用 git merge。两种选项都很好用，但至少你现在多了 git rebase 这个选择。

```bash
#交互式rebase合并最近三次提交

git checkout feature
git rebase -i HEAD~3

pick 07c5abd Introduce OpenPGP and teach basic usage
pick de9b1eb Fix PostChecker::Post#urls
pick 3e7ee36 Hey kids, stop all the highlighting
pick fa20af3 git interactive rebase, squash, amend

# Rebase 8db7e8b..fa20af3 onto 8db7e8b
#
# Commands:
# p, pick = use commit
# r, reword = use commit, but edit the commit message
# e, edit = use commit, but stop for amending
# s, squash = use commit, but meld into previous commit
# f, fixup = like "squash", but discard this commit's log message
# x, exec = run command (the rest of the line) using shell
#
# These lines can be re-ordered; they are executed from top to bottom.
#
# If you remove a line here THAT COMMIT WILL BE LOST.
#
# However, if you remove everything, the rebase will be aborted.
#
# Note that empty commits are commented out

```

#### 6.查询日志状态

```bash
git log -1 --stat
```

### 7.拉取指定分支文件到当前分支

```bash
git checkout -p /dir/phth/file.name
```

### 8. 查看带有合并详情的历史记录

```bash
git log --graph --pretty=oneline --abbrev-commit -n 4

                                                                                                                         dev
*   2206b9c (HEAD -> dev, tag: ci_xiongchao_端内外化后端开发_2021_01_19_11_54_55639, tag: 2021-01-19-17-32-40490ci_xiongchao_端内外化后端开发_2021_01_19_11_54_55639稳定版本, origin/dev, origin/HEAD, gn-test-local) Merge branch 'offline_20210119' into 'dev'
|\  
| *   a5ef1ff (origin/offline_20210119) Merge branch 'feature_qa' into 'offline_20210119'
| |\  
|/ /  
| *   278ecca (origin/feature_qa) Merge branch 'dev' into feature_qa
| |\  
| |/  
|/|   
* |   4ec94cf (tag: ci_zhangweinan_【人管】业务账号接入企微_2021_01_18_15_55_52272, tag: 2021-01-18-16-02-19954ci_zhangweinan_【人管】业务账号接入企微_2021_01_18_15_55_52272稳定版本) Merge branch 'zwn_laxinmisdocker' into 'dev'
```
