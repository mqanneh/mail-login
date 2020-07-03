<?php

namespace Drupal\mail_login;

use Drupal\user\UserAuthInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;

/**
 * Validates user authentication credentials.
 */
class AuthDecorator implements UserAuthInterface {

  /**
   * The original user authentication service.
   *
   * @var \Drupal\user\UserAuthInterface
   */
  protected $userAuth;

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Constructs a UserAuth object.
   *
   * @param \Drupal\user\UserAuthInterface $user_auth
   *   The original user authentication service.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_managerk
   *   The entity type manager.
   */
  public function __construct(UserAuthInterface $user_auth, EntityTypeManagerInterface $entity_type_manager) {
    $this->userAuth = $user_auth;
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public function authenticate($username, $password) {
    // If we have an email lookup the username by email.
    if (!empty($username) && filter_var($username, FILTER_VALIDATE_EMAIL)) {
      $account_search = $this->entityTypeManager->getStorage('user')->loadByProperties(['mail' => $username]);

      if ($account = reset($account_search)) {
        $username = $account->getAccountName();
      }
    }

    return $this->userAuth->authenticate($username, $password);
  }

}
