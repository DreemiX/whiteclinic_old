<?php
defined( 'ABSPATH' ) || exit;

$rocket_cookie_hash = '39b2a0f4aa7f3cb379add8dfa4b62e4a';
$rocket_logged_in_cookie = 'wordpress_logged_in_39b2a0f4aa7f3cb379add8dfa4b62e4a';
$rocket_cache_mobile_files_tablet = 'desktop';
$rocket_cache_mobile = 1;
$rocket_do_caching_mobile_files = 1;
$rocket_cache_webp = 1;
$rocket_cache_reject_uri = '/(.+/)?feed/?.+/?|/(?:.+/)?embed/|/(index\.php/)?wp\-json(/.*|$)|/white-admin/';
$rocket_cache_reject_cookies = 'wordpress_logged_in_.+|wp-postpass_|wptouch_switch_toggle|comment_author_|comment_author_email_';
$rocket_cache_reject_ua = 'facebookexternalhit';
$rocket_cache_query_strings = array(
  0 => 'q',
);
$rocket_secret_cache_key = '61504aa6d4c4e707594600';
$rocket_cache_ssl = 1;
$rocket_cache_ignored_parameters = array(
  'utm_source' => 1,
  'utm_medium' => 1,
  'utm_campaign' => 1,
  'utm_expid' => 1,
  'utm_term' => 1,
  'utm_content' => 1,
  'fb_action_ids' => 1,
  'fb_action_types' => 1,
  'fb_source' => 1,
  'fbclid' => 1,
  'gclid' => 1,
  'age-verified' => 1,
  'ao_noptimize' => 1,
  'usqp' => 1,
  'cn-reloaded' => 1,
  '_ga' => 1,
);
$rocket_cache_mandatory_cookies = '';
$rocket_cache_dynamic_cookies = array();
