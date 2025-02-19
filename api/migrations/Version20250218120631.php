<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250218120631 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE currency (
                        id UUID NOT NULL,
                        code VARCHAR(3) NOT NULL,
                        PRIMARY KEY(id))'
        );
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6956883F77153098 ON currency (code)');
        $this->addSql('COMMENT ON COLUMN currency.id IS \'(DC2Type:currency_id)\'');
        $this->addSql('COMMENT ON COLUMN currency.code IS \'(DC2Type:currency_code)\'');

        $this->addSql('CREATE TABLE exchange_rate (
                        id UUID NOT NULL,
                        pair_id UUID NOT NULL,
                        created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
                        value DOUBLE PRECISION NOT NULL,
                        PRIMARY KEY(id))'
        );
        $this->addSql('CREATE INDEX IDX_E9521FAB7EB8B2A3 ON exchange_rate (pair_id)');
        $this->addSql('COMMENT ON COLUMN exchange_rate.id IS \'(DC2Type:exchange_rate_id)\'');
        $this->addSql('COMMENT ON COLUMN exchange_rate.pair_id IS \'(DC2Type:pair_id)\'');
        $this->addSql('COMMENT ON COLUMN exchange_rate.created_at IS \'(DC2Type:datetime_immutable)\'');

        $this->addSql('CREATE TABLE pair (
                        id UUID NOT NULL,
                        base_id UUID NOT NULL,
                        quote_id UUID NOT NULL,
                        watch BOOLEAN NOT NULL,
                        PRIMARY KEY(id))'
        );
        $this->addSql('CREATE INDEX IDX_95A1E696967DF41 ON pair (base_id)');
        $this->addSql('CREATE INDEX IDX_95A1E69DB805178 ON pair (quote_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_95A1E696967DF41DB805178 ON pair (base_id, quote_id)');
        $this->addSql('COMMENT ON COLUMN pair.id IS \'(DC2Type:pair_id)\'');
        $this->addSql('COMMENT ON COLUMN pair.base_id IS \'(DC2Type:currency_id)\'');
        $this->addSql('COMMENT ON COLUMN pair.quote_id IS \'(DC2Type:currency_id)\'');

        $this->addSql('ALTER TABLE exchange_rate ADD CONSTRAINT FK_E9521FAB7EB8B2A3
                        FOREIGN KEY (pair_id) REFERENCES pair (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE pair ADD CONSTRAINT FK_95A1E696967DF41
                        FOREIGN KEY (base_id) REFERENCES currency (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE pair ADD CONSTRAINT FK_95A1E69DB805178
                        FOREIGN KEY (quote_id) REFERENCES currency (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE exchange_rate DROP CONSTRAINT FK_E9521FAB7EB8B2A3');
        $this->addSql('ALTER TABLE pair DROP CONSTRAINT FK_95A1E696967DF41');
        $this->addSql('ALTER TABLE pair DROP CONSTRAINT FK_95A1E69DB805178');
        $this->addSql('DROP TABLE currency');
        $this->addSql('DROP TABLE exchange_rate');
        $this->addSql('DROP TABLE pair');
    }
}
