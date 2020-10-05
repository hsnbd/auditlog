#Welcome to ELK AuditLog
Installation step
- add git repository manually to composer.json (It will ask for gitlab credentials)
```json
"repositories": [
        {
            "type": "vcs",
            "url": "https://gitlab.com/softbdltd/auditlog.git"
        }
    ]
```
- run composer command `composer require hsnbd/auditlog`

```php artisan queue:table```

```php artisan migrate```

```php artisan queue:work database --queue=listeners```


***Please create issue if facing any problem.**