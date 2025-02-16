<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;

enum UserRoles: string implements HasLabel, HasColor, HasIcon
{
    case Admin     = '1';
    case Staff     = '4'; // Not implemented in app yet, this role would only see their own tenant data, apply global scope in models for this or resolve through policies.
    case Mechanic  = '2';
    case Customer  = '3';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Admin     => __('user_roles.admin'),
            self::Staff     => __('user_roles.staff'),
            self::Mechanic  => __('user_roles.mechanic'),
            self::Customer  => __('user_roles.customer'),
        };
    }

    public function getColor(): ?string
    {
        return match ($this) {
            self::Admin     => 'info',
            self::Staff     => 'warning',
            self::Mechanic  => 'primary',
            self::Customer  => 'success',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::Admin     => 'heroicon-o-user',
            self::Staff     => 'heroicon-o-users',
            self::Mechanic  => 'heroicon-o-wrench',
            self::Customer  => 'icon-bike',
        };
    }

    public function getDescription(): ?string
    {
        return match ($this) {
            self::Admin     => __('user_roles.description.admin'),
            self::Staff     => __('user_roles.description.staff'),
            self::Mechanic  => __('user_roles.description.mechanic'),
            self::Customer  => __('user_roles.description.customer'),
        };
    }
}
