# coincheck-leverage-history

現在のところコインチェックでレバレッジ取引の履歴がとれない（[よくある質問 | 取引履歴について](https://coincheck.com/ja/info/faq_posts/109)）のでAPIから取得する。

## Usage

https://coincheck.com/ja/api_settings でアクセスキーとシークレットキーを取得する。


```bash
$ composer install
```

する。インストールするパッケージは本家からフォークした[tmsanrinsha/coincheck-php](https://github.com/tmsanrinsha/coincheck-php)。本家ではAPIリクエストに使うnonceの設定が秒単位のUNIX時間になっているが、nonceはリクエストごとに前回よりも大きい値を指定しなくてはならず、このnonceだと1秒に一回以上リクエストできない。フォーク版ではミリ秒単位のUNIX時間を指定するようにしている。

以下のように実行する。

```bash
$ ACCESS_KEY='****' API_SECRET='****' START_TIME='2017-01-01' END_TIME='2018-01-01' REQUEST_NUM=1000 php leverage-history.php
i:10	id:*******	time:2017-12-31T22:25:13+09:00	pl:292.85925887
i:10	id:*******	time:2017-12-31T22:14:23+09:00	pl:146.93221342
...
i:500	id:*****	time:2017-01-05T21:55:02+09:00	pl:1071.79657832
損益: 12345.67890
```

ACCESS_KEYにアクセスキーを、API_SECRETにシークレットキーを設定する。

START_TIMEに取得し始める時間、END_TIMEに取得を終了する時間を設定する。END_TIMEは範囲に含まれない。ここの時間はポジションをクローズした時間。

ポジション取得APIは一回のリクエストで25件しかとれないので、何度もリクエストする必要がある。そのリクエストの上限をREQUEST_NUMで設定する。リクエストがREQUEST_NUMに達したときか、取得できるポジション履歴がなくなったら、リクエストが終了する。

出力のiはリクエスト回数-1、idはポジションに設定されたID、timeがクローズした時間、plが利益。最後に損益が出力される。
