# m2-weltpixel-lazyload

### Installation

Dependencies:
 - m2-weltpixel-backend

With composer:

```sh
$ composer config repositories.welpixel-m2-weltpixel-lazyload git git@github.com:rusdragos/m2-weltpixel-lazyload.git
$ composer require weltpixel/m2-weltpixel-lazyload:dev-master
```

Manually:

Copy the zip into app/code/WeltPixel/LazyLoading directory


#### After installation by either means, enable the extension by running following commands:

```sh
$ php bin/magento module:enable WeltPixel_LazyLoading --clear-static-content
$ php bin/magento setup:upgrade
```
