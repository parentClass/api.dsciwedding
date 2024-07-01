<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/faqs/list', 'Faq::list', ['filter' => 'cors']);
$routes->get('/faqs/(:alpha)', 'Faq::faqLookupByCategory/$1', ['filter' => 'cors']);

$routes->post('/rsvp', 'Rsvp::record', ['filter' => 'cors']);
$routes->get('/rsvp/list', 'Rsvp::list', ['filter' => 'cors']);