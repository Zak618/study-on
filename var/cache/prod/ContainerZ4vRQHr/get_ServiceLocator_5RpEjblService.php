<?php

namespace ContainerZ4vRQHr;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/*
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class get_ServiceLocator_5RpEjblService extends App_KernelProdContainer
{
    /*
     * Gets the private '.service_locator.5RpEjbl' shared service.
     *
     * @return \Symfony\Component\DependencyInjection\ServiceLocator
     */
    public static function do($container, $lazyLoad = true)
    {
        return $container->privates['.service_locator.5RpEjbl'] = new \Symfony\Component\DependencyInjection\Argument\ServiceLocator($container->getService ??= $container->getService(...), [
            'course' => ['privates', '.errored..service_locator.5RpEjbl.App\\Entity\\Course', NULL, 'Cannot autowire service ".service_locator.5RpEjbl": it needs an instance of "App\\Entity\\Course" but this type has been excluded in "config/services.yaml".'],
            'lessonRepository' => ['privates', 'App\\Repository\\LessonRepository', 'getLessonRepositoryService', true],
        ], [
            'course' => 'App\\Entity\\Course',
            'lessonRepository' => 'App\\Repository\\LessonRepository',
        ]);
    }
}
