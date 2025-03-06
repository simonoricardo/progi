<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Config\FrameworkConfig;

return static function (ContainerConfigurator $containerConfigurator, FrameworkConfig $framework): void {
  $containerConfigurator->extension('framework', [
    'secret' => '%env(APP_SECRET)%',
    'session' => true,
  ]);
  if ($containerConfigurator->env() === 'test') {
    $containerConfigurator->extension('framework', [
      'test' => true,
      'session' => [
        'storage_factory_id' => 'session.storage.factory.mock_file',
      ],
    ]);
  }

  $framework->session()
    ->enabled(true)
    ->handlerId(null)
    ->cookieSecure('auto')
    ->cookieSamesite(Cookie::SAMESITE_LAX)
    ->handlerId('session.handler.native_file')
    ->savePath('%kernel.project_dir%/var/sessions/%kernel.environment%');
};
