## DI - Simple Dependency injector

usage: It is singleton object with only `DI::get()` method:  
`Saso\DI::get( string $objDescriptor, array $params=[], string $context='production')`

Example (app code):

    <?php

    // ..some code
    // we need a Log object:
    $log = Saso\DI::get('sysLog', ['log/sysLog.log'], 'production');
    //..we can use it here
    ?>

Creator classes are part of your application and DI finds the classes just by their name (`\DI\Creators\<your_class_here>`)

DI/Creators/sysLog.php:

    <?php
    declare(strict_types=1);

    namespace DI\Creators;

    use Saso\DI\DI;

    final class sysLog extends Base {
        public function production() {
            return new \Service\Log( $this->params[0] );
        }
    }
