<?php

namespace gamringer\Router;

interface Ventureable
{
    public function getName();

    public function match($target, &$extract = null);
}
