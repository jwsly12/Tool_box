#!/usr/bin/env bash

#Shell Script para reconhecimento em ambientes linux
cat <<'EOF'
 _     _                    ____                      
| |   (_)_ __  _   ___  __ |  _ \ ___  ___ ___  _ __  
| |   | | '_ \| | | \ \/ / | |_) / _ \/ __/ _ \| '_ \ 
| |___| | | | | |_| |>  <  |  _ <  __/ (_| (_) | | | |
|_____|_|_| |_|\__,_/_/\_\ |_| \_\___|\___\___/|_| |_|
EOF

echo -e "\nby: jwsly12\n"

echo -e "Ambient: $(uname -a )" 

echo -e "\n=== Environment Variables ===\n"
env
echo -e "================================\n"

#Lista de binários importantes para a exploração 
echo -e "\nImportant Tools of Exploitation\n"
bin_list=("curl" "python" "python3" "python3.8" "netstat")

for i in "${bin_list[@]}"; do
    cmd_=$(which "$i" 2>/dev/null)
    if [ -n "$cmd_" ]; then 
       echo "-> $i : $cmd_"
    else
       echo "$i Not found"
    fi
done

#
