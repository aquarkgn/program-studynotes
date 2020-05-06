## 1. 添加索引
- REQUEST
PUT /game/_doc/8
```json
{
    "name": "我的世界",
    "number": "2000",
    "version": "1.0.11"
}
```
该请求`game`尚不存在，此请求将自动创建该`game`索引，添加ID为`1`的文档`_doc`
- RESPONSE
```json
{
    "_index": "game",
    "_type": "_doc",
    "_id": "22",
    "_version": 1,
    "result": "created",
    "_shards": {
        "total": 2,
        "successful": 1,
        "failed": 0
    },
    "_seq_no": 0,
    "_primary_term": 1
}
```