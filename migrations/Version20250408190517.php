<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250408190517 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE litige (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, covoiturage_id INT DEFAULT NULL, INDEX IDX_EEE9D46DA76ED395 (user_id), INDEX IDX_EEE9D46D62671590 (covoiturage_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE litige ADD CONSTRAINT FK_EEE9D46DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE litige ADD CONSTRAINT FK_EEE9D46D62671590 FOREIGN KEY (covoiturage_id) REFERENCES covoiturage (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE litige DROP FOREIGN KEY FK_EEE9D46DA76ED395');
        $this->addSql('ALTER TABLE litige DROP FOREIGN KEY FK_EEE9D46D62671590');
        $this->addSql('DROP TABLE litige');
    }
}
