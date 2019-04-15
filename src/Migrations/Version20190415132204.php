<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190415132204 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_1F1B251EA76ED395 ON item (user_id)');
        $this->addSql('ALTER TABLE picture DROP FOREIGN KEY FK_16DB4F8955E38587');
        $this->addSql('DROP INDEX IDX_16DB4F8955E38587 ON picture');
        $this->addSql('ALTER TABLE picture CHANGE item_id_id item_id INT NOT NULL');
        $this->addSql('ALTER TABLE picture ADD CONSTRAINT FK_16DB4F89126F525E FOREIGN KEY (item_id) REFERENCES item (id)');
        $this->addSql('CREATE INDEX IDX_16DB4F89126F525E ON picture (item_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE item DROP FOREIGN KEY FK_1F1B251EA76ED395');
        $this->addSql('DROP INDEX IDX_1F1B251EA76ED395 ON item');
        $this->addSql('ALTER TABLE picture DROP FOREIGN KEY FK_16DB4F89126F525E');
        $this->addSql('DROP INDEX IDX_16DB4F89126F525E ON picture');
        $this->addSql('ALTER TABLE picture CHANGE item_id item_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE picture ADD CONSTRAINT FK_16DB4F8955E38587 FOREIGN KEY (item_id_id) REFERENCES item (id)');
        $this->addSql('CREATE INDEX IDX_16DB4F8955E38587 ON picture (item_id_id)');
    }
}
