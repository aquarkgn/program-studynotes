# 查询使用场景化群发的有多少老师
select
    count(assistant_uid) total
from
    (
        select
            assistant_uid
        from
            tblWxMessageSendRecord
        where
            send_type in (0, 1, 2, 3, 4, 5, 6, 7, 13, 14)
        group by
            assistant_uid
    ) AS t
limit
    5;

# 查询使用场景化群发调用次数
select
    count(id) total
from
    tblWxMessageSendRecord
where
    send_type in (0, 1, 2, 3, 4, 5, 6, 7, 13, 14)
group by
    assistant_uid
limit
    5;

# 统计有多少学生和老师出现 连点 导致发送了两次的情况

select
    count(*)
from
    (
        select
            assistant_uid
        from
            (
                select
                    assistant_uid
                from
                    tblWxMessageSendRecord
                where
                    send_type in(21)
                    and create_time > 1587656280
                    and create_time < 1587741780
                group by
                    assistant_uid,
                    student_uids,
                    create_time
                having
                    count(create_time) > 1
            ) as t
    ) as tbl;