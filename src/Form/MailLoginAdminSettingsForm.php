<?php

namespace Drupal\mail_login\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure Mail Login settings.
 */
class MailLoginAdminSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'mail_login_form_admin_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['mail_login.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = \Drupal::service('config.factory')->getEditable('mail_login.settings');

    $form['general'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('General Configurations'),
      '#open' => TRUE,
    ];

    $form['general']['mail_login_enabled'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Enable login by email address'),
      '#default_value' => $config->get('mail_login_enabled'),
      '#description' => $this->t('This option enables login by email address.'),
    ];

    $form['general']['mail_login_override_login_labels'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Override login form'),
      '#default_value' => $config->get('mail_login_override_login_labels'),
      '#states' => [
        'visible' => [
          ':input[name="mail_login_enabled"]' => ['checked' => TRUE],
        ],
      ],
      '#description' => $this->t('This option allows you to override the login form username title/description.'),
    ];

    $form['general']['mail_login_username_title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Login form username label'),
      '#default_value' => $config->get('mail_login_username_title'),
      '#states' => [
        'required' => [
          ':input[name="mail_login_override_login_labels"]' => ['checked' => TRUE, 'visible' => TRUE],
        ],
        'visible' => [
          ':input[name="mail_login_override_login_labels"]' => ['checked' => TRUE, 'visible' => TRUE],
        ],
      ],
      '#description' => $this->t('Override the username field title.'),
    ];

    $form['general']['mail_login_username_description'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Login form username description'),
      '#default_value' => $config->get('mail_login_username_description'),
      '#states' => [
        'required' => [
          ':input[name="mail_login_override_login_labels"]' => ['checked' => TRUE, 'visible' => TRUE],
        ],
        'visible' => [
          ':input[name="mail_login_override_login_labels"]' => ['checked' => TRUE, 'visible' => TRUE],
        ],
      ],
      '#description' => $this->t('Override the username field description.'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('mail_login.settings');
    $config
      ->set('mail_login_enabled', $form_state->getValue('mail_login_enabled'))
      ->set('mail_login_override_login_labels', $form_state->getValue('mail_login_override_login_labels'))
      ->set('mail_login_username_title', $form_state->getValue('mail_login_username_title'))
      ->set('mail_login_username_description', $form_state->getValue('mail_login_username_description'))
      ->save();

    parent::submitForm($form, $form_state);
  }

}
