<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version0011AddStorageLocation extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE storage (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, capacity INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB ROW_FORMAT = DYNAMIC');
        $this->addSql('ALTER TABLE bottle ADD storage_location_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', DROP storage_location');
        $this->addSql('ALTER TABLE bottle ADD CONSTRAINT FK_ACA9A955CDDD8AF FOREIGN KEY (storage_location_id) REFERENCES storage (id)');
        $this->addSql('CREATE INDEX IDX_ACA9A955CDDD8AF ON bottle (storage_location_id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE bottle DROP FOREIGN KEY FK_ACA9A955CDDD8AF');
        $this->addSql('DROP TABLE storage');
        $this->addSql('DROP INDEX IDX_ACA9A955CDDD8AF ON bottle');
        $this->addSql('ALTER TABLE bottle ADD storage_location VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, DROP storage_location_id');
    }
}
