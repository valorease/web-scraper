ProductQueue
| id:uuid:primary | slug:string | last_sync:datetime:index |

ProductUrl
| id:uuid:primary | product_queue_id:uuid:foreign | url:string |
