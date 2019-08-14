<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190814015017 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE beneficiaire ADD prenom_b VARCHAR(255) NOT NULL, ADD nom_b VARCHAR(255) NOT NULL, ADD adresse_b VARCHAR(255) DEFAULT NULL, ADD numero_piece_b INT DEFAULT NULL, ADD type_piece_b VARCHAR(255) DEFAULT NULL, DROP prenom, DROP nom, DROP adresse, DROP numero_piece, DROP type_piece, CHANGE telephone telephone_b INT NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE beneficiaire ADD prenom VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, ADD nom VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, ADD adresse VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, ADD numero_piece BIGINT DEFAULT NULL, ADD type_piece VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, DROP prenom_b, DROP nom_b, DROP adresse_b, DROP numero_piece_b, DROP type_piece_b, CHANGE telephone_b telephone INT NOT NULL');
    }
}
