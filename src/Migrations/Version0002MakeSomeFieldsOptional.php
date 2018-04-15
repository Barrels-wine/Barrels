<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version0002MakeSomeFieldsOptional extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE bottle CHANGE acquisition_price acquisition_price DOUBLE PRECISION DEFAULT NULL, CHANGE estimation_price estimation_price DOUBLE PRECISION DEFAULT NULL, CHANGE volume volume VARCHAR(255) DEFAULT NULL, CHANGE storage_location storage_location VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE wine CHANGE designation designation VARCHAR(255) DEFAULT NULL, CHANGE varietal varietal VARCHAR(255) DEFAULT NULL, CHANGE vintage vintage INT DEFAULT NULL, CHANGE region region VARCHAR(255) DEFAULT NULL, CHANGE winemaker winemaker VARCHAR(255) DEFAULT NULL, CHANGE rating rating INT DEFAULT NULL, CHANGE reference reference VARCHAR(255) DEFAULT NULL, CHANGE classification_level classification_level VARCHAR(255) DEFAULT NULL, CHANGE aging aging VARCHAR(255) DEFAULT NULL, CHANGE best_aging best_aging VARCHAR(255) DEFAULT NULL, CHANGE best_after best_after VARCHAR(255) DEFAULT NULL, CHANGE drink_after drink_after VARCHAR(255) DEFAULT NULL, CHANGE alcohol_degree alcohol_degree DOUBLE PRECISION DEFAULT NULL, CHANGE temperature temperature DOUBLE PRECISION DEFAULT NULL, CHANGE batch batch VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE bottle CHANGE acquisition_price acquisition_price DOUBLE PRECISION DEFAULT \'NULL\', CHANGE estimation_price estimation_price DOUBLE PRECISION DEFAULT \'NULL\', CHANGE volume volume VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE storage_location storage_location VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE wine CHANGE designation designation VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE varietal varietal VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE vintage vintage INT NOT NULL, CHANGE region region VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE winemaker winemaker VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE rating rating INT DEFAULT NULL, CHANGE reference reference VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE classification_level classification_level VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE aging aging VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE best_aging best_aging VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE best_after best_after VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE drink_after drink_after VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE alcohol_degree alcohol_degree DOUBLE PRECISION DEFAULT \'NULL\', CHANGE temperature temperature DOUBLE PRECISION DEFAULT \'NULL\', CHANGE batch batch VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
    }
}
