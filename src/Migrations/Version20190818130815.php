<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190818130815 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE transaction ADD prenom VARCHAR(255) NOT NULL, ADD nom VARCHAR(255) NOT NULL, ADD telephone INT NOT NULL, ADD numero_piece INT DEFAULT NULL, ADD type_piece VARCHAR(255) DEFAULT NULL, ADD etat VARCHAR(255) NOT NULL, ADD prenomb VARCHAR(255) NOT NULL, ADD nomb VARCHAR(255) NOT NULL, ADD telephoneb INT NOT NULL, ADD numero_pieceb INT DEFAULT NULL, ADD type_pieceb VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE transaction DROP prenom, DROP nom, DROP telephone, DROP numero_piece, DROP type_piece, DROP etat, DROP prenomb, DROP nomb, DROP telephoneb, DROP numero_pieceb, DROP type_pieceb');
    }
}
