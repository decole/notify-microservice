<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220705180353 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE notification_history (id UUID NOT NULL, message_id UUID DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, status SMALLINT NOT NULL, info TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_32A4FAFC537A1329 ON notification_history (message_id)');
        $this->addSql('CREATE UNIQUE INDEX history_message_idx ON notification_history (message_id, created_at)');
        $this->addSql('COMMENT ON COLUMN notification_history.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN notification_history.message_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN notification_history.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE notification_history ADD CONSTRAINT FK_32A4FAFC537A1329 FOREIGN KEY (message_id) REFERENCES notification_message (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE notification_history');
    }
}
