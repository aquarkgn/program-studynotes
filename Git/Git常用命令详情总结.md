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
