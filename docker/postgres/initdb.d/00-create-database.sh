#!/bin/bash
set -e

if [ -n "$POSTGRES_DB" ]; then
	echo "Creating test database: $POSTGRES_DB"

	psql=( psql -v ON_ERROR_STOP=1 )

    "${psql[@]}" --username $POSTGRES_USER <<-EOSQL
        CREATE DATABASE "$POSTGRES_DB" TEMPLATE template1;
EOSQL
else
  	echo "Not creating test database: $POSTGRES_DB !!!!!!!!!!!"
fi
