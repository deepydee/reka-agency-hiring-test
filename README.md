# PHP Developer Hire Test
## Description
Implement ToDo list.

Required functionality:
- storing lists in the database. Saving should be done without reloading the page (ajax);
- registration / authorization of users to create personal lists. Ability to edit saved lists;
- possibility to attach an image to a list item. A square preview of 150x150px should be automatically created for the image. When clicking on the preview - in a new tab opens the original image. The image can be replaced / deleted;
- the ability to tag items in the list. The number of tags can be unlimited. Tags are formed by the user, i.e. the set is arbitrary, not fixed;
- search by list items. Filtering of list items by tags (one or several);
## Additional functionality (implementation is optional)
- Ability to share the list with another user (i.e. user A can give reading access to user B;
- differentiation of access rights to the list (user A can only read, user B can read and edit);

A separate plus if you can deploy the project yourself and provide a link to it
The result should be uploaded to the public GitHub repository.

Translated with www.DeepL.com/Translator (free version)
## Stack
- PHP 8.1
- Laravel 10
- Livewire 2
- Bootstrap 5
## Pages
- Main (redirect to the task lists page)
Url: /
- User's own and shared lists
Url: /lists
- List page
Url: /lists/{id}
- Login page
Url: /login
- Register page
Url: /login

## Prerequisites

- PHP 8+, php-sqlite3, exif-extension, GD
- Composer
- Nodejs and npm
## Deploy

1. Clone git repository `git clone https://github.com/deepydee/reka-agency-hiring-test.git`
2. Go to the project's directory `cd reka-agency-hiring-test/todo`
3. Define your .env `cp .env.example .env` Within .env you can change DB_CONNECTION if you want to use mysql. By default sqlite connection is used.
4. Create a new sqlite database file `touch db/database.sqlite`
5. Install all necessary composer dependencies `composer install`
6. Install node modules `npm ci`
7. Generate a key `php artisan key:generate`
8. Run all migrations `php artisan migrate`
9. Populate DB with a wonderful fake data `php artisan db:seed`
10. Set permissions to storage and cache folders `sudo chmod -R 755 storage` `sudo chmod -R bootstrap/cache`
11. Run local server `php artisan serve`
12. Run vite bundler `npm run dev`

The project should be available at http://localhost:8000

Deploy: https://expowala.com
You can use preceeded user with these credentials: user@user.com/password
