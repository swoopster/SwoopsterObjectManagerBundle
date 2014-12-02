Installation
============

Step 1: Download the Bundle
---------------------------

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```bash
$ composer require swoopster/objectmanagerbundle "~0.2"
```

This command requires you to have Composer installed globally, as explained
in the [installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

Step 2: Enable the Bundle
-------------------------

Then, enable the bundle by adding the following line in the `app/AppKernel.php`
file of your project:


    <?php
    // app/AppKernel.php

    class AppKernel extends Kernel
    {
        public function registerBundles()
        {
    
            $bundles = array(
                new Swoopster\ObjectManagerBundle\SwoopsterObjectManagerBundle()
            );
        
        }

    }

Step 3: Configure Bundle
------------------------

Define coniguration parameters to customize bundle for your own needs. You need to define which bundles should be managed
by the ObjectManager. Currently this bundle supports only the xml-mapping-format.


    swoopster_object_manager:
        model_dir: Model #default
        mapping_format: xml #default
        bundles:
            - {namespace: Swoopster\TestBundle}

#Usage

## Define Mappings

Define your mapping definitions inside `BundleFolder/Resources/config/doctrine/model`. Currently only xml mappings supported!

## Use Manager

To get the manager use `$container->get('id')` or use the factory. To use the factory the manager service has to be tagged with 
the following tag:

    <tag name="swoopster.object_manager"/>
    
Now you can get an Manager by using the `$conatainer->get('swoopster.object_manager.manager_factory')->getManager($instanceOfManagedObject)`

### Standard Manager

To use a manager for an object without customization, define a service like below

    <services>
        <service id="swoopster_test.model.test_object_manager" parent="swoopster.object_manager.doctrine_manager">
            <argument>Swoopster\TestBundle\Model\TestModel</argument>
        </service>
    </services>
    
### Customizable Manager

To extend the standardmanager define a manager class which extends from `Swoopster\ObjectManagerBundle\Doctrine\DoctrineManager`
and register this class as a service. see example below

    <services>
        <service id="swoopster_test.model.test_object_manager" class="Swoopster\TestBundle\Model\TestObjectManager" parent="swoopster.object_manager.doctrine_manager">
            <argument>Swoopster\TestBundle\Model\TestModel</argument>
        </service>
    </services>
    
## Manager Functions

### Create

Get a new instance of managed modelclass

    $manager->create()

### Save

save or update an instance of managed modelclass. Second argument defines if orm should persist object immediately or later, default 
is true (immediately).

    $manager->save($object, $flush)
    
### Delete

delete an existing instance of managed modelclass

    $manager->delete($object, $flush)

### Find

the manager supports a predefined set of methods to find objects in database. you can find supported functions in `Swoopster\ObjectManagerBundle\Model\ManagerInterface`

    $manager->find*()
    
## Lifecycle Events

To use the lifecycle events you need to comply with the following requirments

1. the model to be managed must implement `Swoopster\ObjectManagerBundle\Model\EventDrivenModelInterface`
1. the manager belonging to this model must implement `Swoopster\ObjectManagerBundle\Model\ManagerEventInterface`
1. manager must be registered in the factory

### Supported Events

All events are expecting an instance of the managed model as argument

- prePersist
- postPersit
- preUpdate
- postUpdate
- preRemove
- postRemove
- postLoad
    
License
-------

This bundle is under the MIT license. See the complete license in the bundle:

    Resources/meta/LICENSE