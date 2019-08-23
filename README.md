# Cards Against Humanity Card Service

A岛讨论串: https://adnmb2.com/t/19601054

## 说明

反人类卡牌游戏是风靡欧美的知名卡牌游戏，详见 [维基百科: Cards Against Humanity](https://en.wikipedia.org/wiki/Cards_Against_Humanity)

蘑菇游戏工作室曾经制作过本游戏的中国版本，可是年久失修，卡组无法契合现今的中国网络环境（石锤过气小马）

本软件为卡牌服务，使用 Lumen 框架以 PHP 编写

## 环境要求

- PHP 7.2+
- Composer
- MySQL 5.7+

## 安装

```bash
git clone --recurse-submodules https://github.com/mushroomgame/CAH_CardService.git 
cd CAH_CardService
composer i
php artisan migrate --seed
```

将以下命令加入 crontab:

```bash
php artisan cards:clean
```

如需更新官方卡组，请执行:
```bash
git submodule update --remote
```

## 文档

Common RESTful API:

- 路由通用参数:
  - `{type}`: 卡牌类型，限定为 `whitecards` 或 `blackcards`
  - `{id}`: 卡牌ID

- 加`[]`的参数为可选项

### 获取系统信息

- method: `GET`
- route: `/`

Sample Output:
```json
{
    "status": "success",
    "version": 1
}
```

### 获取卡牌

- method: `GET`
- route: `/{type}`

Params:
- `[tags]`: 标签，JSON数组，返回数据取标签并集
    - Sample Input:
        ```json
        ["A岛", "玩家自制"]
        ```

Sample Output:
```json
[
    {
        "_id": 1,
        "text": "如果在课堂上_的话学校生活就结束了",
        "tags": "[\"玩家自制\"]",
        "plays": 0,
        "votes": 0,
        "status": 1,
        "created_at": "2019-08-23 12:07:33",
        "updated_at": "2019-08-23 12:07:33"
    },
    {
        "_id": 2,
        "text": "只有_才能_",
        "tags": "[\"玩家自制\"]",
        "plays": 0,
        "votes": 0,
        "status": 1,
        "created_at": "2019-08-23 12:07:33",
        "updated_at": "2019-08-23 12:07:33"
    }
]
```

Output Explanation:
- `_id`: 卡牌ID
- `text`: 卡牌文本
- `tags`: 卡牌标签，返回值为字符串化的JSON数组
- `plays`: 总游玩次数(白)/总投票次数(黑)
- `votes`: 获胜数(白)/支持票数(黑)
- `status`: 卡牌状态，`1` 为启用，`0` 为禁用
  - 注: 游玩/投票次数超过100且胜率或支持率低于 `3%` 的卡牌会被自动禁用，超过一个月会自动删除
- `created_at`/`updated_at`: 创建时间/更新时间

### 上传卡牌

- method: `POST`
- route: `/{type}`

Params:
- `tags`: 标签，JSON数组
    - Sample Input:
        ```json
        ["A岛", "玩家自制"]
        ```
- `text`: 卡牌文本
    - Sample Input:
        ```json
        如果在课堂上_的话学校生活就结束了
        ```

Sample Output:
```json
{
    "status": "success"
}
```

### 修改卡牌

- method: `PUT`
- route: `/{type}/{id}`

Params:

- `secret`: App密钥，在 `.env` 中定义
    - Sample Input:
        ```
        super_strong_secret
        ```
- `[tags]`: 标签，JSON数组
    - Sample Input:
        ```json
        ["A岛", "玩家自制"]
        ```
- `[text]`: 卡牌文本
    - Sample Input:
        ```
        如果在课堂上_的话学校生活就结束了
        ```
- `[status]`: 卡牌状态
    - Sample Input:
        ```
        1
        ```

Sample Output:
```json
{
    "status": "success"
}
```

### 删除卡牌

- method: `DELETE`
- route: `/{type}/{id}`

Sample Output:
```json
{
    "status": "success"
}
```

### 标记卡牌

- method: `POST`
- route: `/{type}/votes/{id}`

Params:
- `vote`: `up` 为支持(黑)/胜利(白)，`down` 为反对(黑)/失败(白)
    - Sample Input:
        ```
        up
        ```

Sample Output:
```json
{
    "status": "success"
}
```

### 通用错误返回

Sample Output:

```json
{
    "status": "failed",
    "reason": ""
}
```

Output Explanation:

`reason` 为错误说明，抛出异常会显示在此，除此之外还有:
- `{type}`错误: `'{type}' is not a valid type`
- `secret`错误: `Not authorized.`
- 数据库没有执行语句: `Nothing happened.`

## 开放接口

```
https://cah.nut.moe
```

## 更多参考

游戏终端：https://github.com/mushroomgame/cards-against-humanity

卡组文本：https://github.com/mushroomgame/Cards_Against_Humanity_ZH