<?php
// custom_rgb_color/src/Plugin/Field/FieldFormatter/CustomColorBackgroundColorFormatter.php

namespace Drupal\custom_rgb_color\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;

/**
 * Plugin implementation of the 'custom_color_background_color' formatter.
 *
 * @FieldFormatter(
 *   id = "custom_color_background_color",
 *   label = @Translation("Color Code Text with Background Color"),
 *   field_types = {
 *     "custom_color_field"
 *   }
 * )
 */
class CustomColorBackgroundColorFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];
    foreach ($items as $delta => $item) {
      $color_code = $item->color;
      $elements[$delta] = [
        '#type' => 'markup',
        '#markup' => '<div style="background-color: ' . $color_code . '; color: #fff;">' . $color_code . '</div>',
      ];
    }
    return $elements;
  }

}

?>