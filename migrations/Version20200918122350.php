<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20200918122350 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Adding indexes';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('CREATE INDEX preparations_name ON preparations (name)');
        $this->addSql('CREATE INDEX preparations_undefined_name ON preparations_undefined (name)');
        $this->addSql('CREATE INDEX pharmacies_address ON pharmacies (address)');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('DROP INDEX preparations_name ON preparations');
        $this->addSql('DROP INDEX preparations_undefined_name ON preparations_undefined');
        $this->addSql('DROP INDEX pharmacies_address ON pharmacies');
    }
}
