<?php

namespace App\Policies;

use Common\Pages\CustomPage;

class LinkPagePolicy extends WorkspacedResourcePolicy
{
    protected $resource = CustomPage::class;
}
