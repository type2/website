#!/bin/bash

root_directory="." # Change to your project directory

# Function to generate vercel rewrite rule for a directory
generate_rewrite_rule() {
    local dir_path=$1
    # Remove leading './' from path
    dir_path=${dir_path#./}
    echo "  {"
    echo "    \"source\": \"/$dir_path\","
    echo "    \"destination\": \"/$dir_path/\""
    echo "  },"
}

# Create an associative array to track unique directories
declare -A unique_dirs

# Find directories containing files and generate rules
find "$root_directory" -type f | while read -r file; do
    # echo "file: $file"
    dir=$(dirname "$file")
    # echo "dir: $dir"
    # Check if directory is already processed
    if [[ -z "${unique_dirs[$dir]}" ]]; then
        unique_dirs[$dir]=1
        generate_rewrite_rule "$dir" >> rules.json
    fi
done
