<?php
class Facade
{
    public function __call($method, $args)
    {
        throw new Exception('Not implemented.');
    }
}
