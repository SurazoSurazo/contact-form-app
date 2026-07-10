# テーブル定義書

この定義書は `database/migrations` 配下の Migration、および `app/Models` 配下の Eloquent リレーションと一致する内容です。

## Eloquentリレーション

| Model | リレーション | 実装 |
| --- | --- | --- |
| Category | `contacts()` | `hasMany(Contact::class)` |
| Contact | `category()` | `belongsTo(Category::class)` |
| Contact | `tags()` | `belongsToMany(Tag::class)->withTimestamps()` |
| Tag | `contacts()` | `belongsToMany(Contact::class)->withTimestamps()` |

## users

| カラム | 型 | PK | FK | NULL | UNIQUE | DEFAULT | 備考 |
| --- | --- | --- | --- | --- | --- | --- | --- |
| id | bigint unsigned | yes | no | no | no | auto increment | `$table->id()` |
| name | varchar(255) | no | no | no | no | none | 管理ユーザー名 |
| email | varchar(255) | no | no | no | yes | none | メールアドレス |
| email_verified_at | timestamp | no | no | yes | no | null | メール認証日時 |
| password | varchar(255) | no | no | no | no | none | ハッシュ化パスワード |
| remember_token | varchar(100) | no | no | yes | no | null | `$table->rememberToken()` |
| created_at | timestamp | no | no | yes | no | null | `$table->timestamps()` |
| updated_at | timestamp | no | no | yes | no | null | `$table->timestamps()` |

## categories

| カラム | 型 | PK | FK | NULL | UNIQUE | DEFAULT | 備考 |
| --- | --- | --- | --- | --- | --- | --- | --- |
| id | bigint unsigned | yes | no | no | no | auto increment | `$table->id()` |
| content | varchar(255) | no | no | no | no | none | 問い合わせ分類名 |
| created_at | timestamp | no | no | yes | no | null | `$table->timestamps()` |
| updated_at | timestamp | no | no | yes | no | null | `$table->timestamps()` |

## tags

| カラム | 型 | PK | FK | NULL | UNIQUE | DEFAULT | 備考 |
| --- | --- | --- | --- | --- | --- | --- | --- |
| id | bigint unsigned | yes | no | no | no | auto increment | `$table->id()` |
| name | varchar(50) | no | no | no | yes | none | タグ名 |
| created_at | timestamp | no | no | yes | no | null | `$table->timestamps()` |
| updated_at | timestamp | no | no | yes | no | null | `$table->timestamps()` |

## contacts

| カラム | 型 | PK | FK | NULL | UNIQUE | DEFAULT | 備考 |
| --- | --- | --- | --- | --- | --- | --- | --- |
| id | bigint unsigned | yes | no | no | no | auto increment | `$table->id()` |
| category_id | bigint unsigned | no | categories.id | no | no | none | `ON DELETE CASCADE` |
| first_name | varchar(255) | no | no | no | no | none | 姓 |
| last_name | varchar(255) | no | no | no | no | none | 名 |
| gender | tinyint | no | no | no | no | none | 1:男性、2:女性、3:その他 |
| email | varchar(255) | no | no | no | no | none | メールアドレス |
| tel | varchar(11) | no | no | no | no | none | ハイフンなし10〜11桁 |
| address | varchar(255) | no | no | no | no | none | 住所 |
| building | varchar(255) | no | no | yes | no | null | 建物名 |
| detail | varchar(120) | no | no | no | no | none | お問い合わせ内容 |
| created_at | timestamp | no | no | yes | no | null | `$table->timestamps()` |
| updated_at | timestamp | no | no | yes | no | null | `$table->timestamps()` |

## contact_tag

| カラム | 型 | PK | FK | NULL | UNIQUE | DEFAULT | 備考 |
| --- | --- | --- | --- | --- | --- | --- | --- |
| id | bigint unsigned | yes | no | no | no | auto increment | `$table->id()` |
| contact_id | bigint unsigned | no | contacts.id | no | composite | none | `ON DELETE CASCADE`, `UNIQUE(contact_id, tag_id)` |
| tag_id | bigint unsigned | no | tags.id | no | composite | none | `ON DELETE CASCADE`, `UNIQUE(contact_id, tag_id)` |
| created_at | timestamp | no | no | yes | no | null | `$table->timestamps()` |
| updated_at | timestamp | no | no | yes | no | null | `$table->timestamps()` |

複合ユニーク制約: `UNIQUE(contact_id, tag_id)`
