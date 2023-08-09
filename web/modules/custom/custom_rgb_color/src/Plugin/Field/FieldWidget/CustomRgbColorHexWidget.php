<?php

// custom_rgb_color/src/Plugin/Field/FieldWidget/CustomRgbColorHexWidget.php

namespace Drupal\custom_rgb_color\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Defines the 'custom_field_rgb' field widget.
 *
 * @FieldWidget(
 *   id = "custom_field_rgb",
 *   label = @Translation("RGB Hex Code"),
 *   field_types = {"custom_color_field"},
 * )
 */
class CustomRgbColorHexWidget extends WidgetBase {

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $value = isset($items[$delta]->color) ? $items[$delta]->color : '';
    $element += [
      '#type' => 'textfield',
      '#title' => t('Color'),
      '#default_value' => $value,
      '#size' => 7,
      '#maxlength' => 7,
      '#element_validate' => [
        [static::class, 'validate'],
      ],
    ];
    return ['color' => $element];
  }

  /**
   * Validate the color text field.
   */
  public static function validate($element, FormStateInterface $form_state) {
    $value = $element['#value'];
    if (strlen($value) == 0) {
      $form_state->setValueForElement($element, '');
      return;
    }
    if (!preg_match('/^#([a-f0-9]{6})$/iD', strtolower($value))) {
      $form_state->setError($element, t('Color must be a valid 6-digit hexadecimal value, suitable for CSS.'));
    }
  }

}

?>