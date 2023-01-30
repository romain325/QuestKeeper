CREATE EXTENSION IF NOT EXISTS "uuid-ossp";

CREATE TABLE "QuestKeeper".player (
                                      id text NOT NULL DEFAULT uuid_generate_v4(),
                                      "name" text NOT NULL,
                                      "desc" text NULL,
                                      CONSTRAINT player_pk PRIMARY KEY (id)
);