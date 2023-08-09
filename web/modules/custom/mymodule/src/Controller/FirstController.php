<?php

namespace Drupal\mymodule\Controller;

use Drupal\Core\Controller\ControllerBase;


/**
 * FirstController
 */
class FirstController extends ControllerBase {

  /**
   * @method sayHello
   *  This function is used to show the result in /hello page.
   *
   *   @return array
   */
  public function sayHello() {
    return [
      '#type' => 'markup',
      '#markup' => t('hello world'),
    ];
  }


  public function Welcome_user() {
    $user = \Drupal\user\Entity\User::load(\Drupal::currentUser()->id());
    $username = $user->getAccountName();
    return [
      '#type' => 'markup',
      '#markup' => t('hello @user',[
        '@user' => $username,
      ]),
    ];
  }
}

?>