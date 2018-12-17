<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181120131437 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE task CHANGE number number DOUBLE PRECISION DEFAULT NULL, CHANGE url url VARCHAR(255) DEFAULT NULL, CHANGE search search VARCHAR(255) DEFAULT NULL, CHANGE rangeminmax rangeminmax VARCHAR(255) DEFAULT NULL, CHANGE tel tel VARCHAR(255) DEFAULT NULL, CHANGE color color VARCHAR(255) DEFAULT NULL, CHANGE choice choice VARCHAR(255) DEFAULT NULL, CHANGE country country VARCHAR(255) DEFAULT NULL, CHANGE language language VARCHAR(255) DEFAULT NULL, CHANGE locale_abc locale_abc VARCHAR(255) DEFAULT NULL, CHANGE timezone timezone VARCHAR(255) DEFAULT NULL, CHANGE currency currency VARCHAR(255) DEFAULT NULL, CHANGE date date DATE DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE task CHANGE number number DOUBLE PRECISION NOT NULL, CHANGE url url VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE search search VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE rangeminmax rangeminmax VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE tel tel VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE color color VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE choice choice VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE country country VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE language language VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE locale_abc locale_abc VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE timezone timezone VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE currency currency VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE date date DATE NOT NULL');
    }
}
