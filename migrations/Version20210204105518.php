<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210204105518 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE admin ADD prenom VARCHAR(255) NOT NULL, ADD nom VARCHAR(255) NOT NULL, ADD adresse VARCHAR(255) NOT NULL');
        $this->addSql("INSERT INTO admin (username, roles, password , nom , prenom, adresse) VALUES ('admin', '[\"ROLE_ADMIN\"]','\$argon2id\$v=19\$m=65536,t=4,p=1$/v1A3fs04RMzAWQrbM9jHg\$Lgk+Hya3hHWbljLY+dep+/e2YJGpdDR9vLgH0gHmoS4', 'AMHAOUCH', 'Roger', '22 Rue Louis Lépine')");
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE admin DROP prenom, DROP nom, DROP adresse');
    }
}
