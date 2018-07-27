To install this module do next:

1. Edit your magento 2 composer.json file adding
to "repositories"
         {
             "type": "vcs",
             "url": "http://products.git.devoffice.com/magento/magento2_blog.git"
         }

2. Run
composer require templatemonster/module-blog

3. Run
php bin/magento module:enable TemplateMonster_Blog

4. Run
php bin/magento setup:upgrade

5. If not shown on frontend
Clear pub/static folder
rm -rf pub/static

6. Run
bin/magento setup:static-content:deploy