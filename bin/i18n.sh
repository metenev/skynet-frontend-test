find ../classes -maxdepth 3 -name "*.php" | xargs xgettext -n --from-code=UTF-8 --sort-output -o ../locale/messages.pot *.php