<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240304190145 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE client (id INT NOT NULL, society_id INT DEFAULT NULL, creator_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, num_tel VARCHAR(10) DEFAULT NULL, num_fix VARCHAR(10) DEFAULT NULL, email VARCHAR(255) NOT NULL, adresse VARCHAR(255) DEFAULT NULL, code_postal VARCHAR(5) DEFAULT NULL, ville VARCHAR(255) DEFAULT NULL, raison_sociale VARCHAR(255) DEFAULT NULL, num_siret VARCHAR(14) DEFAULT NULL, type VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_C7440455E6389D24 ON client (society_id)');
        $this->addSql('CREATE INDEX IDX_C744045561220EA6 ON client (creator_id)');
        $this->addSql('COMMENT ON COLUMN client.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN client.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE devis (id INT NOT NULL, society_id INT NOT NULL, client_id INT NOT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, ref_devis VARCHAR(255) NOT NULL, date_devis TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, date_echeance TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, titre_devis VARCHAR(255) NOT NULL, content TEXT DEFAULT NULL, total_ht DOUBLE PRECISION NOT NULL, tva DOUBLE PRECISION NOT NULL, total_ttc DOUBLE PRECISION NOT NULL, total_remise DOUBLE PRECISION NOT NULL, slug VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8B27C52B989D9B62 ON devis (slug)');
        $this->addSql('CREATE INDEX IDX_8B27C52BE6389D24 ON devis (society_id)');
        $this->addSql('CREATE INDEX IDX_8B27C52B19EB6921 ON devis (client_id)');
        $this->addSql('CREATE INDEX IDX_8B27C52BB03A8386 ON devis (created_by_id)');
        $this->addSql('CREATE INDEX IDX_8B27C52B896DBBDE ON devis (updated_by_id)');
        $this->addSql('CREATE TABLE devis_produit (id INT NOT NULL, devis_id INT NOT NULL, product_id INT NOT NULL, nb_apprenant INT NOT NULL, montant_ht DOUBLE PRECISION NOT NULL, taxe_tva DOUBLE PRECISION NOT NULL, montant_remise DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_BB4B777B41DEFADA ON devis_produit (devis_id)');
        $this->addSql('CREATE INDEX IDX_BB4B777B4584665A ON devis_produit (product_id)');
        $this->addSql('CREATE TABLE facture (id INT NOT NULL, society_id INT DEFAULT NULL, client_id INT DEFAULT NULL, creator_id INT DEFAULT NULL, ref_facture VARCHAR(255) NOT NULL, date_facture TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, titre_facture VARCHAR(255) NOT NULL, total_ht DOUBLE PRECISION NOT NULL, tva DOUBLE PRECISION NOT NULL, total_ttc DOUBLE PRECISION NOT NULL, total_remise DOUBLE PRECISION DEFAULT NULL, condition_termes TEXT DEFAULT NULL, status TEXT DEFAULT NULL, condition TEXT DEFAULT NULL, date_echeance TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_FE866410E6389D24 ON facture (society_id)');
        $this->addSql('CREATE INDEX IDX_FE86641019EB6921 ON facture (client_id)');
        $this->addSql('CREATE INDEX IDX_FE86641061220EA6 ON facture (creator_id)');
        $this->addSql('COMMENT ON COLUMN facture.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN facture.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE facture_produit (id INT NOT NULL, facture_id INT NOT NULL, product_id INT NOT NULL, nb_apprenant INT NOT NULL, montant_ht DOUBLE PRECISION NOT NULL, taxe_tva DOUBLE PRECISION NOT NULL, montant_remise DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_61424D7E7F2DEE08 ON facture_produit (facture_id)');
        $this->addSql('CREATE INDEX IDX_61424D7E4584665A ON facture_produit (product_id)');
        $this->addSql('CREATE TABLE paiement (id INT NOT NULL, facture_id INT NOT NULL, creator_id INT NOT NULL, date_paiement TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, montant DOUBLE PRECISION NOT NULL, type VARCHAR(255) NOT NULL, statut VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_B1DC7A1E7F2DEE08 ON paiement (facture_id)');
        $this->addSql('CREATE INDEX IDX_B1DC7A1E61220EA6 ON paiement (creator_id)');
        $this->addSql('COMMENT ON COLUMN paiement.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN paiement.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE produit (id INT NOT NULL, society_id INT NOT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, designation TEXT NOT NULL, nb_apprenant_min INT DEFAULT NULL, nb_apprenant_max INT DEFAULT NULL, price_unit DOUBLE PRECISION NOT NULL, categorie VARCHAR(255) NOT NULL, taux_tva DOUBLE PRECISION NOT NULL, duration INT DEFAULT NULL, exigeance TEXT DEFAULT NULL, certification VARCHAR(255) DEFAULT NULL, slug VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_29A5EC27989D9B62 ON produit (slug)');
        $this->addSql('CREATE INDEX IDX_29A5EC27E6389D24 ON produit (society_id)');
        $this->addSql('CREATE INDEX IDX_29A5EC27B03A8386 ON produit (created_by_id)');
        $this->addSql('CREATE INDEX IDX_29A5EC27896DBBDE ON produit (updated_by_id)');
        $this->addSql('CREATE TABLE "societe" (id INT NOT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, raison_sociale VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, num_siret INT NOT NULL, adresse VARCHAR(255) DEFAULT NULL, code_postal INT DEFAULT NULL, ville VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, num_tel INT DEFAULT NULL, num_fix INT DEFAULT NULL, avatar_logo VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_19653DBD989D9B62 ON "societe" (slug)');
        $this->addSql('CREATE INDEX IDX_19653DBDB03A8386 ON "societe" (created_by_id)');
        $this->addSql('CREATE INDEX IDX_19653DBD896DBBDE ON "societe" (updated_by_id)');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, surname VARCHAR(255) NOT NULL, profile_picture_name VARCHAR(255) DEFAULT NULL, is_verified BOOLEAN DEFAULT NULL, confirmation_code VARCHAR(255) DEFAULT NULL, slug VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649989D9B62 ON "user" (slug)');
        $this->addSql('CREATE INDEX IDX_8D93D649B03A8386 ON "user" (created_by_id)');
        $this->addSql('CREATE INDEX IDX_8D93D649896DBBDE ON "user" (updated_by_id)');
        $this->addSql('CREATE TABLE user_societe (user_id INT NOT NULL, societe_id INT NOT NULL, PRIMARY KEY(user_id, societe_id))');
        $this->addSql('CREATE INDEX IDX_416823B7A76ED395 ON user_societe (user_id)');
        $this->addSql('CREATE INDEX IDX_416823B7FCF77503 ON user_societe (societe_id)');
        $this->addSql('ALTER TABLE client ADD CONSTRAINT FK_C7440455E6389D24 FOREIGN KEY (society_id) REFERENCES "societe" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE client ADD CONSTRAINT FK_C744045561220EA6 FOREIGN KEY (creator_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE devis ADD CONSTRAINT FK_8B27C52BE6389D24 FOREIGN KEY (society_id) REFERENCES "societe" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE devis ADD CONSTRAINT FK_8B27C52B19EB6921 FOREIGN KEY (client_id) REFERENCES client (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE devis ADD CONSTRAINT FK_8B27C52BB03A8386 FOREIGN KEY (created_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE devis ADD CONSTRAINT FK_8B27C52B896DBBDE FOREIGN KEY (updated_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE devis_produit ADD CONSTRAINT FK_BB4B777B41DEFADA FOREIGN KEY (devis_id) REFERENCES devis (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE devis_produit ADD CONSTRAINT FK_BB4B777B4584665A FOREIGN KEY (product_id) REFERENCES produit (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE facture ADD CONSTRAINT FK_FE866410E6389D24 FOREIGN KEY (society_id) REFERENCES "societe" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE facture ADD CONSTRAINT FK_FE86641019EB6921 FOREIGN KEY (client_id) REFERENCES client (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE facture ADD CONSTRAINT FK_FE86641061220EA6 FOREIGN KEY (creator_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE facture_produit ADD CONSTRAINT FK_61424D7E7F2DEE08 FOREIGN KEY (facture_id) REFERENCES facture (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE facture_produit ADD CONSTRAINT FK_61424D7E4584665A FOREIGN KEY (product_id) REFERENCES produit (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE paiement ADD CONSTRAINT FK_B1DC7A1E7F2DEE08 FOREIGN KEY (facture_id) REFERENCES facture (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE paiement ADD CONSTRAINT FK_B1DC7A1E61220EA6 FOREIGN KEY (creator_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27E6389D24 FOREIGN KEY (society_id) REFERENCES "societe" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27B03A8386 FOREIGN KEY (created_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27896DBBDE FOREIGN KEY (updated_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "societe" ADD CONSTRAINT FK_19653DBDB03A8386 FOREIGN KEY (created_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "societe" ADD CONSTRAINT FK_19653DBD896DBBDE FOREIGN KEY (updated_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT FK_8D93D649B03A8386 FOREIGN KEY (created_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT FK_8D93D649896DBBDE FOREIGN KEY (updated_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_societe ADD CONSTRAINT FK_416823B7A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_societe ADD CONSTRAINT FK_416823B7FCF77503 FOREIGN KEY (societe_id) REFERENCES "societe" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE client DROP CONSTRAINT FK_C7440455E6389D24');
        $this->addSql('ALTER TABLE client DROP CONSTRAINT FK_C744045561220EA6');
        $this->addSql('ALTER TABLE devis DROP CONSTRAINT FK_8B27C52BE6389D24');
        $this->addSql('ALTER TABLE devis DROP CONSTRAINT FK_8B27C52B19EB6921');
        $this->addSql('ALTER TABLE devis DROP CONSTRAINT FK_8B27C52BB03A8386');
        $this->addSql('ALTER TABLE devis DROP CONSTRAINT FK_8B27C52B896DBBDE');
        $this->addSql('ALTER TABLE devis_produit DROP CONSTRAINT FK_BB4B777B41DEFADA');
        $this->addSql('ALTER TABLE devis_produit DROP CONSTRAINT FK_BB4B777B4584665A');
        $this->addSql('ALTER TABLE facture DROP CONSTRAINT FK_FE866410E6389D24');
        $this->addSql('ALTER TABLE facture DROP CONSTRAINT FK_FE86641019EB6921');
        $this->addSql('ALTER TABLE facture DROP CONSTRAINT FK_FE86641061220EA6');
        $this->addSql('ALTER TABLE facture_produit DROP CONSTRAINT FK_61424D7E7F2DEE08');
        $this->addSql('ALTER TABLE facture_produit DROP CONSTRAINT FK_61424D7E4584665A');
        $this->addSql('ALTER TABLE paiement DROP CONSTRAINT FK_B1DC7A1E7F2DEE08');
        $this->addSql('ALTER TABLE paiement DROP CONSTRAINT FK_B1DC7A1E61220EA6');
        $this->addSql('ALTER TABLE produit DROP CONSTRAINT FK_29A5EC27E6389D24');
        $this->addSql('ALTER TABLE produit DROP CONSTRAINT FK_29A5EC27B03A8386');
        $this->addSql('ALTER TABLE produit DROP CONSTRAINT FK_29A5EC27896DBBDE');
        $this->addSql('ALTER TABLE "societe" DROP CONSTRAINT FK_19653DBDB03A8386');
        $this->addSql('ALTER TABLE "societe" DROP CONSTRAINT FK_19653DBD896DBBDE');
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT FK_8D93D649B03A8386');
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT FK_8D93D649896DBBDE');
        $this->addSql('ALTER TABLE user_societe DROP CONSTRAINT FK_416823B7A76ED395');
        $this->addSql('ALTER TABLE user_societe DROP CONSTRAINT FK_416823B7FCF77503');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE devis');
        $this->addSql('DROP TABLE devis_produit');
        $this->addSql('DROP TABLE facture');
        $this->addSql('DROP TABLE facture_produit');
        $this->addSql('DROP TABLE paiement');
        $this->addSql('DROP TABLE produit');
        $this->addSql('DROP TABLE "societe"');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE user_societe');
    }
}
