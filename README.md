#Welcome to ELK AuditLog
Installation step
- add git repository manually to composer.json (It will ask for gitlab credentials)
- run composer command `composer require hsnbd/auditlog`
```json
"repositories": [
        {
            "type": "vcs",
            "url": "https://gitlab.com/softbdltd/auditlog.git"
        }
    ]
```

***Please create issue if facing any problem.**