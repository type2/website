#!/bin/bash

root_directory="." # Set this to your project root directory

# Function to generate MIME type using 'file' command
generate_mime_type() {
    file --mime-type -b "$1"
}

# Walk through the directory tree
find "$root_directory" -type d -name '.git' -prune -o -type f ! -name "*.*" -print | while read -r file; do
    mime_type=$(generate_mime_type "$file")
    relative_path=${file#"$root_directory"/}

    # Generate vercel.json headers entry
    echo "    {"
    echo "      \"source\": \"/$relative_path\","
    echo "      \"headers\": [{ \"key\": \"Content-Type\", \"value\": \"$mime_type\" }]"
    echo "    },"
done
