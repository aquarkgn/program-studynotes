# gopath的使用

## 查看帮助 gopath 帮助

- `go gopath help`

```Html
<html>
    <table>
        <tr>
            <td>
                <!--左侧内容-->
                <div>
The Go path is used to resolve import statements.
It is implemented by and documented in the go/build package.

The GOPATH environment variable lists places to look for Go code.
On Unix, the value is a colon-separated string.
On Windows, the value is a semicolon-separated string.
On Plan 9, the value is a list.

If the environment variable is unset, GOPATH defaults
to a subdirectory named "go" in the user's home directory
($HOME/go on Unix, %USERPROFILE%\go on Windows),
unless that directory holds a Go distribution.
Run "go env GOPATH" to see the current GOPATH.

See <https://golang.org/wiki/SettingGOPATH> to set a custom GOPATH.

Each directory listed in GOPATH must have a prescribed structure:

The src directory holds source code. The path below src
determines the import path or executable name.

The pkg directory holds installed package objects.
As in the Go tree, each target operating system and
architecture pair has its own subdirectory of pkg
(pkg/GOOS_GOARCH).

If DIR is a directory listed in the GOPATH, a package with
source in DIR/src/foo/bar can be imported as "foo/bar" and
has its compiled form installed to "DIR/pkg/GOOS_GOARCH/foo/bar.a".

The bin directory holds compiled commands.
Each command is named for its source directory, but only
the final element, not the entire path. That is, the
command with source in DIR/src/foo/quux is installed into
DIR/bin/quux, not DIR/bin/foo/quux. The "foo/" prefix is stripped
so that you can add DIR/bin to your PATH to get at the
installed commands. If the GOBIN environment variable is
set, commands are installed to the directory it names instead
of DIR/bin. GOBIN must be an absolute path.

Here's an example directory layout:

    GOPATH=/home/user/go

    /home/user/go/
        src/
            foo/
                bar/               (go code in package bar)
                    x.go
                quux/              (go code in package main)
                    y.go
        bin/
            quux                   (installed command)
        pkg/
            linux_amd64/
                foo/
                    bar.a          (installed package object)

Go searches each directory listed in GOPATH to find source code,
but new packages are always downloaded into the first directory
in the list.

See <https://golang.org/doc/code.html> for an example.

GOPATH and Modules

When using modules, GOPATH is no longer used for resolving imports.
However, it is still used to store downloaded source code (in GOPATH/pkg/mod)
and compiled commands (in GOPATH/bin).

Internal Directories

Code in or below a directory named "internal" is importable only
by code in the directory tree rooted at the parent of "internal".
Here's an extended version of the directory layout above:

    /home/user/go/
        src/
            crash/
                bang/              (go code in package bang)
                    b.go
            foo/                   (go code in package foo)
                f.go
                bar/               (go code in package bar)
                    x.go
                internal/
                    baz/           (go code in package baz)
                        z.go
                quux/              (go code in package main)
                    y.go

The code in z.go is imported as "foo/internal/baz", but that
import statement can only appear in source files in the subtree
rooted at foo. The source files foo/f.go, foo/bar/x.go, and
foo/quux/y.go can all import "foo/internal/baz", but the source file
crash/bang/b.go cannot.

See <https://golang.org/s/go14internal> for details.

Vendor Directories

Go 1.6 includes support for using local copies of external dependencies
to satisfy imports of those dependencies, often referred to as vendoring.

Code below a directory named "vendor" is importable only
by code in the directory tree rooted at the parent of "vendor",
and only using an import path that omits the prefix up to and
including the vendor element.

Here's the example from the previous section,
but with the "internal" directory renamed to "vendor"
and a new foo/vendor/crash/bang directory added:

    /home/user/go/
        src/
            crash/
                bang/              (go code in package bang)
                    b.go
            foo/                   (go code in package foo)
                f.go
                bar/               (go code in package bar)
                    x.go
                vendor/
                    crash/
                        bang/      (go code in package bang)
                            b.go
                    baz/           (go code in package baz)
                        z.go
                quux/              (go code in package main)
                    y.go

The same visibility rules apply as for internal, but the code
in z.go is imported as "baz", not as "foo/vendor/baz".

Code in vendor directories deeper in the source tree shadows
code in higher directories. Within the subtree rooted at foo, an import
of "crash/bang" resolves to "foo/vendor/crash/bang", not the
top-level "crash/bang".

Code in vendor directories is not subject to import path
checking (see 'go help importpath').

When 'go get' checks out or updates a git repository, it now also
updates submodules.

Vendor directories do not affect the placement of new repositories
being checked out for the first time by 'go get': those are always
placed in the main GOPATH, never in a vendor subtree.

See <https://golang.org/s/go15vendor> for details.
                </div>
            </td>
            <td>
                <!--右侧内容-->
                <div>
Go路径用于解析导入语句。
它由go / build包实现并记录在文件中。

GOPATH环境变量列出了查找Go代码的位置。
在Unix上，该值为冒号分隔的字符串。
在Windows上，该值为分号分隔的字符串。
在计划9中，该值为列表。

如果未设置环境变量，则GOPATH默认为
到用户主目录中名为“ go”的子目录
（在Unix上为$ HOME / go，在Windows上为％USERPROFILE％\ go），
除非该目录包含Go发行版。
运行“ go env GOPATH”以查看当前的GOPATH。

请参阅<https://golang.org/wiki/SettingGOPATH来设置自定义GOPATH。>

GOPATH中列出的每个目录必须具有规定的结构：

src目录包含源代码。 src下面的路径
确定导入路径或可执行文件名称。

pkg目录包含已安装的软件包对象。
就像在Go树中一样，每个目标操作系统和
架构对具有自己的pkg子目录
（pkg / GOOS_GOARCH）。

如果DIR是GOPATH中列出的目录，则包含
DIR / src / foo / bar中的源可以导入为“ foo / bar”，并且
将其已编译的表单安装到“ DIR / pkg / GOOS_GOARCH / foo / bar.a”。

bin目录包含已编译的命令。
每个命令均以其源目录命名，但仅
最后一个元素，而不是整个路径。那就是
将DIR / src / foo / quux中带有源代码的命令安装到
DIR / bin / quux，而不是DIR / bin / foo / quux。去除了“ foo /”前缀
这样您就可以将DIR / bin添加到PATH来获取
已安装的命令。如果GOBIN环境变量是
设置后，命令将安装到其命名的目录中
DIR / bin。 GOBIN必须是绝对路径。

这是示例目录布局：

    GOPATH = / home / user / go

    / home / user / go /
        src /
            foo /
                bar /（包装栏中的转到代码）
                    x.go
                quux /（软件包主代码）
                    y.go
        箱/
            quux（已安装命令）
        公斤/
            linux_amd64 /
                foo /
                    bar.a（已安装的软件包对象）

Go搜索GOPATH中列出的每个目录以查找源代码，
但是新软件包总是下载到第一个目录中
在列表中。

有关示例，请参见<https://golang.org/doc/code.html。>

GOPATH和模块

使用模块时，GOPATH不再用于解析导入。
但是，它仍用于存储下载的源代码（在GOPATH / pkg / mod中）
和已编译的命令（在GOPATH / bin中）。

内部目录

名为“内部”的目录中或其下方的代码仅可导入
通过以“内部”的父级为根的目录树中的代码。
这是上面目录布局的扩展版本：

    / home / user / go /
        src /
            崩溃/
                bang /（在bang包中输入代码）
                    b.go
            foo /（执行foo包中的代码）
                f.go
                bar /（包装栏中的转到代码）
                    x.go
                内部/
                    baz /（在baz包中输入代码）
                        z.go
                quux /（软件包主代码）
                    y.go

z.go中的代码被导入为“ foo / internal / baz”，但是
import语句只能出现在子树的源文件中
扎根于foo。源文件foo / f.go，foo / bar / x.go和
foo / quux / y.go都可以导入“ foo / internal / baz”，但是源文件
crash / bang / b.go不能。

有关详细信息，请参见<https://golang.org/s/go14internal。>

供应商目录

Go 1.6包括对使用外部依赖项的本地副本的支持
以满足那些依赖项的导入，通常称为供应商。

名为“ vendor”的目录下的代码仅可导入
通过根源于“供应商”父级的目录树中的代码，
并且仅使用导入路径省略前缀为and
包括供应商元素。

这是上一节的示例，
但将“内部”目录重命名为“供应商”
并添加了一个新的foo / vendor / crash / bang目录：

    / home / user / go /
        src /
            崩溃/
                bang /（在bang包中输入代码）
                    b.go
            foo /（执行foo包中的代码）
                f.go
                bar /（包装栏中的转到代码）
                    x.go
                供应商/
                    崩溃/
                        bang /（在bang包中输入代码）
                            b.go
                    baz /（在baz包中输入代码）
                        z.go
                quux /（软件包主代码）
                    y.go

适用于内部的可见性规则相同，但是代码
在z.go中，将其导入为“ baz”，而不是“ foo / vendor / baz”。

供应商目录中位于源代码树阴影深处的代码
更高目录中的代码。在以foo为根的子树中，导入
“崩溃/爆炸”的名称解析为“ foo /供应商/崩溃/爆炸”，而不是
顶级“崩溃/爆炸”。

供应商目录中的代码不受导入路径的约束
检查（请参阅“转到帮助importpath”）。

当“去获取”签出或更新git存储库时，它现在也
更新子模块。

供应商目录不影响新存储库的位置
第一次被'go get'签出：这些总是
放置在主GOPATH中，而不放置在供应商子树中。

有关详细信息，请参见<https://golang.org/s/go15vendor。>
                </div>
            </td>
        </tr>
    </table>
</html>
```
