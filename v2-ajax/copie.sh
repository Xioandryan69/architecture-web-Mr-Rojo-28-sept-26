#!/bin/bash
> source.txt

find . -type f ! -name "*.db" ! -name "*.sqlite" !  -name "source.txt" | while read -r file; do
    echo "=== Contenu de : $file ===" >> source.txt
    cat "$file" >> source.txt
    echo -e "\n" >> source.txt # Ajoute des lignes vides pour espacer
done
