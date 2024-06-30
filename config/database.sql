CREATE TABLE "accounts" (
	"id" INTEGER NOT NULL,
	"name" TEXT NOT NULL,
	"iban" TEXT NULL,
	"type" TEXT NULL,
	"fintsUrl" TEXT NULL,
	"bankCode" TEXT NULL,
	"user" TEXT NULL,
	"pass" TEXT NULL,
	"tanMode" INTEGER NULL,
	"tanMedium" TEXT NULL,
	PRIMARY KEY ("id")
)
;


INSERT INTO "accounts" ("id", "name", "iban", "type", "fintsUrl", "bankCode", "user", "pass", "tanMode", "tanMedium") VALUES (1, 'ING DIBA', 'DE22231311213121313133', NULL, NULL, NULL, NULL, NULL, NULL, NULL);


CREATE TABLE "categories" (
	"id" INTEGER NOT NULL,
	"name" TEXT NOT NULL,
	"description" TEXT NULL,
	"icon" BLOB NULL,
	PRIMARY KEY ("id")
)
;


INSERT INTO "categories" ("id", "name", "description", "icon") VALUES (1, 'Lebensmittel', NULL, NULL);
INSERT INTO "categories" ("id", "name", "description", "icon") VALUES (2, 'Transport', NULL, NULL);
INSERT INTO "categories" ("id", "name", "description", "icon") VALUES (3, 'Freizeit', NULL, NULL);
INSERT INTO "categories" ("id", "name", "description", "icon") VALUES (4, 'Shopping', NULL, NULL);
INSERT INTO "categories" ("id", "name", "description", "icon") VALUES (5, 'Gesundheit', NULL, NULL);
INSERT INTO "categories" ("id", "name", "description", "icon") VALUES (6, 'Nebenkosten', NULL, NULL);
INSERT INTO "categories" ("id", "name", "description", "icon") VALUES (7, 'Bildung', NULL, NULL);
INSERT INTO "categories" ("id", "name", "description", "icon") VALUES (8, 'Reisen', NULL, NULL);
INSERT INTO "categories" ("id", "name", "description", "icon") VALUES (9, 'Pflege', NULL, NULL);
INSERT INTO "categories" ("id", "name", "description", "icon") VALUES (10, 'Kommunikation', NULL, NULL);
INSERT INTO "categories" ("id", "name", "description", "icon") VALUES (11, 'Haus & Garten', NULL, NULL);


CREATE TABLE "costcenters" (
	"id" INTEGER NOT NULL,
	"name" TEXT NOT NULL,
	"description" TEXT NULL,
	"icon" BLOB NULL,
	PRIMARY KEY ("id")
)
;


CREATE TABLE "transactions" (
	"id" INTEGER NOT NULL,
	"date" DATE NOT NULL,
	"description" TEXT NOT NULL,
	"amount" REAL NOT NULL,
	"client" TEXT NULL,
	"iban" TEXT NULL,
	"bic" TEXT NULL,
	"reference" TEXT NULL,
	"category" INTEGER NULL,
	"costcenter" INTEGER NULL,
	"account" INTEGER NULL,
	PRIMARY KEY ("id"),
	CONSTRAINT "0" FOREIGN KEY ("account") REFERENCES "accounts" ("id") ON UPDATE CASCADE ON DELETE SET NULL,
	CONSTRAINT "1" FOREIGN KEY ("costcenter") REFERENCES "costcenters" ("id") ON UPDATE CASCADE ON DELETE SET NULL,
	CONSTRAINT "2" FOREIGN KEY ("category") REFERENCES "categories" ("id") ON UPDATE CASCADE ON DELETE SET NULL
)
;
