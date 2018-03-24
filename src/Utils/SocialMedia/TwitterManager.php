<?php

namespace Drupal\social_networks\Utils\SocialMedia;

use Abraham\TwitterOAuth\TwitterOAuth;

/**
 * Class TwitterManager.
 *
 * @category Service
 * @author Adfab <dev@adfab.fr>
 * @license All right reserved
 * @link Null
 * @package Drupal\social_networks
 */
class TwitterManager extends AbstractSocialMedia {

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
    // Connection to Twitter API.
    $connection = $this->connection();

    // Get Count.
    if ($connection) {
      $content = (array) $connection->get(
        "account/verify_credentials",
        ['screen_name' => '']
      );
    } else {
      $content['followers_count']
        = \Drupal::cache()->get('twitter_count')->data;
    }

    return $this->count = $content['followers_count'];
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
    // Connection to Twitter API.
    $connection = $this->connection();

    // Get Feed.
    $content = (array) $connection->get(
      "statuses/user_timeline",
      [
        'screen_name' => $this->user,
        'exclude_replies' => TRUE,
      ]
    );

    // Get Avatar.
    $this->getAvatar($connection);

    // Feed Array Constructor.
    $i = 0;

    foreach ($content as $item) {
      if ($i < $params['start'] || $i >= ($params['start'] + $params['count'])) {
        $i++;
        continue;
      }
      $this->content[] = $item;
      $i++;
    }

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
    // Connection to Twitter API.
    $connection = $this->connection();

    // Get Avatar.
    $this->getAvatar($connection);

    $term = $params['term'];

    $content = $connection->get(
      "search/tweets",
      [
        "q" => "$term from:$this->user",
        "result_type" => "recent",
        "lang" => $params['lang'],
        "exclude_replies" => TRUE,
      ]
    );

    $this->content = $content->statuses;
  }

  /**
   * Convert data structure to a standard array.
   *
   * @return array
   *    Return refactored news feed as array.
   */
  public function toArray(): array {

    $tweets = [];

    foreach ($this->content as $item) {

      // Add <a> to url in content string.
      $patternUrl = "/https:\/\/t.co\/[A-Za-z0-9-]{10}/";

      $text = $item->text;

      if (preg_match_all($patternUrl, $text, $matches)) {
        foreach ($matches[0] as $match) {
          $replace = '<a href="' . $match . '" target="_blank" class="tw">' . $match . '</a>';
          $text = str_replace($match, $replace, $text);
        }
      }
      $tweets[] = [
        'type' => 'twitter2',
        'id' => $this->user,
        'avatar' => $this->avatar['profile_image_url_https'],
        'language' => $item->lang,
        'created' => strtotime($item->created_at),
        'content' => $text,
        'media' => $item->entities->urls[0]->display_url,
        'link' => $item->entities->urls[0]->url,
      ];

    }

    return $tweets;
  }

  /**
   * Get account avatar.
   */
  private function getAvatar($connection) {
    // Get Avatar.
    $this->avatar = (array) $connection->get(
      'users/show',
      ['screen_name' => $this->user]
    );
  }

  /**
   * Twitter API connection.
   *
   * @return \Abraham\TwitterOAuth\TwitterOAuth
   *    Return $connection.
   */
  private function connection() {
    // Config.
    $config = \Drupal::config('social_networks.settings');
    $this->user = $config->get('twitter_id');

    // Set needed variables for the request.
    $consumer_key = $config->get('twitter_key');
    $consumer_secret = $config->get('twitter_secret');
    $access_token = $config->get('twitter_token');
    $access_token_secret = $config->get('twitter_token_secret');

    // Connection.
    $connection = new TwitterOAuth(
      $consumer_key,
      $consumer_secret,
      $access_token,
      $access_token_secret
    );

    return $connection;
  }

}
