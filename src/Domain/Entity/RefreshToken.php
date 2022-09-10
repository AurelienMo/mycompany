<?php

namespace MyCompany\Domain\Entity;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Gesdinet\JWTRefreshTokenBundle\Entity\RefreshToken as BaseRefreshToken;

#[Table(name: "refresh_tokens")]
#[Entity]
class RefreshToken extends BaseRefreshToken
{
}
