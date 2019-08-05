<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190805160600 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE UNIQUE INDEX UNIQ_E6D6B297A4D60759 ON profil (libelle)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_CFF652609731415A ON compte (numero_compte)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1D1C63B3450FF010 ON utilisateur (telephone)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_32FFA373D19FA60 ON partenaire (entreprise)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_32FFA373C678AEBE ON partenaire (ninea)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX UNIQ_CFF652609731415A ON compte');
        $this->addSql('DROP INDEX UNIQ_32FFA373D19FA60 ON partenaire');
        $this->addSql('DROP INDEX UNIQ_32FFA373C678AEBE ON partenaire');
        $this->addSql('DROP INDEX UNIQ_E6D6B297A4D60759 ON profil');
        $this->addSql('DROP INDEX UNIQ_1D1C63B3450FF010 ON utilisateur');
    }
}
