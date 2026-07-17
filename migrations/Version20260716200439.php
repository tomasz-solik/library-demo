<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260716200439 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Migration: Book';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE book (
                id SERIAL NOT NULL,
                serial_number VARCHAR(6) NOT NULL UNIQUE,
                title VARCHAR(255) NOT NULL,
                author VARCHAR(255) NOT NULL,
                is_borrowed BOOLEAN NOT NULL DEFAULT FALSE,
                deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL,
                PRIMARY KEY(id)
            )
        ');

    }

    public function down(Schema $schema): void
    {
        $this->addSql('
            DROP TABLE book
        ');
    }
}
