# Inventory Assistant API
This is the API part of Inventory Assistant.<br>
It handles all of the API calls and the connection to the database.<br>

## Getting started
### Installation
To begin with we need to have Composer, PHP and a MariaDB server installed. <br>
For laravel 11 you need to have PHP 8.2 - 8.3 installed. PHP can be installed together with XAMPP [here](https://www.apachefriends.org/download.html) and select the appropiate version.<br>
If you choose to install with XAMP it includes an Apache server and MariaDB server, which will be used by the API.<br>
To install Composer go to their download page [here](https://getcomposer.org/download/) and install the latest version.<br>

Now we have everything to begin.<br>
Now lets clone the repository

    git clone https://github.com/InventoryAssistant/InventoryAssistantAPI.git
Navigate to the new folder

    cd InventoryAssistantAPI
Install the dependencies

    composer install
To get .env file copy the .env.example file

    cp .env.example .env
Generate application key

    php artisan key:generate

Next up is setting up the search engine.<br>

### Search engine
The search engine used is Meilisearch in combinatiion with laravel Scout.<br>
You need to start with installing Laravel scout, Laravel has a guide [here](https://laravel.com/docs/11.x/scout).<br>
When installed you need to get Meilisearch up and running.<br>
To do this go [here](https://www.meilisearch.com/docs/learn/getting_started/installation) Laravel Sail can be configured to have Meilisearch, read more [here](https://laravel.com/docs/11.x/sail#meilisearch).<br>
Meilisearch also have a Laravel specific guide [here](https://www.meilisearch.com/docs/learn/cookbooks/laravel_scout).


Now that we have the search engine set up, we can go to the next step which is migrations.

### Migration
Now that we have our project configured we should be ready to migrate our models.<br>
To do so ensure the MariaDB is running and have the same IP as in the .env file.<br>
If the IPs don't match change the .env file.<br>
Before we can run our migrations we also need to be sure that we are connecting to the correct database server.<br>
To do this go to the .env file and change the following lines:

    DB_CONNECTION=sqlite
    # DB_HOST=127.0.0.1
    # DB_PORT=3306
    # DB_DATABASE=laravel
    # DB_USERNAME=root
    # DB_PASSWORD=
`DB_CONNECTION` should be changed to mysql.<br>
Make sure that they are uncommented and have the correct values. <br>

To run the migrations and have the database seeded use:

    php artisan migrate --seed
If you want to refresh the database use:

    php artisan migrate:fresh --seed

Next step is serving the API.

### Serve the API
The project is now ready to be served. <br>
This can be done multiple ways. Either by using `php artisan` or using a webserver. <br>

If you choose to use `php artisan` use the following command:

    php artisan serve
Optionally you could add a `--host` to specify the ip it should host on. You could also add `--port` to specify the post it should use.<br>
These commands and more can be found with:

    php artisan serve --help


If you choose to use a webserver it should have the web root to be the index inside the `public` directory.
