<?php

// custom_rgb_color/src/Plugin/Field/FieldType/CustomRgbColorField.php

namespace Drupal\custom_rgb_color\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\TypedData\DataDefinition;

/**
 * Defines the 'custom_color_field' field type.
 *
 * @FieldType(
 *   id = "custom_color_field",
 *   label = @Translation("RGB Color"),
 *   category = @Translation("Custom"),
 *   default_widget = "custom_field_rgb",
 *   default_formatter = "custom_color_static_text",
 * )
 */
class CustomRgbColorItem extends FieldItemBase {

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition) {
    return [
      'columns' => [
        'color' => [
          'type' => 'varchar',
          'length' => 7, // 6-digit hex code + '#' symbol
        ],
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {
    $properties['color'] = DataDefinition::create('string')
      ->setLabel(t('Color'));

    return $properties;
  }

}

?>