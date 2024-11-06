<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20241104112625 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Creates "clients" table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE clients (
                id UUID PRIMARY KEY,
                last_name VARCHAR(100) NOT NULL,
                first_name VARCHAR(100) NOT NULL,
                age INT NOT NULL,
                address VARCHAR(255) NOT NULL,
                ssn VARCHAR(11) NOT NULL,
                fico INT NOT NULL,
                email VARCHAR(255) NOT NULL,
                phone VARCHAR(15) NOT NULL
            )
        ');

        $this->addSql('CREATE UNIQUE INDEX idx_client_ssn ON clients (ssn)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE clients');
    }
}
