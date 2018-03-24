<?php

namespace Drupal\social_networks\Utils\SocialMedia;

/**
 * Class LinkedInManager.
 *
 * @category Service
 * @author Adfab <dev@adfab.fr>
 * @license All right reserved
 * @link Null
 * @package Drupal\social_networks
 */
class LinkedInManager extends AbstractSocialMedia {

  protected $id;
  protected $key;
  protected $context;

  protected $count;
  protected $user;
  protected $content;
  protected $avatar;

  /**
   * Get count.
   *
   * @return mixed
   *    Return number of likes as a string.
   */
  public function getCount() {
    // Connection.
    $this->connection();

    // Get Count.
    $url = "https://api.linkedin.com/v1/companies/$this->id/num-followers?format=json";
    $get = file_get_contents($url, FALSE, $this->context);
    $count = json_decode($get, TRUE);

    return $count;
  }

  /**
   * Import posts feed.
   *
   * @param array $params
   *    Array of parameters :
   *    - start
   *    - count.
   *
   * @return mixed
   *    Array of posts.
   */
  public function import($params) {
    // Connection.
    $this->connection();

    // Get Feed.
    $start = $params['start'];
    $count = $params['count'];
    $url = "https://api.linkedin.com/v1/companies/$this->id/updates?event-type=status-update&format=json&start=$start&count=$count";
    $get = file_get_contents($url, FALSE, $this->context);
    $content = json_decode($get, TRUE);

    // Get Avatar.
    $this->getAvatar();

    $this->content = $content['values'];
  }

  /**
   * Import posts feed relative to the term.
   *
   * @param array $params
   *    Array of parameters :
   *    - start
   *    - count.
   *
   * @return mixed
   *    Array of posts as JSON.
   */
  public function hashtag($params) {
    $this->import($params);
  }

  /**
   * Convert data structure to a standard array.
   *
   * @return array
   *    Return refactored news feed as array.
   */
  public function toArray(): array {
    $newsFeed = [];

    foreach ($this->content as $item) {
      $created = date('d/m/Y', substr($item['timestamp'], 0, -3));
      $newsFeed[] = [
        'type' => 'linkedin2',
        'id' => $item['updateContent']['company']['name'],
        'avatar' => $this->avatar,
        'created' => $created,
        'content' => $item['updateContent']['companyStatusUpdate']['share']['comment'],
        'thumbnail' => $item['updateContent']['companyStatusUpdate']['share']['content']['thumbnailUrl'],
        'image' => $item['updateContent']['companyStatusUpdate']['share']['content']['submittedImageUrl'],
        'link' => 'https://www.linkedin.com/company-beta/' . $item['updateContent']['company']['id'],
        'likes' => $item['numLikes'],
      ];
    }

    return $newsFeed;
  }

  /**
   * Get account avatar.
   */
  private function getAvatar() {
    // Get Avatar.
    $url = "https://api.linkedin.com/v1/companies/$this->id/logo-url?format=json";
    $get = file_get_contents($url, FALSE, $this->context);
    $this->avatar = json_decode($get, TRUE);
  }

  /**
   * Connection to the API.
   */
  private function connection() {
    // Config.
    $config = \Drupal::config('social_networks.settings');
    $this->id = $config->get('linkedin_id');
    $this->key = $config->get('linkedin_token');

    $authorization = "Authorization: Bearer $this->key";

    $this->context = stream_context_create(
      array(
        'http' => array(
          'method' => 'GET',
          'header' => $authorization,
        ),
      )
    );
  }

  /**
   * @param $code
   *
   * @return mixed
   */
  public function getToken($code) {
    $config = \Drupal::config('social_networks.settings');
    $clientId = $config->get('linkedin_client_id');
    $clientSecret = $config->get('linkedin_client_secret');
    $redirectUri = $config->get('linkedin_redirect_uri');
    $accessUrl
      = "https://www.linkedin.com/oauth/v2/accessToken?grant_type=authorization_code&code=$code&client_id=$clientId&client_secret=$clientSecret&redirect_uri=$redirectUri";

    $get = file_get_contents($accessUrl, FALSE);
    $data = json_decode($get, TRUE);

    return $data['access_token'];
  }

}
