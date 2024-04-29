<?php

namespace App\Enums;

enum TokenEnum: string
{
    case CHANGE_EMAIL = 'change-email';
    case PASSWORD_RESET = 'password-reset';
    case CREATE = 'create';
    case READ = 'read';
    case UPDATE = 'update';
    case DESTROY = 'destroy';
    case CREATE_ROLES_AND_USERS = 'create-roles-and-users';
    case READ_ROLES_AND_USERS = 'read-roles-and-users';
    case UPDATE_ROLES_AND_USERS = 'update-roles-and-users';
    case DESTROY_ROLES_AND_USERS = 'destroy-roles-and-users';
    case ISSUE_TOKENS = 'issue-tokens';
}
