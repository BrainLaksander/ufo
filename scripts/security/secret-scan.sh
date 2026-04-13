#!/usr/bin/env bash
set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/../.." && pwd)"
cd "${ROOT_DIR}"

MODE="${1:-staged}"

if [[ "${MODE}" != "staged" && "${MODE}" != "--all" ]]; then
  echo "Usage: scripts/security/secret-scan.sh [staged|--all]" >&2
  exit 2
fi

collect_files() {
  if [[ "${MODE}" == "--all" ]]; then
    git ls-files
  else
    git diff --cached --name-only --diff-filter=ACMRTUXB
  fi
}

skip_file() {
  local file="$1"

  [[ ! -f "${file}" ]] && return 0

  case "${file}" in
    vendor/*|storage/*|public/build/*|node_modules/*)
      return 0
      ;;
  esac

  return 1
}

patterns=(
  'gh[pousr]_[A-Za-z0-9]{20,}'
  'github_pat_[A-Za-z0-9_]{20,}'
  'AKIA[0-9A-Z]{16}'
  'APP_KEY=base64:[A-Za-z0-9+/=]{20,}'
  'BEGIN[[:space:]]+(RSA|EC|DSA|OPENSSH)?[[:space:]]*PRIVATE[[:space:]]+KEY'
  'IDENTIFIED BY '\''[^'\''\n]{6,}'\'''
  'DB_PASSWORD=[^[:space:]#][^[:space:]]+'
  'USER_[0-9]+_PAT=[^[:space:]]+'
)

allowlist='(__UFO_DB_PASSWORD__|example\.com)'

has_issue=0
found_any_file=0

while IFS= read -r file; do
  [[ -z "${file}" ]] && continue

  if skip_file "${file}"; then
    continue
  fi

  found_any_file=1

  for pattern in "${patterns[@]}"; do
    matches="$(grep -nE "${pattern}" "${file}" || true)"

    if [[ -z "${matches}" ]]; then
      continue
    fi

    filtered="$(printf '%s\n' "${matches}" | grep -vE "${allowlist}" || true)"

    if [[ -n "${filtered}" ]]; then
      if [[ ${has_issue} -eq 0 ]]; then
        echo "Secret scan failed. Potential secret(s) detected:" >&2
      fi

      has_issue=1
      printf '%s\n' "${filtered}" | sed "s|^|${file}:|" >&2
    fi
  done

done < <(collect_files)

if [[ ${found_any_file} -eq 0 ]]; then
  echo "Secret scan skipped: no files to scan."
  exit 0
fi

if [[ ${has_issue} -ne 0 ]]; then
  echo "Commit blocked. Remove secrets or move them to runtime environment variables." >&2
  exit 1
fi

echo "Secret scan passed."