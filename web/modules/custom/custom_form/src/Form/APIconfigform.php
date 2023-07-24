<?php

declare(strict_types=1);

namespace Drupal\custom_form\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

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
    ];

    $form['email'] = [
      '#type' => 'email',
      '#title' => $this->t('Email ID'),
      '#default_value' => $this->config('custom_form.settings')->get('email'),
      '#required' => TRUE,
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
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state): void {
    parent::validateForm($form, $form_state);

    // Validate phone number.
    $phone_number = $form_state->getValue('phone_number');
    if (strlen($phone_number) !== 10 || !is_numeric($phone_number)) {
      $form_state->setErrorByName(
        'phone_number',
        $this->t('Phone number should be a 10-digit number.')
      );
    }

    // Validate email.
    $email = $form_state->getValue('email');
    $email_validator = \Drupal::service('email.validator');
    if (!$email_validator->isValid($email)) {
      $form_state->setErrorByName(
        'email',
        $this->t('Invalid email format.')
      );
    } elseif (!preg_match('/@(yahoo|gmail|outlook)\.com$/', $email)) {
      $form_state->setErrorByName(
        'email',
        $this->t('Email address should be from Yahoo, Gmail, or Outlook.')
      );
    }
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