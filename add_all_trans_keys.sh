#!/bin/bash

EN_LANG="resources/lang/en/all.php"
AR_LANG="resources/lang/ar/all.php"

# Extract keys from Blade files, including spaces and punctuation
KEYS=$(grep -rhoP "trans\('all\.([^']+)'\)" resources/views \
    | sed -E "s/trans\('all\.([^']+)'\)/\1/" \
    | sort | uniq)

add_keys() {
    LANG_FILE="$1"
    LANG_PLACEHOLDER="$2"
    TMP_FILE="${LANG_FILE}.tmp"

    # Remove closing bracket
    head -n -1 "$LANG_FILE" > "$TMP_FILE"

    # For each key, check if it exists, if not, add it
    while IFS= read -r KEY; do
        # Escape single quotes for grep
        ESCAPED_KEY=$(printf "%s" "$KEY" | sed "s/'/\\\\'/g")
        if ! grep -q "'$ESCAPED_KEY'" "$LANG_FILE"; then
            echo "    '$KEY' => '$LANG_PLACEHOLDER'," >> "$TMP_FILE"
        fi
    done <<< "$KEYS"

    # Add closing bracket
    echo "];" >> "$TMP_FILE"
    mv "$TMP_FILE" "$LANG_FILE"
}

add_keys "$EN_LANG" "TODO: English translation"
add_keys "$AR_LANG" "TODO: الترجمة العربية"

echo "Done! Missing keys (including spaces and punctuation) added to $EN_LANG and $AR_LANG."
