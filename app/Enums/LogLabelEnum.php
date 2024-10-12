<?php

namespace App\Enums;

enum LogLabelEnum: string
{
    case INDEX = 'Index';
    case LIST = 'List';
    case CREATE = 'Create';
    case STORE = 'Store';
    case SHOW = 'Show';
    case EDIT = 'Edit';
    case UPDATE = 'Update';
    case RESOLVED = 'Resolved';
    case DELETE = 'Delete';
}
