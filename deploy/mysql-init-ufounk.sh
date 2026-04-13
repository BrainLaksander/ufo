#!/usr/bin/env bash
set -euo pipefail

# Bootstrap MySQL database/user using runtime secrets from environment variables.
# Required:
#   UFO_DB_NAME
#   UFO_DB_USER
#   UFO_DB_PASSWORD
# Optional:
#   MYSQL_ROOT_HOST (default: 127.0.0.1)
#   MYSQL_ROOT_PORT (default: 3306)
#   MYSQL_ROOT_USER (default: root)
#   MYSQL_ROOT_PASSWORD

require_var() {
  local name="$1"
  if [[ -z "${!name:-}" ]]; then
    echo "Missing required environment variable: ${name}" >&2
    exit 1
  fi
}

validate_identifier() {
  local value="$1"
  local name="$2"

  if [[ ! "${value}" =~ ^[A-Za-z0-9_]+$ ]]; then
    echo "Invalid ${name}: only letters, numbers, and underscore are allowed." >&2
    exit 1
  fi
}

sql_escape() {
  printf "%s" "$1" | sed "s/'/''/g"
}

sed_escape() {
  printf "%s" "$1" | sed -e 's/[\/&]/\\&/g'
}

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
TEMPLATE_FILE="${SCRIPT_DIR}/mysql-init-ufounk.sql"

require_var "UFO_DB_NAME"
require_var "UFO_DB_USER"
require_var "UFO_DB_PASSWORD"

validate_identifier "${UFO_DB_NAME}" "UFO_DB_NAME"
validate_identifier "${UFO_DB_USER}" "UFO_DB_USER"

if [[ ! -f "${TEMPLATE_FILE}" ]]; then
  echo "Template not found: ${TEMPLATE_FILE}" >&2
  exit 1
fi

mysql_host="${MYSQL_ROOT_HOST:-127.0.0.1}"
mysql_port="${MYSQL_ROOT_PORT:-3306}"
mysql_user="${MYSQL_ROOT_USER:-root}"
mysql_password="${MYSQL_ROOT_PASSWORD:-}"

db_name_sed="$(sed_escape "${UFO_DB_NAME}")"
db_user_sed="$(sed_escape "${UFO_DB_USER}")"
db_password_sed="$(sed_escape "$(sql_escape "${UFO_DB_PASSWORD}")")"

tmp_sql="$(mktemp)"
trap 'rm -f "${tmp_sql}"' EXIT

sed -e "s/__UFO_DB_NAME__/${db_name_sed}/g" \
    -e "s/__UFO_DB_USER__/${db_user_sed}/g" \
    -e "s/__UFO_DB_PASSWORD__/${db_password_sed}/g" \
    "${TEMPLATE_FILE}" > "${tmp_sql}"

mysql_cmd=(mysql "--host=${mysql_host}" "--port=${mysql_port}" "--user=${mysql_user}")

if [[ -n "${mysql_password}" ]]; then
  MYSQL_PWD="${mysql_password}" "${mysql_cmd[@]}" < "${tmp_sql}"
else
  "${mysql_cmd[@]}" < "${tmp_sql}"
fi

echo "MySQL bootstrap completed for database '${UFO_DB_NAME}'."