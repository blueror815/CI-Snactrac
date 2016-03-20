<?php

/**
 * Return a full url to the assets dir - which is skipped by mod rewrite
 * @param string $uri
 * @return string
 */
function assets_url($uri = ''){
	return base_url().'assets/' . $uri;
}
