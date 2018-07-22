<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version0005PricesToString extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE bottle CHANGE acquisition_price acquisition_price VARCHAR(255) DEFAULT NULL, CHANGE estimation_price estimation_price VARCHAR(255) DEFAULT NULL, CHANGE volume volume VARCHAR(255) DEFAULT NULL, CHANGE storage_location storage_location VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE wine CHANGE designation designation VARCHAR(255) DEFAULT NULL, CHANGE varietal varietal VARCHAR(255) DEFAULT NULL, CHANGE vintage vintage INT DEFAULT NULL, CHANGE region region VARCHAR(255) DEFAULT NULL, CHANGE winemaker winemaker VARCHAR(255) DEFAULT NULL, CHANGE rating rating INT DEFAULT NULL, CHANGE reference reference VARCHAR(255) DEFAULT NULL, CHANGE classification_level classification_level VARCHAR(255) DEFAULT NULL, CHANGE alcohol_degree alcohol_degree DOUBLE PRECISION DEFAULT NULL, CHANGE temperature temperature INT DEFAULT NULL, CHANGE batch batch VARCHAR(255) DEFAULT NULL, CHANGE drink_from drink_from VARCHAR(255) DEFAULT NULL, CHANGE drink_to drink_to VARCHAR(255) DEFAULT NULL, CHANGE climax_from climax_from VARCHAR(255) DEFAULT NULL, CHANGE climax_to climax_to VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE bottle CHANGE acquisition_price acquisition_price INT DEFAULT NULL, CHANGE estimation_price estimation_price INT DEFAULT NULL, CHANGE volume volume VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE storage_location storage_location VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE wine CHANGE designation designation VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE varietal varietal VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE vintage vintage INT DEFAULT NULL, CHANGE region region VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE winemaker winemaker VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE rating rating INT DEFAULT NULL, CHANGE reference reference VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE classification_level classification_level VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE drink_from drink_from VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE drink_to drink_to VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE climax_from climax_from VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE climax_to climax_to VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE alcohol_degree alcohol_degree DOUBLE PRECISION DEFAULT \'NULL\', CHANGE temperature temperature INT DEFAULT NULL, CHANGE batch batch VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
    }
}
