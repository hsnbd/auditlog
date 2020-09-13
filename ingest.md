#### create ingest pipeline for application.
```
PUT _ingest/pipeline/application_audit_pipeline
{
  "description" : "Application Audit Pipeline",
  "processors" : [
    {
        "lowercase": {
            "field": "user.office"
        }
    },
    {
      "geoip": {
        "field": "ip_addr"
      }
    },
    {
      "user_agent": {
        "field": "browser"
      }
    }
  ]
}
```

#### create log data index mappers
```
PUT my_index
{
  "mappings": {
    "properties": {
      "timestamp": {
        "type": "text"
      },  
      "action_type": {
        "type": "text"
      },
      "alert_type": {
        "type": "text"
      },
      "log_type": {
        "type": "text"
      },
      "browser": {
        "type": "text"
      },
      "ip_addr": {
        "type": "ip"
      },
      "message": {
        "type": "text"
      },
      "user": {
        "properties": {
          "id": {
            "type": "integer"
          },
          "username": {
            "type": "text"
          },
          "mobile": {
            "type": "text"
          },
          "office": {
            "type": "text"
          }
        }
      }
    }
  }
}
```


```
GET /_aliases
GET _cat/indices?v
GET /_cat/nodes?h=ip,port
GET /_cat/count?v

PUT /_snapshot/my_backup
{
  "type": "fs",
  "settings": {
    "location": "/home/hasan/Desktop/elk/ES_repository",
    "compress": true,
    "chunk_size": "10m"
  }
}
POST /_snapshot/my_backup/_verify
POST /_snapshot/my_backup/snapshot_2?wait_for_completion=true
{
  "indices": "my_index*",
  "ignore_unavailable": true,
  "include_global_state": false,
  "metadata": {
    "taken_by": "Baker",
    "taken_because": "backup before upgrading"
  }
}

GET /_snapshot

PUT /_snapshot/my_backup
{
   "indices": "my_index*",
   "ignore_unavailable": true,
   "include_global_state": false,
   "partial": false
}

PUT my-index-000001
{
  "mappings": {
    "properties": {
      "date": {
        "type":   "date",
        "format": "yyyy-MM-dd"
      }
    }
  }
}

PUT my-index-000001/_mapping
{
  "properties": {
      "age": {
        "type": "integer"
      }
    }
}
GET /my-index-000001/_mapping
GET /my-index-000001/_mapping/field/age

POST /my-index-000001/_doc/1
{
  "date": "2020-01-20",
  "age": "25",
  "dob": "2020-10-10"
}

GET /my_index/_search


GET /_security/_authenticate


PUT my_index
{
  "mappings": {
    "properties": {
      "timestamp": {
        "type": "text"
      },  
      "action_type": {
        "type": "text"
      },
      "alert_type": {
        "type": "text"
      },
      "log_type": {
        "type": "text"
      },
      "browser": {
        "type": "text"
      },
      "ip_addr": {
        "type": "ip"
      },
      "message": {
        "type": "text"
      },
      "user": {
        "properties": {
          "id": {
            "type": "integer"
          },
          "username": {
            "type": "text"
          },
          "mobile": {
            "type": "text"
          },
          "office": {
            "type": "text"
          }
        }
      }
    }
  }
}

PUT _ingest/pipeline/application_audit_pipeline
{
  "description" : "Application Audit Pipeline",
  "processors" : [
    {
        "lowercase": {
            "field": "user.office"
        }
    },
    {
      "geoip": {
        "field": "ip_addr"
      }
    },
    {
      "user_agent": {
        "field": "browser"
      }
    }
  ]
}

PUT _ingest/pipeline/user_agent
{
  "description" : "Add user agent information",
  "processors" : [
    {
      "user_agent" : {
        "field" : "agent"
      }
    }
  ]
}
PUT my-index-000001/_doc/my_id?pipeline=user_agent
{
  "agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.102 Safari/537.36"
}
GET my-index-000001/_doc/my_id
