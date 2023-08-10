<?php
namespace Drupal\movie_entity;

use Drupal\Core\Config\Entity\EntityListBuilder;
use Drupal\Core\Entity\EntityInterface;

/**
 * Provides a list builder for Movie entities.
 */
class MovieListBuilder extends \Drupal\Core\Entity\EntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('ID');
    $header['title'] = $this->t('Title');
    // $header['movie_price'] = $this->t('Movie Price');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    // $entity is an instance of your Movie entity.
    $row['id'] = $entity->id();
    $row['title'] = $entity->label();
    // $row['movie_price'] = $entity->get('movie_price')->value;
    return $row + parent::buildRow($entity);
  }

}
