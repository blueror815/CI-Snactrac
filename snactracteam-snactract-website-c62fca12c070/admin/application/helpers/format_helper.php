<?php 

/**
 * Does not really belong here but hey
 * @param string $input
 * @param number $limit
 */
function format_limit_string($input, $limit = 20){
  if(strlen($input) <= $limit){
    return $input;
  }
  return substr($input, 0, $limit - 3) . '...';
}

/**
 * converts a cents value to price
 * @param float $price
 * @return string
 */
function format_currency($price){
  return '$' . number_format($price/100, 2);
}