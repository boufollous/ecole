<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210910221548 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'add confirmed at and is confirmed fields';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE user ADD is_confirmed TINYINT(1) NOT NULL, ADD confirmed_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE `user` DROP is_confirmed, DROP confirmed_at');
    }
}
