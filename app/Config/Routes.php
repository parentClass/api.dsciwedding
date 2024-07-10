<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// faqs
$routes->get('/faqs/list', 'Faq::list', ['filter' => 'cors']);
$routes->get('/faqs/(:alpha)', 'Faq::faqLookupByCategory/$1', ['filter' => 'cors']);

// rsvp
$routes->post('/rsvp', 'Rsvp::record', ['filter' => 'cors']);
$routes->get('/rsvp/list', 'Rsvp::list', ['filter' => 'cors']);
$routes->post('/rsvp/approve', 'Rsvp::approve', ['filter' => 'cors']);
$routes->get('/rsvp/stats', 'Rsvp::stats', ['filter' => 'cors']);
$routes->post('/rsvp/decline', 'Rsvp::decline', ['filter' => 'cors']);

// invitation
$routes->get('/download/invitation', 'Home::download', ['filter' => 'cors']);