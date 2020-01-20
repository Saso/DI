<?php
declare(strict_types=1);

namespace Saso\DI;

final class DI {
    static private $instance;
    private $objects = array();

    private function __construct() {}

    static private function getInstance() {
        if (!self::$instance instanceof self) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    // static interface
    // $creationParams are used only with first call, upon creation and ignored after that
    static public function get( string $objName, array $creationParams=array(), string $context='production' ) {
        $di = self::getInstance();
        $objId = md5("{$objName}-".json_encode($creationParams)."-{$context}");
        if ( !array_key_exists( $objId, $di->objects )) {
            $factory = $di->factory( $objName, $creationParams, $context );
            $di->objects[$objId] = $factory->$context(); // actual object creation
            $factory->afterCreate($di->objects[$objId]); // for some additional injecting
        }
        return $di->objects[$objId];
    }

    static public function status() {
        $di = self::getInstance();
        foreach($di->objects as $id=>$obj) {
            $className = get_class($obj);
            echo "\t{$id} => {$className}\n";
        }
    }

    // object interface
    private function factory( $objName, $creationParams, $context ) {
        $creatorClass = "\\DI\\Creators\\{$objName}"; // hard-coded class names
        $creator = new $creatorClass( $creationParams );
        return $creator;
        //return $creator->$context();  // create actual obj - no params allowed here!
    }

}
