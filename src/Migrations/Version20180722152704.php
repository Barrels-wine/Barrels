<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180722152704 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE bottle CHANGE acquisition_price acquisition_price INT DEFAULT NULL, CHANGE estimation_price estimation_price INT DEFAULT NULL, CHANGE volume volume VARCHAR(255) DEFAULT NULL, CHANGE storage_location storage_location VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE wine ADD drink_from VARCHAR(255) DEFAULT NULL, ADD drink_to VARCHAR(255) DEFAULT NULL, ADD climax_from VARCHAR(255) DEFAULT NULL, ADD climax_to VARCHAR(255) DEFAULT NULL, DROP aging, DROP best_aging, DROP best_after, DROP drink_after, CHANGE designation designation VARCHAR(255) DEFAULT NULL, CHANGE varietal varietal VARCHAR(255) DEFAULT NULL, CHANGE vintage vintage INT DEFAULT NULL, CHANGE region region VARCHAR(255) DEFAULT NULL, CHANGE winemaker winemaker VARCHAR(255) DEFAULT NULL, CHANGE rating rating INT DEFAULT NULL, CHANGE reference reference VARCHAR(255) DEFAULT NULL, CHANGE classification_level classification_level VARCHAR(255) DEFAULT NULL, CHANGE alcohol_degree alcohol_degree DOUBLE PRECISION DEFAULT NULL, CHANGE temperature temperature INT DEFAULT NULL, CHANGE batch batch VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE bottle CHANGE acquisition_price acquisition_price INT DEFAULT NULL, CHANGE estimation_price estimation_price INT DEFAULT NULL, CHANGE volume volume VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE storage_location storage_location VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE wine ADD aging VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, ADD best_aging VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, ADD best_after VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, ADD drink_after VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, DROP drink_from, DROP drink_to, DROP climax_from, DROP climax_to, CHANGE designation designation VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE varietal varietal VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE vintage vintage INT DEFAULT NULL, CHANGE region region VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE winemaker winemaker VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE rating rating INT DEFAULT NULL, CHANGE reference reference VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE classification_level classification_level VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE alcohol_degree alcohol_degree DOUBLE PRECISION DEFAULT \'NULL\', CHANGE temperature temperature INT DEFAULT NULL, CHANGE batch batch VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
    }
}
