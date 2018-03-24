<?php

namespace Drupal\social_networks\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class SocialSettingsForm.
 *
 * @category Service
 * @author Adfab <dev@adfab.fr>
 * @license All right reserved
 * @link Null
 * @package Drupal\social_networks
 */
class SocialSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'social_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'social_networks.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('social_networks.settings');

    $form['tabs'] = [
      '#type' => 'horizontal_tabs',
      '#tree' => TRUE,
      '#group_name' => 'social_networks',
      '#entity_type' => '',
      '#bundle' => '',
    ];

    // Facebook fields.
    $form['fb_tabs'] = [
      '#tree' => TRUE,
      '#title' => 'Facebook',
    ];

    $form['facebook'] = [
      '#type' => 'details',
      '#title' => t('Facebook'),
      '#group' => 'tabs',
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
    ];

    $form['facebook']['facebook_link'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Facebook Page Link'),
      '#default_value' => $config->get('facebook_link'),
    ];

    $form['facebook']['facebook_id'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Facebook Page ID'),
      '#default_value' => $config->get('facebook_id'),
    ];

    $form['facebook']['facebook_token'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Facebook APP Token'),
      '#default_value' => $config->get('facebook_token'),
    ];

    // Twitter field.
    $form['tw_tab'] = [
      '#tree' => TRUE,
      '#title' => 'Twitter',
    ];

    $form['twitter'] = [
      '#type' => 'details',
      '#title' => t('Twitter'),
      '#group' => 'tabs',
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
    ];

    $form['twitter']['twitter_link'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Twitter Count Link'),
      '#default_value' => $config->get('twitter_link'),
    ];

    $form['twitter']['twitter_id'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Twitter Count ID'),
      '#default_value' => $config->get('twitter_id'),
    ];

    $form['twitter']['twitter_key'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Twitter Consumer Key'),
      '#default_value' => $config->get('twitter_key'),
    ];

    $form['twitter']['twitter_secret'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Twitter Consumer Secret'),
      '#default_value' => $config->get('twitter_secret'),
    ];

    $form['twitter']['twitter_token'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Twitter Access Token'),
      '#default_value' => $config->get('twitter_token'),
    ];

    $form['twitter']['twitter_token_secret'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Twitter Access Token Secret'),
      '#default_value' => $config->get('twitter_token_secret'),
    ];

    // Youtube field.
    $form['yt_tab'] = [
      '#title' => 'Youtube',
    ];

    $form['youtube'] = [
      '#type' => 'details',
      '#title' => t('Youtube'),
      '#group' => 'tabs',
    ];

    $form['youtube']['youtube_link'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Youtube Channel Link'),
      '#default_value' => $config->get('youtube_link'),
    ];

    $form['youtube']['youtube_id'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Youtube Channel ID'),
      '#default_value' => $config->get('youtube_id'),
    ];

    $form['youtube']['youtube_key'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Youtube API Key'),
      '#default_value' => $config->get('youtube_key'),
    ];

    // Google+ field.
    $form['gp_tab'] = [
      '#title' => 'Google+',
    ];

    $form['google'] = [
      '#type' => 'details',
      '#title' => t('Google+'),
      '#group' => 'tabs',
    ];

    $form['google']['google_link'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Google+ Page Link'),
      '#default_value' => $config->get('google_link'),
    ];

    $form['google']['google_id'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Google+ Page ID'),
      '#default_value' => $config->get('google_id'),
    ];

    $form['google']['google_key'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Google+ API key'),
      '#default_value' => $config->get('google_key'),
    ];

    // LinkedIn field.
    $form['li_tab'] = [
      '#title' => 'LinkedIn',
    ];

    $form['linkedin'] = [
      '#type' => 'details',
      '#title' => t('LinkedIn'),
      '#group' => 'tabs',
    ];

    $form['linkedin']['linkedin_link'] = [
      '#type' => 'textfield',
      '#title' => $this->t('LinkedIn Page Link'),
      '#default_value' => $config->get('linkedin_link'),
    ];

    $form['linkedin']['linkedin_id'] = [
      '#type' => 'textfield',
      '#title' => $this->t('LinkedIn Page ID'),
      '#default_value' => $config->get('linkedin_id'),
    ];

    $form['linkedin']['linkedin_client_id'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Client ID'),
      '#default_value' => $config->get('linkedin_client_id'),
    ];

    $form['linkedin']['linkedin_client_secret'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Client Secret'),
      '#default_value' => $config->get('linkedin_client_secret'),
    ];

    $form['linkedin']['linkedin_redirect_uri'] = [
      '#type' => 'textfield',
      '#title' => 'Authorized URL: ',
      '#default_value' => (
        ( isset($_SERVER['HTTPS'])
          ?
          'https://' . $_SERVER['HTTP_HOST']
          :
          'http://'  . $_SERVER['HTTP_HOST'])
        . \Drupal::service('path.current')->getPath() . '/token/linkedin'),
      '#disabled' => TRUE,
      '#description' => t('Indicate this url in the settings "Authorized URL" of the API.')
    ];

    if ($config->get('linkedin_token')) {
      $form['linkedin']['linkedin_token_status'] = [
        '#type' => 'html_tag',
        '#value' => 'Token is set.',
        '#tag' => 'p'
      ];
    }

    $form['linkedin']['linkedin_get_token'] = [
      '#type' => 'button',
      '#value' => (
      (!$config->get('linkedin_token'))
        ?
        $this->t('Get API token')
        :
        $this->t('Refresh API token')
      ),
      '#default_value' => $config->get('linkedin_get_token'),
      '#id' => 'getLinkedinToken',
    ];

    $form['linkedin']['linkedin_token'] = [
      '#type' => 'token',
      '#default_value' => $config->get('linkedin_token'),
    ];

    // Pinterest field.
    $form['pi_tab'] = [
      '#title' => 'Pinterest',
    ];

    $form['pinterest'] = [
      '#type' => 'details',
      '#title' => t('Pinterest'),
      '#group' => 'tabs',
    ];

    $form['pinterest']['pinterest_link'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Pinterest Link'),
      '#default_value' => $config->get('pinterest_link'),
    ];

    $form['pinterest']['pinterest_id'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Pinterest ID'),
      '#default_value' => $config->get('pinterest_id'),
    ];

    $form['pinterest']['pinterest_secret'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Client Secret'),
      '#default_value' => $config->get('pinterest_secret'),
    ];

    $form['pinterest']['pinterest_redirect_uri'] = [
      '#type' => 'textfield',
      '#title' => 'Authorized URL: ',
      '#default_value' => (
        ( isset($_SERVER['HTTPS'])
          ?
          'https://' . $_SERVER['HTTP_HOST']
          :
          'http://'  . $_SERVER['HTTP_HOST'])
        . \Drupal::service('path.current')->getPath() . '/token/pinterest'),
      '#disabled' => TRUE,
      '#description' => t('Indicate this url in the settings "Authorized URL" of the API.')
    ];

    if ($config->get('pinterest_token')) {
      $form['pinterest']['pinterest_token_status'] = [
        '#type' => 'html_tag',
        '#value' => 'Token is set.',
        '#tag' => 'p'
      ];
    }

    $form['pinterest']['pinterest_get_token'] = [
      '#type' => 'button',
      '#value' => (
      (!$config->get('pinterest_token'))
        ?
        $this->t('Get API token')
        :
        $this->t('Refresh API token')
      ),
      '#default_value' => $config->get('pinterest_get_token'),
      '#id' => 'getPinterestToken',
    ];

    $form['pinterest']['pinterest_token'] = [
      '#type' => 'token',
      '#default_value' => $config->get('pinterest_token'),
    ];

    // Instagram field.
    $form['ig_tab'] = [
      '#title' => 'Pinterest',
    ];

    $form['instagram'] = [
      '#type' => 'details',
      '#title' => t('Instagram'),
      '#group' => 'tabs',
    ];

    $form['instagram']['instagram_link'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Instagram Link'),
      '#default_value' => $config->get('instagram_link'),
    ];

    $form['instagram']['instagram_id'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Instagram ID'),
      '#default_value' => $config->get('instagram_id'),
    ];


    $form['instagram']['instagram_secret'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Client Secret'),
      '#default_value' => $config->get('instagram_secret'),
    ];

    $form['instagram']['instagram_redirect_uri'] = [
      '#type' => 'textfield',
      '#title' => 'Authorized URL: ',
      '#default_value' => (
        ( isset($_SERVER['HTTPS'])
          ?
          'https://' . $_SERVER['HTTP_HOST']
          :
          'http://'  . $_SERVER['HTTP_HOST'])
        . \Drupal::service('path.current')->getPath() . '/token/instagram'),
      '#disabled' => TRUE,
      '#description' => t('Indicate this url in the settings "Authorized URL" of the API.')
    ];

    if ($config->get('instagram_token')) {
      $form['instagram']['instagram_token_status'] = [
        '#type' => 'html_tag',
        '#value' => 'Token is set.',
        '#tag' => 'p'
      ];
    }

    $form['instagram']['instagram_get_token'] = [
      '#type' => 'button',
      '#value' => (
      (!$config->get('instagram_token'))
        ?
        $this->t('Get API token')
        :
        $this->t('Refresh API token')
      ),
      '#default_value' => $config->get('instagram_get_token'),
      '#id' => 'getInstagramToken',
    ];

    $form['instagram']['instagram_token'] = [
      '#type' => 'token',
      '#default_value' => $config->get('instagram_token'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Retrieve the configuration.
    $config = $this->config('social_networks.settings');
    // Set the submitted configuration setting.
    $config->set('facebook_link', $form_state->getValue('facebook_link'));
    $config->set('facebook_id', $form_state->getValue('facebook_id'));
    $config->set('facebook_token', $form_state->getValue('facebook_token'));
    $config->set('twitter_link', $form_state->getValue('twitter_link'));
    $config->set('twitter_id', $form_state->getValue('twitter_id'));
    $config->set('twitter_key', $form_state->getValue('twitter_key'));
    $config->set('twitter_secret', $form_state->getValue('twitter_secret'));
    $config->set('twitter_token', $form_state->getValue('twitter_token'));
    $config->set(
      'twitter_token_secret',
      $form_state->getValue('twitter_token_secret')
    );
    $config->set('youtube_link', $form_state->getValue('youtube_link'));
    $config->set('youtube_id', $form_state->getValue('youtube_id'));
    $config->set('youtube_key', $form_state->getValue('youtube_key'));
    $config->set('google_link', $form_state->getValue('google_link'));
    $config->set('google_id', $form_state->getValue('google_id'));
    $config->set('google_key', $form_state->getValue('google_key'));

    $config->set('linkedin_link', $form_state->getValue('linkedin_link'));
    $config->set('linkedin_id', $form_state->getValue('linkedin_id'));
    $config->set('linkedin_client_id', $form_state->getValue('linkedin_client_id'));
    $config->set('linkedin_client_secret', $form_state->getValue('linkedin_client_secret'));
    $config->set('linkedin_redirect_uri', $form_state->getValue('linkedin_redirect_uri'));
    $config->set('linkedin_token', $form_state->getValue('linkedin_token'));

    $config->set('pinterest_link', $form_state->getValue('pinterest_link'));
    $config->set('pinterest_id', $form_state->getValue('pinterest_id'));
    $config->set('pinterest_secret', $form_state->getValue('pinterest_secret'));
    $config->set('pinterest_token', $form_state->getValue('pinterest_token'));

    $config->set('instagram_link', $form_state->getValue('instagram_link'));
    $config->set('instagram_id', $form_state->getValue('instagram_id'));
    $config->set('instagram_secret', $form_state->getValue('instagram_secret'));
    $config->set('instagram_token', $form_state->getValue('instagram_token'));
    $config->save();

    parent::submitForm($form, $form_state);
  }

}
