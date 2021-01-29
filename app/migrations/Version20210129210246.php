<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210129210246 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        $this->addSql('CREATE TABLE "user" (id VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, password VARCHAR(255) DEFAULT NULL, roles JSON DEFAULT NULL, auth_token VARCHAR(255) DEFAULT NULL, access_token VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE "user"');
    }
}
