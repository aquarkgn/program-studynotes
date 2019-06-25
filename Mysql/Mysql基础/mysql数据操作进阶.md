### 1. 关联 update
```sql
update A,B set A.name=B.name where A.id=B.id ...;
update A inner join B ON A.id=B.id set A.name=B.name where ...;
```

### 2. 关联 select
```sql
例子：   a.id同b.parent_id   存在关系 

-------------------------------------------------
  a表     id   name     b表     id   job   parent_id   
          1   张3               1     23     1   
          2   李四              2     34     2   
          3   王武              3     34     4
-------------------------------------------------- 

 1） 内连接   
  select   a.*,b.*   from   a   inner   join   b     on   a.id=b.parent_id       
  结果是     
  1   张3                   1     23     1   
  2   李四                  2     34     2   
    
  2）左连接   
  select   a.*,b.*   from   a   left   join   b     on   a.id=b.parent_id       
  结果是     
  1   张3                   1     23     1   
  2   李四                  2     34     2   
  3   王武                  null   

 
  3） 右连接   
  select   a.*,b.*   from   a   right   join   b     on   a.id=b.parent_id       
  结果是     
  1   张3                   1     23     1   
  2   李四                  2     34     2   
  null                      3     34     4   
    
 4） 完全连接：(mysql不支持全连接，可以使用 left join .... union .... right join)
  select   a.*,b.*   from   a   full   join   b     on   a.id=b.parent_id

  结果是     
  1   张3                  1     23     1   
  2   李四                 2     34     2   
  null               　　  3     34     4   
  3   王武                 null

  5） 交叉连接   
  select a.*,b.* from a cross join b;

  结果是:交叉连接，笛卡尔积，一般没有意义不使用
  1   张3                  1     23     1   
  1   张3                  2     34     2   
  1   张3                  3     34     4  
  2   李四                 1     23     1    
  2   李四                 2     34     2   
  2   李四                 3     34     4    
  3   王武                 1     23     1
  3   王武                 2     34     2 
  3   王武                 3     34     4 

```

### 3. 联合查询 UNION
- UNION 操作符合并两个或多个 SELECT 语句的结果
- UNION 内部的每个 SELECT 语句必须拥有相同数量的列。列也必须拥有相似的数据类型
- 每个 SELECT 语句中的列的顺序必须相同
- 相同的行记录会合并为一条
```sql
SELECT column_name(s) FROM table1
UNION
SELECT column_name(s) FROM table2;
```
默认情况下，UNION 操作符选取不同的值。UNION ALL 不会合并相同的行记录


### 4. 查询分析与优化
- pt-query-degest 工具分析慢查询日志
```sql
set profiles=1; # 临时记录sql语句

show profiles; # 查看日志

show profile for query 2; #单条日志时间分析

show processlist; # 查看线程列表

explain select .... ; # 分析sql语句效率
desc select .... ; # 分析sql语句效率
```

- 性能优化： 
```txt
访问数据太多会导致性能下降，尽量对查询条数做限制
确定查询是否检索大量超过需要的数据列，避免查询不需要的列和数据行
多表关联返回有需要的列，避免频繁使用*
重复使用的数据可以做缓存
合理使用索引覆盖搜索的列，这样存储引擎减少扫描行数提升效率
改变数据库表的结构，增加冗余等
超大型数据表做表分区存储
一个复杂的查询可以分割为多个小的查询，可减少锁的使用
尽量少使用子查询
使用 group by 和 distinct
```