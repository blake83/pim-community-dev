Feature: Standard format for products
  In order to manipulate products inside and outside the PIM
  As a developer
  I need the existence of a standard format for products, no matter their storage

  Background:
    Given a "minimal" catalog configuration
    And the following categories:
      | code       | parent    |
      | categoryA  | master    |
      | categoryA1 | categoryA |
      | categoryA2 | categoryA |
      | categoryB  | master    |
    And the following attribute groups:
      | code            |
      | attributeGroupA |
      | attributeGroupB |
    And the following attributes:
      | code                     | allowed_extensions | available_locales | date_max | date_min | decimals_allowed | default_metric_unit | group           | localizable | max_characters | max_file_size | metric_family | minimum_input_length | negative_allowed | number_max | number_min | reference_data_name | scopable | sort_order | type                        | unique | useable_as_grid_filter | validation_regexp | validation_rule | wysiwyg_enabled |
      | sku                      |                    |                   |          |          |                  |                     | attributeGroupA | 0           |                |               |               | 0                    |                  |            |            |                     | 0        | 0          | identifier                  | 1      | 1                      |                   |                 |                 |
      | a_date                   |                    |                   |          |          |                  |                     | attributeGroupA | 0           |                |               |               | 0                    |                  |            |            |                     | 0        | 0          | date                        | 0      | 0                      |                   |                 |                 |
      | a_file                   |                    |                   |          |          |                  |                     | attributeGroupA | 0           |                |               |               | 0                    |                  |            |            |                     | 0        | 0          | file                        | 0      | 0                      |                   |                 |                 |
      | an_image                 |                    |                   |          |          |                  |                     | attributeGroupB | 0           |                |               |               | 0                    |                  |            |            |                     | 0        | 0          | image                       | 0      | 0                      |                   |                 |                 |
      | a_metric                 |                    |                   |          |          | 1                | KILOWATT            | attributeGroupB | 0           |                |               | Power         | 0                    | 1                |            |            |                     | 0        | 0          | metric                      | 0      | 0                      |                   |                 |                 |
      | a_multi_select           |                    |                   |          |          |                  |                     | attributeGroupB | 0           |                |               |               | 0                    |                  |            |            |                     | 0        | 0          | multiselect                 | 0      | 0                      |                   |                 |                 |
      | a_number_float           |                    |                   |          |          | 1                |                     | attributeGroupB | 0           |                |               |               | 0                    | 0                |            |            |                     | 0        | 0          | number                      | 0      | 0                      |                   |                 |                 |
      | a_number_float_negative  |                    |                   |          |          | 1                |                     | attributeGroupB | 0           |                |               |               | 0                    | 1                |            |            |                     | 0        | 0          | number                      | 0      | 0                      |                   |                 |                 |
      | a_number_integer         |                    |                   |          |          | 0                |                     | attributeGroupB | 0           |                |               |               | 0                    | 0                |            |            |                     | 0        | 0          | number                      | 0      | 0                      |                   |                 |                 |
      | a_price                  |                    |                   |          |          | 1                |                     | attributeGroupA | 0           |                |               |               | 0                    |                  |            |            |                     | 0        | 0          | prices                      | 0      | 0                      |                   |                 |                 |
      | a_ref_data_multi_select  |                    |                   |          |          |                  |                     | attributeGroupA | 0           |                |               |               | 0                    |                  |            |            | fabrics             | 0        | 0          | reference_data_multiselect  | 0      | 0                      |                   |                 |                 |
      | a_ref_data_simple_select |                    |                   |          |          |                  |                     | attributeGroupA | 0           |                |               |               | 0                    |                  |            |            | color               | 0        | 0          | reference_data_simpleselect | 0      | 0                      |                   |                 |                 |
      | a_simple_select          |                    |                   |          |          |                  |                     | attributeGroupB | 0           |                |               |               | 0                    |                  |            |            |                     | 0        | 0          | simpleselect                | 0      | 0                      |                   |                 |                 |
      | a_text                   |                    |                   |          |          |                  |                     | attributeGroupA | 0           |                |               |               | 0                    |                  |            |            |                     | 0        | 0          | text                        | 0      | 0                      |                   |                 |                 |
      | a_text_area              |                    |                   |          |          |                  |                     | attributeGroupA | 0           |                |               |               | 0                    |                  |            |            |                     | 0        | 0          | textarea                    | 0      | 0                      |                   |                 |                 |
      | a_yes_no                 |                    |                   |          |          |                  |                     | attributeGroupA | 0           |                |               |               | 0                    |                  |            |            |                     | 0        | 0          | boolean                     | 0      | 0                      |                   |                 |                 |
    And the following "a_multi_select" attribute options: optionA, optionB
    And the following "a_simple_select" attribute options: optionA, optionB
    And the following "a_ref_data_multi_select" attribute reference data: fabricA, fabricB
    And the following "a_ref_data_simple_select" attribute reference data: colorA, colorB, colorc
    And the following product groups:
      | code     | label | axis            | type    |
      | variantA |       | a_simple_select | VARIANT |
      | groupA   |       |                 | RELATED |
      | groupB   |       |                 | RELATED |
    And the following channels:
      | code   | label  | locales             | currencies | tree   |
      | tablet | Tablet | en_US, fr_FR, de_DE | EUR        | master |
    And the following family:
      | code    | attributes                                                                                                                                                                                                      | attribute_as_label | requirements-ecommerce                                                                                                                                                                                          | requirements-tablet                                                                                                                                                                                             |
      | familyA | a_date,a_file,a_metric,a_multi_select,a_number_float,a_number_float_negative,a_number_integer,a_price,a_ref_data_multi_select,a_ref_data_simple_select,a_simple_select,a_text,a_text_area,a_yes_no,an_image,sku | sku                | a_date,a_file,a_metric,a_multi_select,a_number_float,a_number_float_negative,a_number_integer,a_price,a_ref_data_multi_select,a_ref_data_simple_select,a_simple_select,a_text,a_text_area,a_yes_no,an_image,sku | a_date,a_file,a_metric,a_multi_select,a_number_float,a_number_float_negative,a_number_integer,a_price,a_ref_data_multi_select,a_ref_data_simple_select,a_simple_select,a_text,a_text_area,a_yes_no,an_image,sku |
    And the following random files:
      | filename   | size |
      | fileA.txt  | 1    |
      | imageA.jpg | 1    |
    And the following products:
      | sku | enabled |
      | bar | 0       |
      | baz | 1       |
    And the following products:
      | sku | a_file               | an_image              | a_date     | a_metric                  | a_metric-unit | a_multi_select  | a_number_float | a_number_float_negative | a_number_integer | a_price-EUR | a_price-USD | a_ref_data_multi_select | a_ref_data_simple_select | a_simple_select | a_text           | a_text_area                                     | a_yes_no | categories           | enabled | family  | groups                 | PACK-groups | PACK-products | SUBSTITUTION-groups | SUBSTITUTION-products | UPSELL-groups | UPSELL-products | X_SELL-groups | X_SELL-products |
      | foo | %fixtures%/fileA.txt | %fixtures%/imageA.jpg | 2016-06-13 | 987654321987.123456789123 | KILOWATT      | optionB,optionA | 12.5678        | -99.8732                | 42               | 56.53       | 45          | fabricA,fabricB         | colorB                   | optionB         | "this is a text" | "this is a very very very very very long  text" | 1        | categoryA1,categoryB | 1       | familyA | groupA,groupB,variantA |             | bar,baz       |                     |                       | groupA        |                 | groupB        | bar             |
    And the following associations for the product "foo":
      | type   | products | groups |
      | PACK   | bar, baz |        |
      | UPSELL |          | groupA |
      | X_SELL | bar      | groupB |

  Scenario: Define the standard format for an empty product
    When I normalize the product "bar" with the "standard" format
    Then the standard format result of the product "bar" should be:
      """
      array:9 [
        "family" => null
        "groups" => []
        "variant_group" => null
        "categories" => []
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
        "associations" => []
        "created" => "2016-06-14T13:12:50+02:00"
        "updated" => "2016-06-14T13:12:50+02:00"
      ]
      """

  Scenario: Define the standard f ormat for a product with all types of attributes
    When I normalize the product "foo" with the "standard" format
    Then the standard format result of the product "foo" should be:
      """
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
              "data" => "4/d/e/b/4deb535f0979dea59cf34661e22336459a56bed3_fileA.txt"
            ]
          ]
          "an_image" => array:1 [
            0 => array:3 [
              "locale" => null
              "scope" => null
              "data" => "1/5/7/5/15757827125efa686c1c0f1e7930ca0c528f1c2c_imageA.jpg"
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
              "data" => "42"
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
          ]
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
                  "data" => "45"
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
        "created" => "2016-06-14T13:12:50+02:00"
        "updated" => "2016-06-14T13:12:50+02:00"
      ]
      """
