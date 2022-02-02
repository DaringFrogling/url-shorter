<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220105212101 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create "link" table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(
            'CREATE TABLE IF NOT EXISTS link
                (
                    id           varchar(255) not null primary key,
                    original_url varchar(255) not null,
                    title        varchar(255) not null,
                    tags         text         not null,
                    created_at   timestamp(0) not null,
                    updated_at   timestamp(0) default NULL::timestamp without time zone
                );'
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE IF EXISTS link');
    }
}
