# thought-magento
ThoughtMetric plugin for Magento 2

Step 1
Navigate to your Magento 2 installation folder on your server.

Step 2
Add ThoughtMetric repository to the composer sources:

```jsx

composer config repositories.thoughtmetric-magento git "https://github.com/thoughtmetric/thought-magento.git"
```

Step 3
Install ThoughtMetric plugin:

```jsx
composer require thoughtmetric/integration:dev-master
```

Step 4
Upgrade your magento modules

```jsx
bin/magento setup:upgrade
```

Step 5
Configure ThoughtMetric credentials in the admin panel:

Stores → Configuration
Change scope to 'Main Website' level
Click on ThoughtMetric reporting
Paste in your ThoughtMetric Property ID. You will need to make a ThoughtMetric account at https://thoughtmetric.io/ to get this ID.

Step 6
Clear magento cache on order to make sure that the changes will apply
```jsx
bin/magento cache:flush
```
