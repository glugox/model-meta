<?php

namespace Glugox\ModelMeta\Filters;

use Glugox\ModelMeta\Filter;
use Glugox\ModelMeta\FilterType;

class BaseFilter extends Filter {

    public function __construct(
        string $name,
        FilterType $type
    ) {
        parent::__construct($name, $type);
    }
}