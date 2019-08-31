
# ImportWoocommerceUsers
Import woocommerce users into Magento 2
The powerfull feature of this module is that it will import users without changing their password. Already registered users from wordpress or woocommerce will be able to use their old password without having to change it.
If using woocommerce, address information will be imported as well.

I used it for a magento 2.3 and a wordpress 5.2.2 import
Should work for other versions, feel free to adapt

## How to install : 
Install into app/code folder of your magento.
```
$ bin/magento setup:upgrade
$ bin/magento setup:di:compile
$ bin/magento c:c && bin/magento c:f
```

## How to configure :

>Be sure that the database of your wordpress is accessible from your magento (over a port 3306 for example)

- Login into your magento admin
- Go to : Stores > Configuration > Idmkr > woocommerce_import
- Configure Database connection

## How to use

> Notice that both users and their address will be imported
```
$ bin/magento idmkr_importwoocommerceusers:importwoocommerceusers
```
