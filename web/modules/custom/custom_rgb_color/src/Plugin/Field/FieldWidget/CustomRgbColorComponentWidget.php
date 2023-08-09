<?php
// custom_rgb_color/src/Plugin/Field/FieldWidget/CustomRgbColorComponentWidget.php

namespace Drupal\custom_rgb_color\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Defines the 'custom_field_rgb_component' field widget.
 *
 * @FieldWidget(
 *   id = "custom_field_rgb_component",
 *   label = @Translation("RGB Components"),
 *   field_types = {"custom_color_field"},
 * )
 */
class CustomRgbColorComponentWidget extends WidgetBase {

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state): array {
    $element['r'] = [
      '#type' => 'number',
      '#title' => t('R'),
      '#default_value' => isset($items[$delta]->color) ? hexdec(substr($items[$delta]->color, 1, 2)) : 255,
      '#min' => 0,
      '#max' => 255,
    ];

    $element['g'] = [
      '#type' => 'number',
      '#title' => t('G'),
      '#default_value' => isset($items[$delta]->color) ? hexdec(substr($items[$delta]->color, 3, 2)) : 255,
      '#min' => 0,
      '#max' => 255,
    ];

    $element['b'] = [
      '#type' => 'number',
      '#title' => t('B'),
      '#default_value' => isset($items[$delta]->color) ? hexdec(substr($items[$delta]->color, 5, 2)) : 255,
      '#min' => 0,
      '#max' => 255,
    ];

    return ['color' => $element];
  }

}

?>