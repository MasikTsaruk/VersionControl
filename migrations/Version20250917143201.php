<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250917143201 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE blog_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE category_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE tag_id_seq CASCADE');
        $this->addSql('CREATE TABLE app (id SERIAL NOT NULL, uuid UUID NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE env (id SERIAL NOT NULL, app_id INT NOT NULL, uuid UUID NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_F34542F97987212D ON env (app_id)');
        $this->addSql('CREATE TABLE version (id SERIAL NOT NULL, env_id INT NOT NULL, version VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_BF1CD3C318AD1504 ON version (env_id)');
        $this->addSql('COMMENT ON COLUMN version.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE env ADD CONSTRAINT FK_F34542F97987212D FOREIGN KEY (app_id) REFERENCES app (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE version ADD CONSTRAINT FK_BF1CD3C318AD1504 FOREIGN KEY (env_id) REFERENCES env (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tags_to_blog DROP CONSTRAINT fk_147ab9dbad26311');
        $this->addSql('ALTER TABLE tags_to_blog DROP CONSTRAINT fk_147ab9ddae07e97');
        $this->addSql('ALTER TABLE blog DROP CONSTRAINT fk_c015514312469de2');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE tags_to_blog');
        $this->addSql('DROP TABLE tag');
        $this->addSql('DROP TABLE blog');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE SEQUENCE blog_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE category_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE tag_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE category (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE tags_to_blog (blog_id INT NOT NULL, tag_id INT NOT NULL, PRIMARY KEY(blog_id, tag_id))');
        $this->addSql('CREATE INDEX idx_147ab9ddae07e97 ON tags_to_blog (blog_id)');
        $this->addSql('CREATE UNIQUE INDEX uniq_147ab9dbad26311 ON tags_to_blog (tag_id)');
        $this->addSql('CREATE TABLE tag (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE blog (id SERIAL NOT NULL, category_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, text TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_c015514312469de2 ON blog (category_id)');
        $this->addSql('ALTER TABLE tags_to_blog ADD CONSTRAINT fk_147ab9dbad26311 FOREIGN KEY (tag_id) REFERENCES tag (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tags_to_blog ADD CONSTRAINT fk_147ab9ddae07e97 FOREIGN KEY (blog_id) REFERENCES blog (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE blog ADD CONSTRAINT fk_c015514312469de2 FOREIGN KEY (category_id) REFERENCES category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE env DROP CONSTRAINT FK_F34542F97987212D');
        $this->addSql('ALTER TABLE version DROP CONSTRAINT FK_BF1CD3C318AD1504');
        $this->addSql('DROP TABLE app');
        $this->addSql('DROP TABLE env');
        $this->addSql('DROP TABLE version');
    }
}
