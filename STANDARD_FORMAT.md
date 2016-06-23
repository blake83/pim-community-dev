# Standard format

The standard format is a normalized array representation of the objects of the PIM. It it used to manipulate (query/update) these objects *inside* the PIM. Currently it is *not* designed to provide a a representation of these objects outside the PIM.

The standard format is consistent in term of:

* structure: for instance, products will always be represented the same way
* data formatting: for instance, dates will always be formatted the same way

## General points

We use the JSON format machin.

Dates and datetimes are always formatted to [ISO-8601](https://en.wikipedia.org/wiki/ISO_8601), with the timezone.
For instance, the standard format of an object that contains the properties *a_datetime* and *a_date* would be:
    
        array:2 [
          "a_datetime" => "2016-06-23T11:24:44+02:00"
          "a_date" => "2016-06-23T00:00:00+04:00"
        ]

Decimal numbers are formatted to strings. We don't use float to avoid [the floating point precision problem of PHP](http://php.net/manual/en/language.types.float.php). If you need to perform precise operations on such numbers, please use [the arbitrary precision math functions](http://php.net/manual/en/ref.bc.php) or the [gmp functions](http://php.net/manual/en/ref.gmp.php).
For instance, the standard format of an object that contains the properties *a_decimal* and *a_negative_decimal* would be:
    
        array:2 [
          "a_decimal" => "46546.65987313"
          "a_negative_deciaml" => "-45.8981226"
        ]

Linked entities are represented only by their identifier as strings. For instance, the standard format of a *foo* object that has a link to an external *bar* object would be:
    
        array:1 [
          "bar" => "here is the identifier of the bar object"
        ]

## Product

### Common structure

The products contain inner fields and product values that are linked to attributes.
All products have the same fields (family, groups, variant groups, categories, associations, status, dates of creation and update) while product values are flexible among products.

Let's a *bar* product, without any product value, except its identifier *sku*. This product also contains:

* a family
* several groups
* a variant group
* several categories
* several associations related to groups and/or other products

Its standard format would be the following:
        
        array:9 [
          "family" => "familyA"
          "groups" => array:2 [
            0 => "groupA"
            1 => "groupB"
          ]
          "variant_group" => "variantA"
          "categories" => array:2 [
            0 => "categoryA1"
            1 => "categoryB"
          ]
          "enabled" => false
          "values" => array:1 [
            "sku" => array:1 [
              0 => array:3 [
                "locale" => null
                "scope" => null
                "data" => "bar"
              ]
            ]
          ]
          "created" => "2016-06-23T11:24:44+02:00"
          "updated" => "2016-06-23T11:24:44+02:00"
          "associations" => array:3 [
            "PACK" => array:1 [
              "products" => array:2 [
                0 => "bar"
                1 => "baz"
              ]
            ]
            "UPSELL" => array:1 [
              "groups" => array:1 [
                0 => "groupA"
              ]
            ]
            "X_SELL" => array:2 [
              "groups" => array:1 [
                0 => "groupB"
              ]
              "products" => array:1 [
                0 => "bar"
              ]
            ]
          ]
        ]

Family (key *family*), groups (key *groups*) variant group (key *variant_group*), categories (key *categories*) as well as products (key *associations.ASSOCIATION_NAME.products*) and groups (key *associations.ASSOCIATION_NAME.groups*) of the associations, are external objects to the product. So they are represented by their identifier as strings.

Date of creation (key *created*) and update (key *updated*) are datetimes.

Status (key *enabled*) is a boolean.

TODO: identifier in the root

### Product values

Let's now consider a catalog with all attribute types possible and a *foo* product, that contains:

* all the attributes of the catalog
* a family
* several groups
* a variant group
* several categories
* several associations related to groups and/or other products

Its standard format would be the following:

        array:9 [
          "family" => "familyA"
          "groups" => array:2 [
            0 => "groupA"
            1 => "groupB"
          ]
          "variant_group" => "variantA"
          "categories" => array:2 [
            0 => "categoryA1"
            1 => "categoryB"
          ]
          "enabled" => true
          "values" => array:16 [
            "sku" => array:1 [
              0 => array:3 [
                "locale" => null
                "scope" => null
                "data" => "foo"
              ]
            ]
            "a_file" => array:1 [
              0 => array:3 [
                "locale" => null
                "scope" => null
                "data" => "f/2/e/6/f2e6674e076ad6fafa12012e8fd026acdc70f814_fileA.txt"
              ]
            ]
            "an_image" => array:1 [
              0 => array:3 [
                "locale" => null
                "scope" => null
                "data" => "f/4/d/1/f4d12ffbdbe628ba8e0b932c27f425130cc23535_imageA.jpg"
              ]
            ]
            "a_date" => array:1 [
              0 => array:3 [
                "locale" => null
                "scope" => null
                "data" => "2016-06-13T00:00:00+02:00"
              ]
            ]
            "a_multi_select" => array:1 [
              0 => array:3 [
                "locale" => null
                "scope" => null
                "data" => array:2 [
                  0 => "optionA"
                  1 => "optionB"
                ]
              ]
            ]
            "a_number_float" => array:1 [
              0 => array:3 [
                "locale" => null
                "scope" => null
                "data" => "12.5678"
              ]
            ]
            "a_number_float_negative" => array:1 [
              0 => array:3 [
                "locale" => null
                "scope" => null
                "data" => "-99.8732"
              ]
            ]
            "a_number_integer" => array:1 [
              0 => array:3 [
                "locale" => null
                "scope" => null
                "data" => "42.0000"
              ]
            ]
            "a_ref_data_multi_select" => array:1 [
              0 => array:3 [
                "locale" => null
                "scope" => null
                "data" => array:2 [
                  0 => "fabricA"
                  1 => "fabricB"
                ]
              ]
            ]null
            "a_ref_data_simple_select" => array:1 [
              0 => array:3 [
                "locale" => null
                "scope" => null
                "data" => "colorB"
              ]
            ]
            "a_simple_select" => array:1 [
              0 => array:3 [
                "locale" => null
                "scope" => null
                "data" => "optionB"
              ]
            ]
            "a_text" => array:1 [
              0 => array:3 [
                "locale" => null
                "scope" => null
                "data" => ""this is a text""
              ]
            ]
            "a_text_area" => array:1 [
              0 => array:3 [
                "locale" => null
                "scope" => null
                "data" => ""this is a very very very very very long  text""
              ]
            ]
            "a_yes_no" => array:1 [
              0 => array:3 [
                "locale" => null
                "scope" => null
                "data" => true
              ]
            ]
            "a_metric" => array:1 [
              0 => array:3 [
                "locale" => null
                "scope" => null
                "data" => array:2 [
                  "data" => "987654321987.123456789123"
                  "unit" => "KILOWATT"
                ]
              ]
            ]
            "a_price" => array:1 [
              0 => array:3 [
                "locale" => null
                "scope" => null
                "data" => array:2 [
                  0 => array:2 [
                    "data" => "45.00"
                    "currency" => "USD"
                  ]
                  1 => array:2 [
                    "data" => "56.53"
                    "currency" => "EUR"
                  ]
                ]
              ]
            ]
          ]
          "created" => "2016-06-23T11:24:44+02:00"
          "updated" => "2016-06-23T11:24:44+02:00"
          "associations" => array:3 [
            "PACK" => array:1 [
              "products" => array:2 [
                0 => "bar"
                1 => "baz"
              ]
            ]
            "UPSELL" => array:1 [
              "groups" => array:1 [
                0 => "groupA"
              ]
            ]
            "X_SELL" => array:2 [
              "groups" => array:1 [
                0 => "groupB"
              ]
              "products" => array:1 [
                0 => "bar"
              ]
            ]
          ]
        ]

The product values are provided via the key *values*.

As product values can be localisable and/or scopable, they always respect the following structure:

        array:3 [
            "locale" => "a locale code"
            "scope" => "a scope code"
            "data" => "the value for the given locale and scope"
        ]

Depending on the type of the product value, the *data* can have different structure:

| attribute type               	| data structure 	| data example                                                                                         	| notes                                                                                                             	|
|------------------------------	|----------------	|------------------------------------------------------------------------------------------------------	|-------------------------------------------------------------------------------------------------------------------	|
| identifier                   	| string         	| "foo"                                                                                                	|                                                                                                                   	|
| file                         	| string         	| "f/2/e/6/f2e6674e076ad6fafa12012e8fd026acdc70f814_fileA.txt"                                         	| it represents the *key* of the object *Akeneo\Component\FileStorage\Model\FileInfoInterface*                      	|
| image                        	| string         	| "f/4/d/1/f4d12ffbdbe628ba8e0b932c27f425130cc23535_imageA.jpg"                                        	| it represents the *key* of the object *Akeneo\Component\FileStorage\Model\FileInfoInterface*                      	|
| date                         	| string         	| "2016-06-13T00:00:00+02:00"                                                                          	| formatted to ISO-8601 (see above)                                                                                 	|
| multi select                 	| array          	| [0 => "optionA", 1 => "optionB"]                                                                     	| each element of the array represents the *code* of the *Pim\Component\Catalog\Model\AttributeOptionInterface*     	|
| number                       	| string         	| "-99.8732"                                                                                           	| formatted as a string to avoid the floating point precision problem of PHP (see above)                            	|
| reference data multi select  	| array          	| [0 => "fabricA",1 => "fabricB"]                                                                      	| each element of the array represents the *code* of the *Pim\Component\ReferenceData\Model\ReferenceDataInterface* 	|
| simple select                	| string         	| "optionB"                                                                                            	| it represents the *code* of the *Pim\Component\Catalog\Model\AttributeOptionInterface*                            	|
| reference data simple select 	| string         	| "colorB"                                                                                             	| it represents the *code* of the *Pim\Component\ReferenceData\Model\ReferenceDataInterface*                        	|
| text                         	| string         	| "this is a text"                                                                                     	|                                                                                                                   	|
| text area                    	| string         	| "this is a very very very very very long,text"                                                       	|                                                                                                                   	|
| yes/no                       	| boolean        	| true                                                                                                 	|                                                                                                                   	|
| metric                       	| array          	| ["data" => "987654321987.123456789123","unit" => "KILOWATT"]                                         	| *data* and *unit* keys are expected *unit* should be a know unit depending of the metric family of the attribute  	|
| price collection             	| array          	| [    0 => ["data" => "45.00","currency" => "USD"],    1 => ["data" => "56.53","currency" => "EUR"] ] 	| *data* and *currency* keys are exepected for each price *currency* should be a known currency                     	|


The following product values data, that represents decimal values, are represented with strings in the standard format:

* metric (class *Pim\Component\Catalog\Model\MetricInterface*)
* price (class *Pim\Component\Catalog\Model\ProductPriceInterface*)
* number (class *Pim\Component\Catalog\Model\ProductValueInterface*, property *getDecimal*)


## Other entities

TODO: labels
