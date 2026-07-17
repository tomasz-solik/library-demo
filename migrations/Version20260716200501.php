<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260716200501 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Migation BookBorrowing';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE book_borrowing (
                id SERIAL NOT NULL,
                book_id INT NOT NULL,
                borrower_card_number VARCHAR(6) NOT NULL,
                borrowed_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
                returned_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL,
                PRIMARY KEY(id)
            )
        ');

        $this->addSql('
            CREATE INDEX IDX_BOOK_BORROWING_BOOK
            ON book_borrowing (book_id)
        ');

        $this->addSql('
            ALTER TABLE book_borrowing
            ADD CONSTRAINT FK_BOOK_BORROWING_BOOK
            FOREIGN KEY (book_id)
            REFERENCES book (id)
            NOT DEFERRABLE INITIALLY IMMEDIATE
        ');

        $this->addSql('
            CREATE UNIQUE INDEX UNIQUE_ACTIVE_BOOK_BORROWING
            ON book_borrowing (book_id)
            WHERE returned_at IS NULL
        ');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('
            DROP INDEX UNIQUE_ACTIVE_BOOK_BORROWING
        ');

        $this->addSql('
            ALTER TABLE book_borrowing
            DROP CONSTRAINT FK_BOOK_BORROWING_BOOK
        ');

        $this->addSql('
            DROP INDEX IDX_BOOK_BORROWING_BOOK
        ');

        $this->addSql('
            DROP TABLE book_borrowing
        ');
    }
}
