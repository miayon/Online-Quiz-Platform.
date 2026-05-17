# Online Quiz Platform - Student Role MVC



## Run

1. Copy `web_project` to `C:\xampp\htdocs\web_project`
2. Create database `web_project` in phpMyAdmin
3. Import your main SQL schema
4. Optional: import `database/demo_student_data.sql`
5. Open:

```text
http://localhost/web_project/public/login.php
```

## MVC Rules Used

- `public/` = routes only
- `app/controllers/` = request handling and business logic
- `app/models/` = database queries with prepared statements
- `app/views/` = UI/HTML only
- `public/api/` = JSON endpoints for AJAX

## Student Login

Default user from your schema:

```text
Email: rakib@gmail.com
Password: 123456
```
