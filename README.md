# devnix/belfiore-code

[![Join the chat at https://gitter.im/DevNIX/belfiore-code](https://badges.gitter.im/DevNIX/belfiore-code.svg)](https://gitter.im/DevNIX/belfiore-code?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)

Official Italian Belfiore code list (cadastral code) which represents a 
[comune](https://en.wikipedia.org/wiki/Comune).

## Update

To update the database, you have to clone the project and install the
`dev-dependencies`. Then, the console will be available to download and dump 
the normalized datasets:

```
composer install
bin/console update
```

and you are golden.

Anyway, if you think that the crawled data is outdated, please 
[file an issue](https://github.com/devnix/belfiore-code/issues/new). I will 
update the new data ASAP.

# Usage

## Installation

### PHP

```
composer require devnix/belfiore-code
```

## Serialized data

You can get the up to date serialized database of comunes and
foreign regions in CSV, JSON, XML and YAML inside the `dist/` folder in any
language.

## API

### PHP

There is a `Devnix\BelfioreCode\Collection\ComuneCollection` and a 
`Devnix\BelfioreCode\Collection\ComuneCollection` to get an `ArrayCollection`
filled with both databases. This enables you to directly iterate through them
like an array, or even perform queries of columns.

#### Querying

You can fetch a comune by its `registry_code` (also know as  *cadastral code* or
*belfiore code*).

```php
<?php

use Devnix\BelfioreCode\Collection\ComuneCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Expr\Comparison;

$comunes = new ComuneCollection();

$criteria = new Criteria();
$criteria
    ->where(new Comparison('registry_code', Comparison::IS, 'A001'))
;

var_dump($comunes->matching($criteria)));
```

This would get you a new collection with the matching registry codes. As you can
see there is a comune called "ABANO" discontinued in 1924-11-13, and an active
comune called "ABANO TERME":
```
object(Devnix\BelfioreCode\Collection\ComuneCollection)#49 (1) {
  ["elements":"Doctrine\Common\Collections\ArrayCollection":private]=>
  array(2) {
    [0]=>
    array(18) {
      ["id"]=>
      string(5) "12560"
      ["institution_date"]=>
      string(10) "1866-11-19"
      ["end_date"]=>
      string(10) "1924-11-13"
      ["istat_id"]=>
      string(6) "028001"
      ["registry_code"]=>
      string(4) "A001"
      ["name_it"]=>
      string(5) "ABANO"
      ["name_transliterated"]=>
      string(5) "ABANO"
      ["alternative_name"]=>
      string(0) ""
      ["alternative_name_transliterated"]=>
      string(0) ""
      ["anpr_id"]=>
      string(2) "28"
      ["istat_province_id"]=>
      string(3) "028"
      ["istat_region_id"]=>
      string(2) "05"
      ["istat_prefecture_id"]=>
      string(0) ""
      ["status"]=>
      string(12) "discontinued"
      ["provincial_code"]=>
      string(2) "PD"
      ["source"]=>
      string(0) ""
      ["last_update"]=>
      string(10) "2016-06-17"
      ["istat_discontinued_code"]=>
      string(6) "028500"
    }
    [1]=>
    array(18) {
      ["id"]=>
      string(1) "1"
      ["institution_date"]=>
      string(10) "1924-11-14"
      ["end_date"]=>
      string(10) "9999-12-31"
      ["istat_id"]=>
      string(6) "028001"
      ["registry_code"]=>
      string(4) "A001"
      ["name_it"]=>
      string(11) "ABANO TERME"
      ["name_transliterated"]=>
      string(11) "ABANO TERME"
      ["alternative_name"]=>
      string(0) ""
      ["alternative_name_transliterated"]=>
      string(0) ""
      ["anpr_id"]=>
      string(2) "28"
      ["istat_province_id"]=>
      string(3) "028"
      ["istat_region_id"]=>
      string(2) "05"
      ["istat_prefecture_id"]=>
      string(2) "PD"
      ["status"]=>
      string(6) "active"
      ["provincial_code"]=>
      string(2) "PD"
      ["source"]=>
      string(0) ""
      ["last_update"]=>
      string(10) "2016-06-17"
      ["istat_discontinued_code"]=>
      string(0) ""
    }
  }
}
```

You may want to find an active comune by his `registry_code`. To archieve this,
just play with the
[Doctrine Collections docs](https://www.doctrine-project.org/projects/doctrine-collections/en/1.6/index.html#introduction)

```php
<?php

use Devnix\BelfioreCode\Collection\ComuneCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Expr\Comparison;

$comunes = new ComuneCollection();

$criteria = new Criteria();
$criteria
    ->where(new Comparison('registry_code', Comparison::IS, 'A001'))
    ->andWhere(new Comparison('status', Comparison::IS, 'active'))
;

var_dump($comunes->matching($criteria)));
```

and it will grab for you your desired criteria:

```
object(Devnix\BelfioreCode\Collection\ComuneCollection)#55 (1) {
  ["elements":"Doctrine\Common\Collections\ArrayCollection":private]=>
  array(1) {
    [1]=>
    array(18) {
      ["id"]=>
      string(1) "1"
      ["institution_date"]=>
      string(10) "1924-11-14"
      ["end_date"]=>
      string(10) "9999-12-31"
      ["istat_id"]=>
      string(6) "028001"
      ["registry_code"]=>
      string(4) "A001"
      ["name_it"]=>
      string(11) "ABANO TERME"
      ["name_transliterated"]=>
      string(11) "ABANO TERME"
      ["alternative_name"]=>
      string(0) ""
      ["alternative_name_transliterated"]=>
      string(0) ""
      ["anpr_id"]=>
      string(2) "28"
      ["istat_province_id"]=>
      string(3) "028"
      ["istat_region_id"]=>
      string(2) "05"
      ["istat_prefecture_id"]=>
      string(2) "PD"
      ["status"]=>
      string(6) "active"
      ["provincial_code"]=>
      string(2) "PD"
      ["source"]=>
      string(0) ""
      ["last_update"]=>
      string(10) "2016-06-17"
      ["istat_discontinued_code"]=>
      string(0) ""
    }
  }
}
```

#### Ordering

Ordering can be done too through Doctrine Collections. Please refer to their 
[docs](https://www.doctrine-project.org/projects/doctrine-collections/en/1.6/expressions.html#expressions)
to see the available API:

```php
<?php

use Devnix\BelfioreCode\Collection\ComuneCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Expr\Comparison;

$comunes = new ComuneCollection();

$criteria = new Criteria();
$criteria->orderBy(['last_update' => Criteria::ASC]);

var_dump($comunes->matching($criteria)));
```


# Roadmap

- [ ] Write unit tests to verify that the data sources maintain the same format
- [ ] Write unit tests to cover all classes
- [ ] Write documentation of each column available in English
- [ ] Investigate to run the update command automatically using Github Actions
once in a month

# Contributing

You can contribute by forking the project and doing a pull request. Please, do 
all your work on the `develop` branch, or your PR will be rejected.

As I would love to get some feedback, specially from people more familiar than
me with this kind of data, I will consider it as a WIP, and the API/column names
may change in the short term.

# Attribution

- Comunes List of Values: CC BY 4.0 Ministero dell'interno
- Regions List of Values: CC BY 3.0 Istituto nazionale di statistica

Inspired by [Marketto/codice-fiscale-utils](https://github.com/Marketto/codice-fiscale-utils), 
done to use in conjunction with [DavidePastore/codice-fiscale](https://github.com/DavidePastore/codice-fiscale)
