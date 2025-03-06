<?php

use App\Controller\AuctionFeesController;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return function (RoutingConfigurator $routes): void {
  $routes->add('api_auction_fees_calculator', '/api/auction_fees/')
    ->controller([AuctionFeesController::class, 'calculate'])
    ->methods(['POST']);
};
