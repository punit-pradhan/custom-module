<?php

namespace Drupal\block_api\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\block_api\services\Db_insert;
use Drupal\Core\TempStore\PrivateTempStoreFactory; 

class ConfigForm extends ConfigFormBase {

  protected $loaddata;
  protected $database;
  protected $tempStore;

  /**
   * {@inheritdoc}
   */
  public function getFormId()
  {
    return 'flagship_program_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames()
  {
    return ['flagship_form.settings'];
  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('block_api.dbinsert'),
      $container->get('tempstore.private'),
    );
  }

  public function __construct(Db_insert $database, PrivateTempStoreFactory $temp_store_factory) {
    $this->database = $database;
    $this->tempStore = $temp_store_factory->get('block_api'); // Initialize the tempstore object.
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('flagship_form.settings');
    $data = $this->tempStore->get('data'); // Retrieve the data from the tempstore.
    if (empty($data)) {
      $data = $config->get('data');
      $this->tempStore->set('data', $data); // Save the data to the tempstore.
    }

    $form['table']['#prefix'] = '<div id="flagship-wrapper">';
    $form['table']['#suffix'] = '</div>';

    $form['table']['data'] = [
      '#type' => 'table',
      '#empty' => $this->t('No data available.'),
      '#header' => [
        $this->t('Title'),
        $this->t('Label 1'),
        $this->t('Value 1'),
        $this->t('Label 2'),
        $this->t('Value 2'),
        $this->t('Remove'),
      ],
    ];

    $data = $form_state->get('data');
    foreach ($data as $index => $value) {
      $form['table']['data'][$index]['title'] = [
        '#type' => 'textfield',
        '#default_value' => $value['title'] ?? '',
      ];

      $form['table']['data'][$index]['label1'] = [
        '#type' => 'textfield',
        '#default_value' => $value['label1'] ?? '',
      ];

      $form['table']['data'][$index]['value1'] = [
        '#type' => 'textfield',
        '#default_value' => $value['value1'] ?? '',
      ];

      $form['table']['data'][$index]['label2'] = [
        '#type' => 'textfield',
        '#default_value' => $value['label2'] ?? '',
      ];

      $form['table']['data'][$index]['value2'] = [
        '#type' => 'textfield',
        '#default_value' => $value['value2'] ?? '',
      ];

      $form['table']['data'][$index]['remove'] = [
        '#type' => 'submit',
        '#value' => $this->t('Remove This'),
        '#submit' => ['::removeRow'],
        '#attributes' => [
          'row_number' => $index,
        ],
        '#ajax' => [
          'callback' => '::updateCallback',
          'wrapper' => 'flagship-wrapper',
        ],
      ];
    }

    $form['table']['add_one_more'] = [
      '#type' => 'submit',
      '#value' => $this->t('Add One More'),
      '#submit' => ['::addRow'],
      '#ajax' => [
        'callback' => '::updateCallback',
        'wrapper' => 'flagship-wrapper',
      ],
    ];

    $form['#cache'] = [
      'tags' => ['flagship-config'],
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * Override the form data.
   *
   * @param array $form
   *   This array contains form information.
   * @param FormStateInterface $form_state
   *   This tracks the form states.
   *
   * @return array
   *
   */
  public function updateCallback(array &$form, FormStateInterface $form_state)
  {
    return $form['table'];
  }

  /**
   * This function add a new tag in the form.
   *
   * @param array $form
   *   This array contains form data.
   * @param FormStateInterface $form_state
   *   This contains the form states.
   *
   * @return void
   *
   */
  public function addRow(array &$form, FormStateInterface $form_state) {
    $data = $this->tempStore->get('data');
    $data[] = [
      'title' => '',
      'label1' => '',
      'value1' => '',
      'label2' => '',
      'value2' => '',
    ];
    $this->tempStore->set('data', $data); // Save the updated data back to the tempstore.
    $form_state->setRebuild(TRUE);
  }
  
   /**
   * This function adds a new row in the form.
   *
   * @param array $form
   *   This array contains form data.
   * @param FormStateInterface $form_state
   *   This contains the form states.
   *
   * @return void
   *
   */
  public function removeRow(array &$form, FormStateInterface $form_state) {
    $data = $this->tempStore->get('data');
    $triggering_element = $form_state->getTriggeringElement();
    $row_number = $triggering_element['#attributes']['row_number'];
    if (isset($data[$row_number])) {
      unset($data[$row_number]);
      $this->tempStore->set('data', $data); // Save the updated data back to the tempstore.
      if (empty($data)) {
        $this->addRow($form, $form_state);
      }
      $form_state->setRebuild(TRUE);
    }
  }

/**
   * Remove a particular row based on that it will update form.
   *
   * @param array $form
   *   This array contains form data.
   * @param FormStateInterface $form_state
   *   This contains the form states.
   *
   * @return void
   *
   */

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    $config = $this->configFactory->getEditable('flagship_form.settings');
    $data = $form_state->getValue('data');
    $config->set('data', $data)->save();
    \Drupal::service('cache_tags.invalidator')
      ->invalidateTags(['flagship-config']);
    parent::submitForm($form, $form_state);
  }
}