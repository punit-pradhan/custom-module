<?php

declare(strict_types=1);

namespace Drupal\custom_form\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;

/**
 * Configure form settings for this site.
 */
final class APIconfigform extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'custom_form_a_p_iconfigform';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames(): array {
    return ['custom_form.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {

    $form['full_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Full Name'),
      '#default_value' => $this->config('custom_form.settings')->get('full_name'),
      '#required' => TRUE,
    ];

    $form['phone_number'] = [
      '#type' => 'tel',
      '#title' => $this->t('Phone Number'),
      '#default_value' => $this->config('custom_form.settings')->get('phone_number'),
      '#required' => TRUE,
      '#suffix' => '<div class="phone-number-validation-status"></div>',
      '#ajax' => [
        'callback' => '::validatePhoneNumberAjax',
        'event' => 'change',
        'wrapper' => 'phone-number-wrapper',
        'progress' => [
          'type' => 'throbber',
          'message' => $this->t('Verifying...'),
        ],
      ],
    ];

    $form['email'] = [
      '#type' => 'email',
      '#title' => $this->t('Email ID'),
      '#default_value' => $this->config('custom_form.settings')->get('email'),
      '#required' => TRUE,
      '#suffix' => '<div class="email-validation-status"></div>',
      '#ajax' => [
        'callback' => '::validateEmailAjax',
        'event' => 'change',
        'wrapper' => 'email-wrapper',
        'progress' => [
          'type' => 'throbber',
          'message' => $this->t('Verifying...'),
        ],
      ],
    ];

    $form['gender'] = [
      '#type' => 'radios',
      '#title' => $this->t('Gender'),
      '#options' => [
        'male' => $this->t('Male'),
        'female' => $this->t('Female'),
        'other' => $this->t('Other'),
      ],
      '#default_value' => $this->config('custom_form.settings')->get('gender'),
      '#required' => TRUE,
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * Ajax callback for phone number validation.
   */
  public function validatePhoneNumberAjax(array &$form, FormStateInterface $form_state): AjaxResponse {
    $response = new AjaxResponse();
    $phone_number = $form_state->getValue('phone_number');
    if (strlen($phone_number) !== 10 || !is_numeric($phone_number)) {
      $error_message = $this->t('Phone number should be a 10-digit number.');
      $response->addCommand(new HtmlCommand('.phone-number-validation-status', '<div class="error">' . $error_message . '</div>'));
    } else {
      $response->addCommand(new HtmlCommand('.phone-number-validation-status', ''));
    }
    return $response;
  }

  /**
   * Ajax callback for email validation.
   */
  public function validateEmailAjax(array &$form, FormStateInterface $form_state): AjaxResponse {
    $response = new AjaxResponse();
    $email = $form_state->getValue('email');
    $email_validator = \Drupal::service('email.validator');
    if (!$email_validator->isValid($email)) {
      $error_message = $this->t('Invalid email format.');
      $response->addCommand(new HtmlCommand('.email-validation-status', '<div class="error">' . $error_message . '</div>'));
    } elseif (!preg_match('/@(yahoo|gmail|outlook)\.com$/', $email)) {
      $error_message = $this->t('Email address should be from Yahoo, Gmail, or Outlook.');
      $response->addCommand(new HtmlCommand('.email-validation-status', '<div class="error">' . $error_message . '</div>'));
    } else {
      $response->addCommand(new HtmlCommand('.email-validation-status', ''));
    }
    return $response;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {
    $this->config('custom_form.settings')
      ->set('full_name', $form_state->getValue('full_name'))
      ->set('phone_number', $form_state->getValue('phone_number'))
      ->set('email', $form_state->getValue('email'))
      ->set('gender', $form_state->getValue('gender'))
      ->save();
    parent::submitForm($form, $form_state);
  }

}