<?php

namespace Glugox\ModelMeta;

enum FilterType: string
{
    case ENUM = 'enum';
    case MANY = 'many';
    case RANGE = 'range';
    case DATE_RANGE = 'date_range';
    case BOOLEAN = 'boolean';
}