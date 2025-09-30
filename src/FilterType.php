<?php

namespace Glugox\ModelMeta;

enum FilterType: string
{
    case STRING = 'string';
    case NUMBER = 'number';

    case ENUM = 'enum';
    case MANY = 'many';
    case RANGE = 'range';
    case DATE_RANGE = 'date_range';
    case TIMESTAMP = 'timestamp'; // Timestamp picker
    case BOOLEAN = 'boolean';
}