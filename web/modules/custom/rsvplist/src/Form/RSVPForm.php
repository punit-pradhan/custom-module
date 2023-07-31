<?php

/**
 * @file
 * A Form to collect an email address for Rsvp Details.
 */

namespace Drupal\rsvplist\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class RSVPForm extends FormBase
{

  /**
   * {@inheritdoc}
   */
  public function getFormId()
  {
    return 'rsvplist_email_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state)
  {
    $node = \Drupal::routeMatch()->getParameter('node');

    if (!(is_null($node))) {
      $nid = $node->id();
    } else {
      //if a node could not be loaded, default to 0;
      $nid = 0;
    }
    // Establish the $form render array. It has an email text field,
    // a submit button, and a hidden field containing the node ID.

    $form['email'] = [
      '#type' => 'textfield',
      '#title' => t('Email address'),
      '#size' => 25,
      '#description' => t("We will send updates to the email address you provide."),
      '#required' => TRUE,
    ];
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('RSVP'),
    ];

    $form['nid'] = [
      '#type' => 'hidden',
      '#value' => $nid,
    ];

    return $form;
  }

  /**
   * {@inheritDoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    $submitted_email = $form_state->getValue('email');
    $this->messenger()->addMessage(t("The form is working! You entered @entry.", ['@entry' => $submitted_email]));
  }
}
