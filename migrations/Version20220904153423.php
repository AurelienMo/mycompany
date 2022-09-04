<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220904153423 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product CHANGE description description LONGTEXT NOT NULL');
        $this->addSql('ALTER TABLE product RENAME INDEX idx_product_company TO IDX_D34A04AD979B1AD6');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product CHANGE description description VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE product RENAME INDEX idx_d34a04ad979b1ad6 TO IDX_product_company');
    }
}
