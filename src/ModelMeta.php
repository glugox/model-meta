<?php

namespace Glugox\ModelMeta;

abstract class ModelMeta
{
    /**
     * @return Field[]
     */
    abstract public function fields(): array;
}