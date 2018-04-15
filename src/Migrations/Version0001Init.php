<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version0001Init extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(25) NOT NULL, password VARCHAR(64) NOT NULL, is_active TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bottle (id INT AUTO_INCREMENT NOT NULL, wine_id INT NOT NULL, acquisition_price DOUBLE PRECISION DEFAULT NULL, estimation_price DOUBLE PRECISION DEFAULT NULL, volume VARCHAR(255) NOT NULL, storage_location VARCHAR(255) DEFAULT NULL, INDEX IDX_ACA9A95528A2BD76 (wine_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE wine (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, designation VARCHAR(255) NOT NULL, varietal VARCHAR(255) NOT NULL, color VARCHAR(255) NOT NULL, vintage INT NOT NULL, country VARCHAR(255) NOT NULL, region VARCHAR(255) NOT NULL, winemaker VARCHAR(255) NOT NULL, rating INT DEFAULT NULL, comment LONGTEXT DEFAULT NULL, food_pairing LONGTEXT DEFAULT NULL, reference VARCHAR(255) DEFAULT NULL, classification_level VARCHAR(255) DEFAULT NULL, aging VARCHAR(255) DEFAULT NULL, best_aging VARCHAR(255) DEFAULT NULL, best_after VARCHAR(255) DEFAULT NULL, drink_after VARCHAR(255) DEFAULT NULL, alcohol_degree DOUBLE PRECISION DEFAULT NULL, temperature DOUBLE PRECISION DEFAULT NULL, batch VARCHAR(255) DEFAULT NULL, category VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE bottle ADD CONSTRAINT FK_ACA9A95528A2BD76 FOREIGN KEY (wine_id) REFERENCES wine (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE bottle DROP FOREIGN KEY FK_ACA9A95528A2BD76');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE bottle');
        $this->addSql('DROP TABLE wine');
    }
}
