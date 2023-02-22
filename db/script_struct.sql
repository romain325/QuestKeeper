CREATE EXTENSION IF NOT EXISTS "uuid-ossp";

CREATE TABLE "QuestKeeper".player (
                                      id text NOT NULL DEFAULT uuid_generate_v4(),
                                      "name" text NOT NULL,
                                      "desc" text NULL,
                                      CONSTRAINT player_pk PRIMARY KEY (id)
);

CREATE TABLE "QuestKeeper"."user" (
                                      id text NOT NULL DEFAULT uuid_generate_v4(),
                                      "name" text NOT NULL,
                                      password_hash text NOT NULL,
                                      current_avatar text NULL,
                                      CONSTRAINT user_pk PRIMARY KEY (id),
                                      CONSTRAINT current_avatar FOREIGN KEY (current_avatar) REFERENCES "QuestKeeper".player(id)
);

CREATE TABLE "QuestKeeper".userplayer (
                                          id text NOT NULL DEFAULT uuid_generate_v4(),
                                          user_id text NOT NULL,
                                          player_id text NOT NULL,
                                          CONSTRAINT userplayer_pk PRIMARY KEY (id),
                                          CONSTRAINT player FOREIGN KEY (player_id) REFERENCES "QuestKeeper".player(id),
                                          CONSTRAINT "user" FOREIGN KEY (user_id) REFERENCES "QuestKeeper"."user"(id)
);

CREATE TABLE "QuestKeeper".stat (
                                    id text NOT NULL DEFAULT uuid_generate_v4(),
                                    "name" text NOT NULL,
                                    value text NULL,
                                    CONSTRAINT stat_pk PRIMARY KEY (id)
);

CREATE TABLE "QuestKeeper".playerstat (
                                          id text NOT NULL DEFAULT uuid_generate_v4(),
                                          id_player text NOT NULL,
                                          id_stat text NOT NULL,
                                          CONSTRAINT playerstat_pk PRIMARY KEY (id),
                                          CONSTRAINT playerstat_fk FOREIGN KEY (id_stat) REFERENCES "QuestKeeper".stat(id),
                                          CONSTRAINT playerstat_fk_1 FOREIGN KEY (id_player) REFERENCES "QuestKeeper".player(id)
);

CREATE TABLE "QuestKeeper".item (
                                    id text NOT NULL DEFAULT uuid_generate_v4(),
                                    "name" text NOT NULL,
                                    description text NULL,
                                    CONSTRAINT item_pk PRIMARY KEY (id)
);

CREATE TABLE "QuestKeeper".inventory (
                                         id text NOT NULL DEFAULT uuid_generate_v4(),
                                         id_player text NOT NULL,
                                         id_item text NOT NULL,
                                         CONSTRAINT inventory_pk PRIMARY KEY (id),
                                         CONSTRAINT item FOREIGN KEY (id_item) REFERENCES "QuestKeeper".item(id),
                                         CONSTRAINT player FOREIGN KEY (id_player) REFERENCES "QuestKeeper".player(id)
);

CREATE TABLE "QuestKeeper".party (
                                     id text NOT NULL DEFAULT uuid_generate_v4(),
                                     join_code text NOT NULL,
                                     "name" text NULL,
                                     master text NOT NULL,
                                     CONSTRAINT party_pk PRIMARY KEY (id),
                                     CONSTRAINT party_fk FOREIGN KEY (master) REFERENCES "QuestKeeper"."user"(id)
);

CREATE TABLE "QuestKeeper".partyplayer (
                                           id_party text NOT NULL,
                                           id_player text NOT NULL,
                                           id text NOT NULL DEFAULT uuid_generate_v4(),
                                           CONSTRAINT pk PRIMARY KEY (id),
                                           CONSTRAINT uniq_party UNIQUE (id_player),
                                           CONSTRAINT partyplayer_fk FOREIGN KEY (id_player) REFERENCES "QuestKeeper".player(id),
                                           CONSTRAINT partyplayer_fk_1 FOREIGN KEY (id_party) REFERENCES "QuestKeeper".party(id)
);

CREATE TABLE "QuestKeeper".partyitems (
                                          id text NOT NULL DEFAULT uuid_generate_v4(),
                                          id_item text NOT NULL,
                                          id_party text NOT NULL,
                                          CONSTRAINT partyitems_pk PRIMARY KEY (id),
                                          CONSTRAINT partyitems_fk FOREIGN KEY (id_item) REFERENCES "QuestKeeper".item(id),
                                          CONSTRAINT partyitems_fk_1 FOREIGN KEY (id_party) REFERENCES "QuestKeeper".party(id)
);
ALTER TABLE "QuestKeeper".partyitems ADD CONSTRAINT partyitems_un UNIQUE (id_item,id_party);

CREATE TABLE "QuestKeeper".authedclient (
                                            id text NOT NULL DEFAULT uuid_generate_v4(),
                                            user_id text NOT NULL,
                                            "token" text NOT NULL,
                                            CONSTRAINT authedclient_pk PRIMARY KEY (id),
                                            CONSTRAINT authedclient_un UNIQUE (user_id),
                                            CONSTRAINT authedclient_deux UNIQUE ("token"),
                                            CONSTRAINT authedclient_fk FOREIGN KEY (user_id) REFERENCES "QuestKeeper"."user"(id)
);
