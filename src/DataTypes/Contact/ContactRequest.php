<?php

declare(strict_types = 1);

namespace Cheppers\MiniCrm\DataTypes\Contact;

use Cheppers\MiniCrm\DataTypes\RequestBase;

class ContactRequest extends RequestBase
{
    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        return $this;
    }
}
