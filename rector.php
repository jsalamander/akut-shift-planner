<?php

use Rector\Core\Configuration\Option;
use Rector\Set\ValueObject\SetList;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();

    $parameters->set(Option::SETS, [
        SetList::SYMFONY_41,
        SetList::SYMFONY_42,
        SetList::SYMFONY_43,
        SetList::SYMFONY_44,
        SetList::SYMFONY_50,
    ]);
};