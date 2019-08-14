<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190813234737 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE transaction (id INT AUTO_INCREMENT NOT NULL, guichetier_id INT NOT NULL, expediteur_id INT NOT NULL, beneficiaire_id INT NOT NULL, code INT NOT NULL, montant BIGINT NOT NULL, frais BIGINT NOT NULL, total BIGINT NOT NULL, commission_wari TINYINT(1) NOT NULL, commission_partenaire BIGINT DEFAULT NULL, commission_etat BIGINT DEFAULT NULL, INDEX IDX_723705D190DCD06F (guichetier_id), INDEX IDX_723705D110335F61 (expediteur_id), INDEX IDX_723705D15AF81F68 (beneficiaire_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D190DCD06F FOREIGN KEY (guichetier_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D110335F61 FOREIGN KEY (expediteur_id) REFERENCES expediteur (id)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D15AF81F68 FOREIGN KEY (beneficiaire_id) REFERENCES beneficiaire (id)');
        $this->addSql('DROP TABLE operation');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE operation (id INT AUTO_INCREMENT NOT NULL, expediteur_id INT NOT NULL, beneficiaire_id INT NOT NULL, guichetier_id INT NOT NULL, code INT NOT NULL, montant BIGINT NOT NULL, frais BIGINT NOT NULL, date_envoi DATETIME DEFAULT NULL, date_retrait DATETIME DEFAULT NULL, commission_wari BIGINT NOT NULL, commission_partenaire BIGINT DEFAULT NULL, commission_etat BIGINT DEFAULT NULL, type_op VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, INDEX IDX_1981A66D5AF81F68 (beneficiaire_id), INDEX IDX_1981A66D10335F61 (expediteur_id), INDEX IDX_1981A66D90DCD06F (guichetier_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE operation ADD CONSTRAINT FK_1981A66D10335F61 FOREIGN KEY (expediteur_id) REFERENCES expediteur (id)');
        $this->addSql('ALTER TABLE operation ADD CONSTRAINT FK_1981A66D5AF81F68 FOREIGN KEY (beneficiaire_id) REFERENCES beneficiaire (id)');
        $this->addSql('ALTER TABLE operation ADD CONSTRAINT FK_1981A66D90DCD06F FOREIGN KEY (guichetier_id) REFERENCES utilisateur (id)');
        $this->addSql('DROP TABLE transaction');
    }
}
