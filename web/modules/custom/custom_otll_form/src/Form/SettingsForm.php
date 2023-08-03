<?php

namespace Drupal\custom_otll_form\Form;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\MessageCommand;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure custom_otll_form settings for this site.
 */
class SettingsForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'custom_otll_form_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames(): array {
    return ['custom_otll_form.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {

    $form['user_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('User name:'),
      '#required' => TRUE,
      '#description' => $this->t('Enter user name to generate one time login link.'),
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Generate'),
      '#ajax' => [
        'callback' => '::ajaxHandler'
      ]
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function ajaxHandler(array &$form, FormStateInterface $form_state) {
    // Fetching user name from the form data.
    $username = $form_state->getValue('user_name');
    // Fetching user object.
    $user = user_load_by_name($username);
    $response = new AjaxResponse();
    if ($user) {
      $url = user_pass_reset_url($user);
      $response->addCommand(new MessageCommand($this->t('One-time login link for the given username is: <br> <a href="@url"> @url </a>', 
        ['@url' => $url])));
    }
    else {
      $response->addCommand(new MessageCommand($this->t('User not found.')));
    }

    return $response;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state): void {
    $username = $form_state->getValue('user_name');
    $user = user_load_by_name($username);

    if (!$user) {
      $form_state->setErrorByName('user_name', $this->t('The specified user does not exist.'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {
    // Perform any additional actions after the form is submitted successfully.
  }
}
