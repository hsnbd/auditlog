#Welcome to ELK AuditLog
Installation step
- add git repository manually to composer.json (It will ask for gitlab credentials)
```json
"repositories": [
        {
            "type": "vcs",
            "url": "https://gitlab.com/softbdltd/auditlog.git"
        }
    ],
"require": {
  "hsnbd/auditlog": "@dev"
}
```
```
if you want to fetch dev-master and dev-updated. then add "hsnbd/auditlog": "*",
```
- run composer command `composer install`

```php artisan queue:table```

```php artisan migrate```

```php artisan queue:work database --queue=listeners```


***Please create issue if facing any problem.**