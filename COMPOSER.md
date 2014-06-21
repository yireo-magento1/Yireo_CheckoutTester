# Instructions for using composer

Use composer to install this extension. First make sure to initialize composer with the right settings:

    composer -n init
    composer install --no-dev

Next, modify your local composer.json file:

    {
        "require": {

        },
        "repositories":[
            {
                "packagist": false
            },
            {
                "type":"composer",
                "url":"http://satis.yireo.com"
            }
       ]
    }

Test this by running:

    composer update --no-dev

Install this extension:

    composer require --update-no-dev yireo/Yireo_CheckTester

Done.
