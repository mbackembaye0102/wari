<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190818130329 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D15AF81F68');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D110335F61');
        $this->addSql('DROP TABLE beneficiaire');
        $this->addSql('DROP TABLE expediteur');
        $this->addSql('DROP TABLE historique');
        $this->addSql('DROP INDEX IDX_723705D110335F61 ON transaction');
        $this->addSql('DROP INDEX IDX_723705D15AF81F68 ON transaction');
        $this->addSql('ALTER TABLE transaction DROP expediteur_id, DROP beneficiaire_id, DROP type_op');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE beneficiaire (id INT AUTO_INCREMENT NOT NULL, telephone_b INT NOT NULL, prenom_b VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, nom_b VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, adresse_b VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, numero_piece_b INT DEFAULT NULL, type_piece_b VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE expediteur (id INT AUTO_INCREMENT NOT NULL, prenom VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, nom VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, telephone INT NOT NULL, adresse VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, numero_piece BIGINT DEFAULT NULL, type_piece VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE historique (id INT AUTO_INCREMENT NOT NULL, transaction_id INT NOT NULL, compte_id INT NOT NULL, prenom VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, nom VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, INDEX IDX_EDBFD5ECF2C56620 (compte_id), INDEX IDX_EDBFD5EC2FC0CB0F (transaction_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE historique ADD CONSTRAINT FK_EDBFD5EC2FC0CB0F FOREIGN KEY (transaction_id) REFERENCES transaction (id)');
        $this->addSql('ALTER TABLE historique ADD CONSTRAINT FK_EDBFD5ECF2C56620 FOREIGN KEY (compte_id) REFERENCES compte (id)');
        $this->addSql('ALTER TABLE transaction ADD expediteur_id INT NOT NULL, ADD beneficiaire_id INT NOT NULL, ADD type_op VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D110335F61 FOREIGN KEY (expediteur_id) REFERENCES expediteur (id)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D15AF81F68 FOREIGN KEY (beneficiaire_id) REFERENCES beneficiaire (id)');
        $this->addSql('CREATE INDEX IDX_723705D110335F61 ON transaction (expediteur_id)');
        $this->addSql('CREATE INDEX IDX_723705D15AF81F68 ON transaction (beneficiaire_id)');
    }
}
