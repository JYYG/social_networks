<?php

namespace Drupal\social_networks\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Drupal\Core\Url;

/**
 * Class SocialController.
 *
 * @category Controller
 * @author Adfab <dev@adfab.fr>
 * @license All right reserved
 * @link Null
 * @package Drupal\social_networks\Controller
 */
class SocialController {

  /**
   * Show social networks count.
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   *    Return a JSON array.
   */
  public static function showSocialCounts(Request $request) {
    $config = \Drupal::config('social_networks.settings');
    /** @var \Drupal\social_networks\SocialService $socialService */
    $socialService = \Drupal::service('social_networks.social');
    $params    = $request->query->all();

    switch ($params['type']) {
      case 'twitter2':
        $counts = $socialService->getSocialLikes($socialService::TWITTER);
        break;

      default:
        $counts = [
          $socialService::TWITTER   => [
            'url'    => $config->get('twitter_link'),
            'counts' => $socialService->getSocialLikes($socialService::TWITTER),
          ],
          $socialService::YOUTUBE   => [
            'url'    => $config->get('youtube_link'),
            'counts' => $socialService->getSocialLikes($socialService::YOUTUBE),
          ],
          $socialService::GOOGLE    => [
            'url'    => $config->get('google_link'),
            'counts' => $socialService->getSocialLikes($socialService::GOOGLE),
          ],
          $socialService::LINKEDIN  => [
            'url'    => $config->get('linkedin_link'),
            'counts' => $socialService->getSocialLikes($socialService::LINKEDIN),
          ],
          $socialService::PINTEREST => [
            'url'    => $config->get('pinterest_link'),
            'counts' => $socialService->getSocialLikes($socialService::PINTEREST),
          ],
          $socialService::INSTAGRAM => [
            'url'    => $config->get('instagram_link'),
            'counts' => $socialService->getSocialLikes($socialService::INSTAGRAM),
          ],
        ];
        break;
    }

    return new JsonResponse($counts);
  }

  /**
   * Show main posts.
   *
   * @return JsonResponse
   *    Return a JSON array.
   */
  public static function showMainPosts() {

    /** @var \Drupal\social_networks\SocialService $socialService */
    $socialService = \Drupal::service('social_networks.social');

    // Twitter.
    $twitter = $socialService->getSocialPosts(
      ['start' => 0, 'count' => 3, 'type' => $socialService::TWITTER]
    );
    // LinkedIn.
    $linkedin = $socialService->getSocialPosts(
      ['start' => 0, 'count' => 1, 'type' => $socialService::LINKEDIN]
    );
    // Youtube.
    $youtube = $socialService->getSocialPosts(
      ['start' => 0, 'count' => 1, 'type' => $socialService::YOUTUBE]
    );
    // Instagram.
    $instagram = $socialService->getSocialPosts(
      ['start' => 0, 'count' => 1, 'type' => $socialService::INSTAGRAM]
    );
    // Pinterest.
    $pinterest = $socialService->getSocialPosts(
      ['start' => 0, 'count' => 1, 'type' => $socialService::PINTEREST]
    );
    // Posts.
    $posts = [
      $twitter[0],
      $youtube[0],
      $linkedin[0],
      $twitter[1],
      $twitter[2],
      $instagram[0],
      $pinterest[0],
    ];

    return new JsonResponse($posts);
  }

  /**
   * Show more main posts.
   *
   * @return JsonResponse
   *    Return a JSON array.
   */
  public static function showMoreMainPosts() {

    /** @var \Drupal\social_networks\SocialService $socialService */
    $socialService = \Drupal::service('social_networks.social');

    // Twitter.
    $twitter = $socialService->getSocialPosts(
      ['start' => 3, 'count' => 4, 'type' => $socialService::TWITTER]
    );
    // Youtube.
    $youtube = $socialService->getSocialPosts(
      ['start' => 1, 'count' => 1, 'type' => $socialService::YOUTUBE]
    );
    // Instagram.
    $instagram = $socialService->getSocialPosts(
      ['start' => 1, 'count' => 1, 'type' => $socialService::INSTAGRAM]
    );
    // Pinterest.
    $pinterest = $socialService->getSocialPosts(
      ['start' => 1, 'count' => 1, 'type' => $socialService::PINTEREST]
    );
    // Posts.
    $posts = [
      $twitter[0],
      $youtube[0],
      $twitter[1],
      $twitter[2],
      $twitter[3],
      $instagram[0],
      $pinterest[0],
    ];

    return new JsonResponse($posts);
  }

  /**
   * Show posts with relative hashtag.
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *    Get URL request.
   *
   * @return JsonResponse
   *    Return a JSON array.
   */
  public function showHashtagPosts(Request $request) {
    /** @var \Drupal\social_networks\SocialService $socialService */
    $socialService = \Drupal::service('social_networks.social');
    $parameters    = $request->query->all();
    $posts         = $socialService->getHashtagFeed($parameters);

    return new JsonResponse($posts);
  }

  /**
   * Provide a range of social posts.
   *
   * @param Request $request
   *    The request.
   *
   * @throws \Twig_Error_Loader
   * @throws \Twig_Error_Syntax
   *
   * @return \Symfony\Component\HttpFoundation\Response
   *    An Ajax response
   */
  public function showSocialPosts(Request $request) {
    /** @var \Drupal\social_networks\SocialService $socialService */
    $socialService = \Drupal::service('social_networks.social');
    $params    = $request->query->all();
    $posts         = $socialService->getSocialPosts(
      [
        'start' => ($params['start']) ? $params['start'] : 0,
        'count' => ($params['count']) ? $params['count'] : 10,
        'type'  => $params['type'],
      ]
    );

    return new JsonResponse($posts);
  }

  /**
   * Get Token.
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *
   * @return RedirectResponse
   */
  public function getToken(Request $request) {
    /** @var \Drupal\social_networks\SocialService $socialService */
    $socialService = \Drupal::service('social_networks.social');

    $type = (String) $request->get('type');
    $params = $request->query->all();
    $socialService->saveSocialToken($type, $params);

    return new RedirectResponse(
      '/'.
      Url::fromRoute('social_networks.settings')->getInternalPath() .
      "#edit-$type"
    );
  }
}
