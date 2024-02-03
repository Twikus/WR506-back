<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240203165933 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, firstname VARCHAR(180) NOT NULL, lastname VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE actor CHANGE reward reward VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE movie CHANGE duration duration INT DEFAULT NULL, CHANGE release_date release_date DATETIME DEFAULT NULL, CHANGE entries entries INT DEFAULT NULL, CHANGE budget budget INT DEFAULT NULL, CHANGE note note INT DEFAULT NULL, CHANGE director director VARCHAR(255) NOT NULL, CHANGE website website VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE user');
        $this->addSql('ALTER TABLE actor CHANGE reward reward VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE movie CHANGE duration duration INT NOT NULL, CHANGE entries entries INT NOT NULL, CHANGE budget budget INT NOT NULL, CHANGE note note INT NOT NULL, CHANGE director director INT NOT NULL, CHANGE website website INT NOT NULL, CHANGE release_date release_date DATE NOT NULL');
    }
}
