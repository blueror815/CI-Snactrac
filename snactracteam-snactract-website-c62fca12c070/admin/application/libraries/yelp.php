<?php

/**
 * Yelp api wrapper - Converted from sample.php at from https://github.com/Yelp/yelp-api/tree/master/v2/php
 */

// Enter the path that the oauth library is in relation to the php file
require_once('oauth/OAuth.php');

class Yelp{

  // OAuth credentials from - http://www.yelp.com/developers/ - look under manage api keys
  const CONSUMER_KEY = '0FuVJdQ3SiQ6xH_24KX3CA';
  const CONSUMER_SECRET = 'fQkHFvSHl1sIzr9NqkuFY25TeLI';
  const TOKEN = 'FP-rSQ8RBCzH0epBOsX2D_5A4w51DxQ8';
  const TOKEN_SECRET = '--vnClRTIrX45sSpNClKvQQg-8w';
  const API_HOST = 'api.yelp.com';
  const DEFAULT_TERM = 'dinner';
  const DEFAULT_LOCATION = 'San Francisco, CA';
  const SEARCH_LIMIT = 3;
  const SEARCH_PATH = '/v2/search/';
  const BUSINESS_PATH = '/v2/business/';
  
  /** 
   * Makes a request to the Yelp API and returns the response
   * 
   * @param    $host    The domain host of the API 
   * @param    $path    The path of the APi after the domain
   * @return   The JSON response from the request      
   */
  private static function request($host, $path) {
      $unsigned_url = "http://" . $host . $path;
  
      // Token object built using the OAuth library
      $token = new OAuthToken(self::TOKEN, self::TOKEN_SECRET);
  
      // Consumer object built using the OAuth library
      $consumer = new OAuthConsumer(self::CONSUMER_KEY, self::CONSUMER_SECRET);
  
      // Yelp uses HMAC SHA1 encoding
      $signature_method = new OAuthSignatureMethod_HMAC_SHA1();
  
      $oauthrequest = OAuthRequest::from_consumer_and_token(
          $consumer, 
          $token, 
          'GET', 
          $unsigned_url
      );
      
      // Sign the request
      $oauthrequest->sign_request($signature_method, $consumer, $token);
      
      // Get the signed URL
      $signed_url = $oauthrequest->to_url();
      
      // Send Yelp API Call
      $ch = curl_init($signed_url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_HEADER, 0);
      $data = curl_exec($ch);
      curl_close($ch);
      
      return $data;
  }
  
  /**
   * Query the Search API by a search term and location 
   * 
   * @param    $term        The search term passed to the API 
   * @param    $location    The search location passed to the API 
   * @return   The JSON response from the request 
   */
  public static function search($term, $location) {
      $url_params = array();
      
      $url_params['term'] = $term ?: self::DEFAULT_TERM;
      $url_params['location'] = $location?: self::DEFAULT_LOCATION;
      $url_params['limit'] = self::SEARCH_LIMIT;
      $search_path = self::SEARCH_PATH . "?" . http_build_query($url_params);
      
      return self::request(self::API_HOST, $search_path);
  }
  
  /**
   * Query the Business API by business_id
   * 
   * @param    $business_id    The ID of the business to query
   * @return   The JSON response from the request 
   */
  public static function get_business($business_id) {
      $business_path = self::BUSINESS_PATH . $business_id;
      
      return json_decode(self::request(self::API_HOST, $business_path));
  }
  
  /**
   * Queries the API by the input values from the user. 
   * Example usage: Yelp::test('bars', 'San Francisco, CA'); 
   * @param    $term        The search term to query
   * @param    $location    The location of the business to query
   */
  public static function test($term, $location) {     
      $response = json_decode(self::search($term, $location));
      $business_id = $response->businesses[0]->id;
      
      error_log(sprintf(
          "YELP: %d businesses found, querying business info for the top result \"%s\"\n\n",         
          count($response->businesses),
          $business_id
      ));
      
      $response = self::get_business($business_id);
      
      error_log("YELP: Result for business \"{$business_id}\" found:");
      error_log($response);
  }
}


