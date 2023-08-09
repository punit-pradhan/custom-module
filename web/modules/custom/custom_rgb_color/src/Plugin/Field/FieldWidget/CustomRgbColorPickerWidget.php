<?php
// custom_rgb_color/src/Plugin/Field/FieldWidget/CustomRgbColorPickerWidget.php

namespace Drupal\custom_rgb_color\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Defines the 'custom_field_color_picker' field widget.
 *
 * @FieldWidget(
 *   id = "custom_field_color_picker",
 *   label = @Translation("Color Picker"),
 *   field_types = {"custom_color_field"},
 * )
 */
class CustomRgbColorPickerWidget extends WidgetBase {

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state): array {
    $element['color'] = [
      '#title' => t('Pick Color'),
      '#type' => 'color',
      '#default_value' => isset($items[$delta]->color) ? $items[$delta]->color : '#ffffff',
    ];
    return $element;
  }

}

?>