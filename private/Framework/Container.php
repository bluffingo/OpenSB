<?php
/*
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace OpenSB\Framework;

class Container {
    private $bindings = [];

    public function get($key) {
        if (!array_key_exists($key, $this->bindings)) {
            throw new \Exception("This method hasn't been binded.");
        }

        $resolver = $this->bindings[$key];
        return call_user_func($resolver, $this);
    }

    public function set($key, $resolve) {
        if (isset($this->bindings[$key])) {
            throw new \Exception("You've already binded this method!");
        }

        $this->bindings[$key] = $resolve;
    }
}
