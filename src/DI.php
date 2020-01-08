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
        //$objId = "$objName({$context})";
        $objId = md5("{$objName}-".json_encode($creationParams)."-{$context}");
        if ( !array_key_exists( $objId, $di->objects )) {
            $di->objects[$objId] = $di->create( $objName, $creationParams, $context );
        }
        return $di->objects[$objId];
    }

    // object interface
    private function create( $objName, $creationParams, $context ) {
        $creatorClass = "\\DI\\Creators\\{$objName}"; // hard-coded class names
        $creator = new $creatorClass( $creationParams );
        return $creator->$context();  // create actual obj - no params allowed here!
    }

}
