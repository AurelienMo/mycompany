<?php

namespace MyCompany\Domain\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;
use Symfony\Component\Serializer\Annotation\Groups;

#[Table]
#[Entity]
class Product extends AbstractEntity
{
    public const GROUPS_SERIALIZATION_LIST = "list:products";
    public const GROUPS_SERIALIZATION_DETAIL = "detail:product";

    #[Column(type: 'string')]
    #[Groups([self::GROUPS_SERIALIZATION_LIST, self::GROUPS_SERIALIZATION_DETAIL])]
    private string $ref;

    #[Column(type: 'string')]
    #[Groups([self::GROUPS_SERIALIZATION_LIST, self::GROUPS_SERIALIZATION_DETAIL])]
    private string $description;

    #[Column(type: 'float')]
    #[Groups([self::GROUPS_SERIALIZATION_LIST, self::GROUPS_SERIALIZATION_DETAIL])]
    private float $unitPrice;

    #[ManyToOne(targetEntity: Company::class)]
    #[JoinColumn(name: 'company_id', referencedColumnName: 'id')]
    private Company $company;

    public function __construct(
        Company $company,
        string $ref,
        string $description,
        float $unitPrice
    ) {
        $this->company = $company;
        $this->ref = $ref;
        $this->description = $description;
        $this->unitPrice = $unitPrice;
        parent::__construct();
    }

    /**
     * @return string
     */
    public function getRef(): string
    {
        return $this->ref;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return float
     */
    public function getUnitPrice(): float
    {
        return $this->unitPrice;
    }

    /**
     * @return Company
     */
    public function getCompany(): Company
    {
        return $this->company;
    }
}
