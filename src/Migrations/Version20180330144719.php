<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180330144719 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE managers (id INT AUTO_INCREMENT NOT NULL, permission_id INT NOT NULL, first_name VARCHAR(250) NOT NULL, last_name VARCHAR(250) NOT NULL, login VARCHAR(255) NOT NULL, Password VARCHAR(455) NOT NULL, status SMALLINT NOT NULL, role VARCHAR(50) NOT NULL, UNIQUE INDEX UNIQ_A949E006FED90CCA (permission_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE permissions (id INT AUTO_INCREMENT NOT NULL, read_order TINYINT(1) NOT NULL, edit_order TINYINT(1) NOT NULL, read_manager TINYINT(1) NOT NULL, edit_manager TINYINT(1) NOT NULL, read_configuration TINYINT(1) NOT NULL, edit_configuration TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE managers ADD CONSTRAINT FK_A949E006FED90CCA FOREIGN KEY (permission_id) REFERENCES permissions (id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE managers DROP FOREIGN KEY FK_A949E006FED90CCA');
        $this->addSql('DROP TABLE managers');
        $this->addSql('DROP TABLE permissions');
    }
}
