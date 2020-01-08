<?php
declare(strict_types=1);

namespace DI\Creators;

abstract class Base {

    abstract public function production();

    public function __construct($params) {
        $this->params = $params;
    }

    /* // ne nucamo!
    public function __invoke() {
        return $this->production();
    }
    */
}
