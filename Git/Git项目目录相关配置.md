# Git WORK工作目录下常用配置和相关问题处理

#### 1. 中文乱码问题(`git status`不能显示中文)
- 原因:在默认设置下，中文文件名在工作区状态输出，中文名不能正确显示，而是显示为八进制的字符编码。
```bash
git config --global core.quotepath false
```
- 现象
![数据库设计范式](./images/work-1.jpg)