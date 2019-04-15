<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190415122555 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE item_status ADD item_id INT NOT NULL, ADD status_id INT NOT NULL');
        $this->addSql('ALTER TABLE item_status ADD CONSTRAINT FK_FDF910D3126F525E FOREIGN KEY (item_id) REFERENCES item (id)');
        $this->addSql('ALTER TABLE item_status ADD CONSTRAINT FK_FDF910D36BF700BD FOREIGN KEY (status_id) REFERENCES status (id)');
        $this->addSql('CREATE INDEX IDX_FDF910D3126F525E ON item_status (item_id)');
        $this->addSql('CREATE INDEX IDX_FDF910D36BF700BD ON item_status (status_id)');
        $this->addSql('ALTER TABLE user_status ADD users_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', ADD status_id INT NOT NULL');
        $this->addSql('ALTER TABLE user_status ADD CONSTRAINT FK_1E527E2167B3B43D FOREIGN KEY (users_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_status ADD CONSTRAINT FK_1E527E216BF700BD FOREIGN KEY (status_id) REFERENCES status (id)');
        $this->addSql('CREATE INDEX IDX_1E527E2167B3B43D ON user_status (users_id)');
        $this->addSql('CREATE INDEX IDX_1E527E216BF700BD ON user_status (status_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE item_status DROP FOREIGN KEY FK_FDF910D3126F525E');
        $this->addSql('ALTER TABLE item_status DROP FOREIGN KEY FK_FDF910D36BF700BD');
        $this->addSql('DROP INDEX IDX_FDF910D3126F525E ON item_status');
        $this->addSql('DROP INDEX IDX_FDF910D36BF700BD ON item_status');
        $this->addSql('ALTER TABLE item_status DROP item_id, DROP status_id');
        $this->addSql('ALTER TABLE user_status DROP FOREIGN KEY FK_1E527E2167B3B43D');
        $this->addSql('ALTER TABLE user_status DROP FOREIGN KEY FK_1E527E216BF700BD');
        $this->addSql('DROP INDEX IDX_1E527E2167B3B43D ON user_status');
        $this->addSql('DROP INDEX IDX_1E527E216BF700BD ON user_status');
        $this->addSql('ALTER TABLE user_status DROP users_id, DROP status_id');
    }
}
