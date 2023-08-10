<?php

namespace Drupal\movie_entity\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\EntityPublishedTrait;
use Drupal\user\UserInterface;

/**
 * Defines the movie_entity entity.
 *
 * @ContentEntityType(
 *   id = "movie_entity",
 *   label = @Translation("Movie"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\movie_entity\MovieListBuilder",
 *     "views_data" = "Drupal\views\EntityViewsData",
 *     "form" = {
 *       "add" = "Drupal\movie_entity\Form\MovieForm",
 *       "edit" = "Drupal\movie_entity\Form\MovieForm",
 *       "delete" = "Drupal\movie_entity\Form\MovieDelete",
 *     },
 *   },
 *   admin_permission = "administer movie entity",
 *   fieldable = TRUE,
 *   base_table = "movie_entity",
 *   data_table = "movie_entity_field_data",
 *   revision_table = "movie_entity_revision",
 *   revision_data_table = "movie_entity_field_revision",
 *   entity_keys = {
 *     "id" = "id",
 *     "uuid" = "uuid",
 *     "label" = "title",
 *     "revision" = "vid",
 *     "status" = "status",
 *     "published" = "status",
 *     "uid" = "uid",
 *     "owner" = "uid",
 *   },
 *   links = {
 *     "canonical" = "/movie_entity/{movie_entity}",
 *     "edit-form" = "/movie_entity/{movie_entity}/edit",
 *     "delete-form" = "/movie_entity/{movie_entity}/delete",
 *     "collection" = "/movie_entity/list",
 *     "create" = "/movie_entity/create"
 *   },
 *   field_ui_base_route = "movie_entity.contact_settings",
 *   revision_metadata_keys = {
 *     "revision_user" = "revision_uid",
 *     "revision_created" = "revision_timestamp",
 *     "revision_log_message" = "revision_log"
 *   }
 * )
 */

class Movie extends ContentEntityBase {

  use EntityChangedTrait;
  
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type); // provides id and uuid fields

    $fields['user_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(('User'))
      ->setDescription(('The user that created the example.'))
      ->setSetting('target_type', 'user')
      ->setSetting('handler', 'default')
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'author',
        'weight' => 0,
      ])
      ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete',
        'weight' => 5,
        'settings' => [
          'match_operator' => 'CONTAINS',
          'size' => '60',
          'autocomplete_type' => 'tags',
          'placeholder' => '',
        ],
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);
    
      $fields['title'] = BaseFieldDefinition::create('string')
      ->setLabel(('Title'))
      ->setDescription(('The title of the movie entity.'))
      ->setRequired(TRUE)
      ->setTranslatable(TRUE)
      ->setSetting('max_length', 255)
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => -6,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -6,
      ]);

    $fields['body'] = BaseFieldDefinition::create('text_long')
      ->setLabel(('Body'))
      ->setDescription(('The description of the movie entity.'))
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'text_default',
        'weight' => -4,
      ])
      ->setDisplayOptions('form', [
        'type' => 'text_textarea',
        'weight' => -4,
      ]);

    $fields['movie_price'] = BaseFieldDefinition::create('decimal')
      ->setLabel(('Movie Price'))
      ->setDescription(('The price of the movie.'))
      ->setDefaultValue(0)
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'decimal',
        'weight' => -2,
      ])
      ->setDisplayOptions('form', [
        'type' => 'number',
        'weight' => -2,
      ]);

    $fields['image'] = BaseFieldDefinition::create('image')
      ->setLabel(('Image'))
      ->setDescription(('An image for the movie entity.'))
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'image',
        'weight' => 0,
      ])
      ->setDisplayOptions('form', [
        'type' => 'image_image',
        'weight' => 0,
      ]);

      $fields['status'] = BaseFieldDefinition::create('boolean')
        ->setLabel(('Publishing status'))
        ->setDescription(('A boolean indicating whether the Offer entity is published.'))
        ->setDefaultValue(TRUE);
      $fields['created'] = BaseFieldDefinition::create('created')
        ->setLabel(('Created'))
        ->setDescription(('The time that the entity was created.'));
      $fields['changed'] = BaseFieldDefinition::create('changed')
        ->setLabel(('Changed'))
        ->setDescription(('The time that the entity was last edited.'));

    return $fields;
  }
    /**
   * {@inheritdoc}
   *
   * When a new entity instance is added, set the user_id entity reference to
   * the current user as the creator of the instance.
   */
  public static function preCreate(EntityStorageInterface $storage_controller, array &$values) {
    parent::preCreate($storage_controller, $values);
    $values += array(
      'user_id' => \Drupal::currentUser()->id(),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getCreatedTime() {
    return $this->get('created')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwner() {
    return $this->get('user_id')->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwnerId() {
    return $this->get('user_id')->target_id;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwnerId($uid) {
    $this->set('user_id', $uid);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwner(UserInterface $account) {
    $this->set('user_id', $account->id());
    return $this;
  }

}
