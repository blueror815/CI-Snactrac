<?php

require 'Geocoder/src/autoload.php';

/**
 * Handles location changes and provide geo location related services
 * @author Naveed Khan
 */
class Location {

  /**
   * Country constants
   */
  const COUNTRY_US = 'us';
  const COUNTRY_CANADA = 'ca';
  const COUNTRY_MEXICO = 'mx';
  const COUNTR_UK = 'uk';
  
  public static $countries = array(
  	self::COUNTRY_US => 'United States',
    self::COUNTRY_CANADA => 'Canada',
    self::COUNTRY_MEXICO => 'Mexico',
    self::COUNTR_UK => 'United Kingdom'
  );

  /**
   * Metro area constants
   */
  const METRO_AREA_SF_BAY = 1;
  const METRO_AREA_NEW_YORK = 2;
  const METRO_AREA_LOS_ANGELES = 3;
  const METRO_AREA_SEATTLE = 4;
  const METRO_AREA_PORTLAND = 5;
  const METRO_AREA_CHICAGO = 6;
  
  private static $metro_areas = array(
    self::METRO_AREA_SF_BAY => 
      array(
        'title' => 'San Francisco Bay Area',
        'country' => self::COUNTRY_US,
        'longitude' => 37.615834,
        'latitude' => -122.231167
      ),
    self::METRO_AREA_NEW_YORK => 
      array(
        'title' =>'New York City',
        'country' => self::COUNTRY_US,
        'longitude' => 0,
        'latitude' => 0
      ),
    self::METRO_AREA_LOS_ANGELES => 
      array(
        'title' =>'Los Angeles',
        'country' => self::COUNTRY_US,
        'longitude' => 34.0204989,
        'latitude' => -118.4117325
      ),
    self::METRO_AREA_SEATTLE => 
      array(
        'title' =>'Seattle',
        'country' => self::COUNTRY_US,
        'longitude' => 0,
        'latitude' => 0
      ),
    self::METRO_AREA_PORTLAND => 
      array(
        'title' =>'Portland',
        'country' => self::COUNTRY_US,
        'longitude' => 0,
        'latitude' => 0
      ),
    self::METRO_AREA_CHICAGO => 
      array(
        'title' =>'Chicago',
        'country' => self::COUNTRY_US,
        'longitude' => 0,
        'latitude' => 0
      )
  );
  
  /**
   * DB table name constants
   */
  const TABLE_LOCATIONS = 'locations';
  const TABLE_USER_LOCATIONS = 'user_locations';
  
  /**
   * Get the nearest metro area from the given point
   * @param string $latitude
   * @param string $longittude
   * @return string
   */
  public function findMetroArea($latitude, $longittude){
    return self::METRO_AREA_SF_BAY;
  }
  
  /**
   * Get the metro info for the given id
   * @param unknown $metroId
   * @return multitype:multitype:string number
   */
  public function getMetroInfo($metroId){
    if(empty($metroId)){
      $metroId = self::METRO_AREA_SF_BAY;
    }
    return self::$metro_areas[$metroId];
  }
  
  /**
   * Checks for valid and supported metro area
   * @param int $metroId
   * @return boolean
   */
  public function checkMetro($metroId){
    return (array_search($metroId, array(self::METRO_AREA_SF_BAY)) !== false);
  }
  
  /**
   * gecode an address using google maps api
   * @param string $address
   * @return array string
   */
  public function geocodeByAddress($street, $city, $state, $zip, $country = self::COUNTRY_US){
    
    $address = "{$street} {$city} {$state} {$zip}";
    if(empty($street) or empty($city) or empty($state) or empty($zip)){
      return array(null, null);
    }
        
    $ret = array();
    try {
      $adapter  = new \Geocoder\HttpAdapter\CurlHttpAdapter();
      $geocoder = new \Geocoder\Geocoder();
      $geocoder->registerProvider(new \Geocoder\Provider\GoogleMapsProvider($adapter, 'en_US'));
      $result = $geocoder->geocode($address);
      $ret = $result->getCoordinates();
      log_message("info", "GEO CODE RESULTS FOR {$address}: " . json_encode($ret));      
    }
    catch (Exception $e) {
      error_log("-- CAUGHT GEOCODER EXCEPTION -- ADDRESS: {$address} -- ERROR:" . $e->getMessage());
      $ret = array(null, null);
    }
    return $ret;
  }
  
  /**
   * geocode based on ip address
   * @param string $ip
   */
  public function geocodeByIp($ip){
    
    if(empty($ip) or $ip == '0.0.0.0'){
      return array(null, null);
    }
    
    return array(
    	37.7939879, //latitude
      -122.3949541  //longitude
    );
  }
  
  /**
   * Get direct line distance between two points on earth
   * @param array $start
   * @param array $end
   * @param int $round
   * @return number
   */
  public function distance($start, $end, $round = 1){
    $distance = (3958 * 3.1415926 * sqrt(
                ($start['latitude'] - $end['latitude'])
                * ($start['latitude'] - $end['latitude'])
                + cos($start['latitude'] / 57.29578)
                * cos($start['latitude'] / 57.29578)
                * ($start['longitude'] - $end['longitude'])
                * ($start['longitude'] - $end['longitude'])
        ) / 180);

    return round($distance, $round);
  }
  
  /**
   * Gets summary of a location
   * @param Location $location
   * @return string
   */
  public function summary($location){
    return "{$location->street}, {$location->city}, {$location->state}";
  }
  
  /**
   * Add a new location and return its id
   * @param string $street
   * @param string $city
   * @param string $state
   * @param string $zip
   * @param string $country
   */
  public function add($controller, $street, $city, $state, $zip, $country = self::COUNTRY_US, $latitude = null, $longitude = null){
    
    // geo code this address
    if(empty($latitude) or empty($longitude)){
      list($latitude, $longitude) = $this->geocodeByAddress($street, $city, $state, $zip, $country);      
    }
    
    // insert new address into db
    $controller->db->insert(
    	self::TABLE_LOCATIONS,
      array(
    	 'street' => $street,
       'street2' => null,
       'city' => $city,
       'state' => $state,
       'zip' => $zip,
       'country' => $country,
       'latitude' => $latitude,
       'longitude' => $longitude
    )
    );
    
    if($controller->db->_error_number()){
      return false;
    }

    $id = $controller->db->insert_id();
    return $id;
  }
  
  /**
   * delete a location
   * @param unknown $locationId
   */
  public function delete($controller, $locationId){
   return $controller->db->delete(
    	self::TABLE_LOCATIONS,
      array('id' => $locationId)
   );
  }
  
  /**
   * update a location with new data
   * @param unknown $locationId
   * @param unknown $street
   * @param unknown $city
   * @param unknown $state
   * @param unknown $zip
   * @param unknown $country
   */
  public function update($controller, $locationId, $street, $city, $state, $zip, $country = self::COUNTRY_US){
    
  }
}