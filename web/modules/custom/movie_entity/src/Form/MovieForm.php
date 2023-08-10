<?php
/**
 * @file
 * Contains Drupal\movie_entity\Form\MovieForm.
 */

namespace Drupal\movie_entity\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

/**
 * Form controller for the movie entity edit forms.
 *
 * @ingroup content_entity_example
 */
class MovieForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildForm($form, $form_state);
    return $form;
  }

  /**
 * {@inheritdoc}
 */
public function save(array $form, FormStateInterface $form_state) {
  parent::save($form, $form_state);

  $url = Url::fromRoute('movie_entity.list');
  $form_state->setRedirectUrl($url);
}


}