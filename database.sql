-- sudo apt-get install postgresql-contrib
-- sudo /etc/init.d/postgresql restart
-- Se connecter en psql:
-- #sudo -i -u postgres;
-- #psql
-- #\connect bolbo-blog;
--  #CREATE EXTENSION ltree;



CREATE TABLE category (
  id    SERIAL PRIMARY KEY,
  title CHARACTER VARYING NOT NULL,
  slug  CHARACTER VARYING NOT NULL,
  UNIQUE (slug)
);

CREATE TABLE post (
  id                serial primary key,
  author_id        INTEGER                                                     NOT NULL,
  category_id      INTEGER REFERENCES category                                 NOT NULL,
  title            CHARACTER VARYING                                           NOT NULL,
  content          TEXT                                                        NOT NULL,
  created_at       TIMESTAMP WITHOUT TIME ZONE DEFAULT now()                   NOT NULL,
  updated_at       TIMESTAMP WITHOUT TIME ZONE DEFAULT now()                   NOT NULL,
  deleted_at       TIMESTAMP WITHOUT TIME ZONE                                 NULL,
  meta_title       CHARACTER VARYING                                           NULL,
  meta_description CHARACTER VARYING                                           NULL,
  meta_keyword     CHARACTER VARYING                                           NULL,
  tag              ltree                                                        NULL,
  published        BOOLEAN                                                     NULL,
  slug             CHARACTER VARYING                                           NOT NULL,
  UNIQUE (slug)
);

CREATE TABLE comment (
  id         SERIAL PRIMARY KEY,
  post_id    INTEGER REFERENCES post                                                   NOT NULL,
  author_id  INTEGER                                                                   NOT NULL,
  created_at TIMESTAMP WITHOUT TIME ZONE DEFAULT now()                                 NOT NULL,
  updated_at TIMESTAMP WITHOUT TIME ZONE DEFAULT now()                                 NOT NULL,
  deleted_at TIMESTAMP WITHOUT TIME ZONE                                               NULL,
  title      CHARACTER VARYING                                                         NOT NULL,
  content    TEXT                                                                      NOT NULL
);

CREATE TABLE favorite (
  id         SERIAL PRIMARY KEY,
  user_id    INT                                       NOT NULL,
  created_at TIMESTAMP WITHOUT TIME ZONE DEFAULT now() NOT NULL
);


INSERT INTO category (title, slug) VALUES ('Categorie 1', 'category-1');
INSERT INTO category (title, slug) VALUES ('Categorie 2', 'category-2');
INSERT INTO category (title, slug) VALUES ('Categorie 3', 'category-3');