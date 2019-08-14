<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190813212027 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE operation ADD expediteur_id INT NOT NULL, ADD beneficiaire_id INT NOT NULL');
        $this->addSql('ALTER TABLE operation ADD CONSTRAINT FK_1981A66D10335F61 FOREIGN KEY (expediteur_id) REFERENCES expediteur (id)');
        $this->addSql('ALTER TABLE operation ADD CONSTRAINT FK_1981A66D5AF81F68 FOREIGN KEY (beneficiaire_id) REFERENCES beneficiaire (id)');
        $this->addSql('CREATE INDEX IDX_1981A66D10335F61 ON operation (expediteur_id)');
        $this->addSql('CREATE INDEX IDX_1981A66D5AF81F68 ON operation (beneficiaire_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE operation DROP FOREIGN KEY FK_1981A66D10335F61');
        $this->addSql('ALTER TABLE operation DROP FOREIGN KEY FK_1981A66D5AF81F68');
        $this->addSql('DROP INDEX IDX_1981A66D10335F61 ON operation');
        $this->addSql('DROP INDEX IDX_1981A66D5AF81F68 ON operation');
        $this->addSql('ALTER TABLE operation DROP expediteur_id, DROP beneficiaire_id');
    }
}
