<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220910111242 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE company (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', firstname VARCHAR(255) DEFAULT NULL, lastname VARCHAR(255) DEFAULT NULL, company_name VARCHAR(255) DEFAULT NULL, is_freelance TINYINT(1) NOT NULL, vat_number VARCHAR(255) DEFAULT NULL, street_number VARCHAR(255) NOT NULL, street_name VARCHAR(255) NOT NULL, zip_code VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', company_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', ref VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, unit_price DOUBLE PRECISION NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_D34A04AD979B1AD6 (company_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE refresh_tokens (id INT AUTO_INCREMENT NOT NULL, refresh_token VARCHAR(128) NOT NULL, username VARCHAR(255) NOT NULL, valid DATETIME NOT NULL, UNIQUE INDEX UNIQ_9BACE7E1C74F2195 (refresh_token), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_account (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', company_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_253B48AE979B1AD6 (company_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)');
        $this->addSql('ALTER TABLE user_account ADD CONSTRAINT FK_253B48AE979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD979B1AD6');
        $this->addSql('ALTER TABLE user_account DROP FOREIGN KEY FK_253B48AE979B1AD6');
        $this->addSql('DROP TABLE company');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE refresh_tokens');
        $this->addSql('DROP TABLE user_account');
    }
}
