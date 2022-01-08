<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220107200514 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE link ADD shortenedUri_id VARCHAR(255) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_969E36CF949DCE37 ON link (shortenedUri_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP INDEX UNIQ_969E36CF949DCE37');
        $this->addSql('ALTER TABLE Link DROP shortenedUri_id');
        $this->addSql('CREATE UNIQUE INDEX link_shortened_uri_id_key ON Link (shortened_uri_id)');
    }
}
