<?php
class SUT
{
    public function __call($method, $arguments)
    {
        throw new Exception("Not implemented.");
    }
}
