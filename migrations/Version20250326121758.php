<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250326121758 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE covoiturage_voyageurs (covoiturage_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_AE1C4B6B62671590 (covoiturage_id), INDEX IDX_AE1C4B6BA76ED395 (user_id), PRIMARY KEY(covoiturage_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE covoiturage_voyageurs ADD CONSTRAINT FK_AE1C4B6B62671590 FOREIGN KEY (covoiturage_id) REFERENCES covoiturage (id)');
        $this->addSql('ALTER TABLE covoiturage_voyageurs ADD CONSTRAINT FK_AE1C4B6BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE covoiturage ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE covoiturage ADD CONSTRAINT FK_28C79E89A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_28C79E89A76ED395 ON covoiturage (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE covoiturage_voyageurs DROP FOREIGN KEY FK_AE1C4B6B62671590');
        $this->addSql('ALTER TABLE covoiturage_voyageurs DROP FOREIGN KEY FK_AE1C4B6BA76ED395');
        $this->addSql('DROP TABLE covoiturage_voyageurs');
        $this->addSql('ALTER TABLE covoiturage DROP FOREIGN KEY FK_28C79E89A76ED395');
        $this->addSql('DROP INDEX IDX_28C79E89A76ED395 ON covoiturage');
        $this->addSql('ALTER TABLE covoiturage DROP user_id');
    }
}
