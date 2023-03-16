# JSON Editor

This application is designed for organizing authorized access to creation and editing json records of any structure. 
A user can choose any database to store records as long as the application based on the Laravel framework provides a fancy common interface for the most popular databases. Ensure that your php installation contains a driver for the database of your choice. 

### Authorization of actions
You need obtain an authorization token using the following command in console (assumed that you have an account in the system)
```
php artisan get token {email} {password} 
```

The token will take effect in the next five minutes. Feel free making the new token if you are out of time. Use it in creation and editing form. 

### Create record (/records/create)
Data textarea expects valid json string. Be aware that invalid json most probably will not be accepted by the database of your choice, the schema assumes json format only. 

### Edit record (/records/edit)
Code textarea expects valid php code. $data variable represents your data recorded in the database. 

Example: 
```
$data->list->sublist[0] = ‘hello’; 
$data[3] = true;
```

Be aware of the actual structure of your json object, using inexistent properties will affect an error.

### Index (/records) and Show (/records/{id})
You have a convenient way to look through any levels of nested objects with fancy recursive listing. 

## Nginx example configuration
```
server {
    listen 80;
    fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;

    server_name_in_redirect off;
    charset utf-8;
    root /var/www/json-editor/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ ^/public/.+\.(jpeg|jpg|JPG|JPEG|PNG|png|gif|bmp|ico|svg|tif|tiff|json|css|js|ttf|otf|webp|woff|woff2|csv|rtf|doc|docx|xls|xlsx|ppt|pptx|odf|odp|ods|odt|pdf|psd|ai|eot|eps|ps|zip|tar|tgz|gz|rar|bz2|7z|aac|m4a|mp3|mp4|ogg|wav|wma|3gp|avi|flv|m4v|mkv|mov|mpeg|mpg|wmv|exe|iso|dmg|swf|html|htm|HTML)$ {
        root /var/www/json-editor;
        add_header Cache-Control "public";
    }

    location ~ \.php$ {
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_pass php-fpm:9000;
        fastcgi_index index.php;
        include fastcgi_params;
    }

}
```
