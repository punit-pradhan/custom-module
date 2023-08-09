<?php
// custom_rgb_color/src/Plugin/Field/FieldFormatter/CustomColorStaticTextFormatter.php

namespace Drupal\custom_rgb_color\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;

/**
 * Plugin implementation of the 'custom_color_static_text' formatter.
 *
 * @FieldFormatter(
 *   id = "custom_color_static_text",
 *   label = @Translation("Color Code Text"),
 *   field_types = {
 *     "custom_color_field"
 *   }
 * )
 */
class CustomRgbColorFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];
    foreach ($items as $delta => $item) {
      $color_code = $item->color;
      $elements[$delta] = ['#markup' => $color_code];
    }
    return $elements;
  }

}

?>