FeatureFlagBundle
=================

A bundle to manage feature flags &amp; A/B tests

*this bundle is not production-ready - some features have not been fully developed or tested*

Dependencies
------------

* Redis using [phpredis](https://github.com/nicolasff/phpredis)

Feature flags
-------------

Features are defined in the application configuration. Features can be enabled/disabled :
* using the provided admin controller (global level),
* by your application (session level),
* using the provided front-end controller (session level),
* by adding a request parameter to the page url (page level).

A feature can have the following default state :
* disabled-always : the feature is disabled and cannot be enabled,
* disabled-hidden : the feature is disabled, and cannot be globaly enabled.
It can be enabled for development/testing purposes using the front-end session controller or the request parameter,
* disabled : the feature is disabled and can be enabled,
* enabled : the feature is enabled and can be disabled,
* enabled-always : the feature is always enabled, and cannot de disabled.

A/B Testing
-----------

A feature can have multiple variations. The list of variations is defined in your configuration file,
and it is your responsability to modify your templates according to those variations.

Variations are automatically choosed by the bundle, but can be changed :
* by your application (session level),
* using the provided front-end controller (session level),
* by adding a request parameter to the page url (page level).

The service provides a simple success/failure couting mecanism. You can also use your favorite external analytics service.

Configuration
-------------

The basic configuration is :

```yaml
socloz_feature_flag:
    options:
        redis:
            host: "host:port,host:port"
    features:
        feature_name:
            state: [enabled|enabled-always|disabled|disabled-hidden|disabled-always]
            description: used for the admin page
            variations: list of variation names
```

Other options :

* `socloz_feature_flag.options.redis.prefix`: prefix for redis keys (default : `socloz_feature_flag`)

Using feature flags
-------------------

Testing if a feature is enabled for the current user :

*PHP*

```php
if ($this->get('socloz_feature_flag.feature')->isEnabled('feature_name')) { [...] }
```

*Twig*

```twig
{% if feature_is_enabled('feature_name') %}...{% endif %}
```

Enabling/disabling a feature for the current user :

*PHP*

```php
$this->get('socloz_feature_flag.feature')->enableForUser('feature_name');
$this->get('socloz_feature_flag.feature')->disableForUser('feature_name');
```

Using A/B Testing
-----------------

Getting the feature variation for the current user :

*PHP*

```php
if ($this->get('socloz_feature_flag.abtesting')->getFeatureVariation('feature_name') == 'A') { [...] }
```

*Twig*

```twig
{% if ab_variation('feature_name') == 'A' %}...{% endif %}
```

Couting transactions :

*PHP*

```php
$this->get('socloz_feature_flag.abtesting')->begin('feature_name');
$this->get('socloz_feature_flag.abtesting')->success('feature_name');
$this->get('socloz_feature_flag.abtesting')->failure('feature_name');
```

Analytics call :

*Twig*

```twig
{{ ab_tracking('feature_name') }}
```

Admin interface
---------------

The admin interface is located at `/feature-flag/admin/features`.

To use the admin interface, you must load the admin routes in `routing.yml`:

```yaml
socloz_feature_flag_feature_admin:
    resource: "@SoclozFeatureFlagBundle/Resources/config/routing/admin.xml"
    prefix:   /feature-flag/admin
```

The twig template use Twitter Bootstrap CSS classes, but does not include Twitter Bootstrap.

You can override the layout by creating a `app/Resources/SoclozFeatureBundle/views/layout-admin.html.twig` file, such as :

```twig
{% extends "AcmeAdminBundle::layout.html.twig" %}
{% block my_block %}
    {% block socloz_feature_flag %}
    {% endblock socloz_feature_flag %}
{% endblock %}
```

You have to secure the admin route in your `security.yml` file :

```yaml
access_control:
    - { path: ^/feature-flag/admin/, role: IS_AUTHENTICATED_FULLY }
```

Use whatever role is appropriate...

Front-end controllers
---------------------

If you want to be able to switch features or variations for the current session, load the corresponding routes :

```yaml
socloz_feature_flag_feature:
  resource: "@SoclozFeatureFlagBundle/Resources/config/routing/feature.xml"
  prefix:   /feature-flag
socloz_feature_flag_ab:
  resource: "@SoclozFeatureFlagBundle/Resources/config/routing/ab_testing.xml"
  prefix:   /feature-flag
```

You can toggle a service for the current session using `/feature-flag/{feature name}/enable` and `/feature-flag/{feature name}/disable`.

You can change variation for the current session using `/feature-flag/{feature name}/change_variation/{variation}`.

The controllers redirect to the route configured as `socloz_feature_flag.options.base.redirect` (use route names).

You can display the feature state or variation using `/feature-flag/{feature name}/status` or  `/feature-flag/{feature name}/ab_status`

Page-level toggles
------------------

You can change variation or toggle features using query string parameters :

* `socloz_feature_flag_{feature name}_enabled` (0 to disable, 1 to enable)
* `socloz_feature_flag_{feature name}_variation` (add the variation name)

Google Analytics
----------------

The Google Analytics implementation expects that you use the async Google Analytics tag. If your tag looks like this :

```javascript
var _gaq = _gaq || [];
_gaq.push(['_setAccount', 'UA-XXXXX-X']);
_gaq.push(['_trackPageview']);
[...]
```

eveything is fine. If it does not look like it, analytics won't work.

A single custom variable (session level) is used for all features. Therefore, you have to use the same variation set for all your features,
and use the `socloz_feature_flag.splitter.shared_random` splitter (see below).

The custom variable can be configured using :
* `socloz_feature_flag.google_analytics.variable_id` (id of the variable, default 1)
* `socloz_feature_flag.google_analytics.variable_name` (name of the variable, default socloz_feature_flag_variation)

Advanced configuration
----------------------

All internal services can be overloaded :

*Splitter service*

Configuration : `socloz_feature_flag.services.splitter`

The splitter service chooses a variation for the current user.
The available splitters are :
* `socloz_feature_flag.splitter.random` (default) : chooses a random variation
* `socloz_feature_flag.splitter.shared_random` : tries to use the same variation for feature having the same variation set (useful for Google Anaytics)

A custom splitter can be written. It must implement `Socloz\FeatureFlagBundle\ABTesting\Splitter\SplitterInterface`.

*Transaction service*

Configuration : `socloz_feature_flag.services.transaction`

The transaction service counts succesful transactions.

There is only one transaction implemented : `socloz_feature_flag.transaction.session`.

A custom transaction service can be written. It must implement `Socloz\FeatureFlagBundle\ABTesting\Transaction\TransactionInterface`.

*State services*

Configuration : `socloz_feature_flag.services.state`

The features states for the current user are stored/recalled from a list of state services. State is written to all services, and is read from the first one.

The default is : `[socloz_feature_flag.state.request, socloz_feature_flag.state.session]`.

* `socloz_feature_flag.state.request` reads the query string and does not write anywhere,
* `socloz_feature_flag.state.session` reads/writes the session.

A custom state service can be written. It must implement `Socloz\FeatureFlagBundle\State\StateInterface`.

*Storage service*

Configuration : `socloz_feature_flag.services.storage`

The storage service stores global data (feature states, counts, ...)

The default is : `socloz_feature_flag.storage.redis`.

A custom storage service can be written. It must implement `Socloz\FeatureFlagBundle\Storage\StorageInterface`.

*Analytics service*

Configuration : `socloz_feature_flag.services.analytics`

The storage service generates the HTML code used to call the external analytics service.

The default is : `socloz_feature_flag.analytics.google_analytics`.

A custom analytics service can be written. It must implement `Socloz\FeatureFlagBundle\Analytics\AnalyticsInterface`.

License
-------

This bundle is released under the MIT license (see LICENSE).
