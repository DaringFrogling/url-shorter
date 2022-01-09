<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220108185607 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE link ALTER id TYPE INT USING (id::integer)');
        $this->addSql('ALTER TABLE link ALTER id DROP DEFAULT');
        $this->addSql('CREATE TABLE IF NOT EXISTS link_stats(
                id         INTEGER                        NOT NULL,
                link_id    INTEGER                        NOT NULL,
                ip_address VARCHAR(46)                    NOT NULL,
                user_agent JSONB DEFAULT \'{}\',
                visited_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
                PRIMARY KEY (id)
        )');
        $this->addSql('CREATE INDEX IDX_F8FC3B74ADA40271 ON link_stats (link_id)');
        $this->addSql('ALTER TABLE link_stats ADD CONSTRAINT FK_F8FC3B74ADA40271 FOREIGN KEY (link_id) REFERENCES Link (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE link ALTER tags TYPE TEXT');
        $this->addSql('ALTER TABLE link ALTER tags DROP DEFAULT');
        $this->addSql('COMMENT ON COLUMN link.tags IS \'(DC2Type:simple_array)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE Link ALTER id TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE Link ALTER id DROP DEFAULT');
        $this->addSql('DROP TABLE IF EXISTS link_stats');
        $this->addSql('ALTER TABLE Link ALTER tags TYPE TEXT');
        $this->addSql('ALTER TABLE Link ALTER tags DROP DEFAULT');
        $this->addSql('COMMENT ON COLUMN Link.tags IS NULL');
    }
}
