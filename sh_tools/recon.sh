#!/usr/bin/env bash

# ========================================
# Reconhecimento de Ambiente Linux
# Funcional para sistemas minimalistas
# ========================================

# Banner
cat <<'EOF'
 _     _                    ____                      
| |   (_)_ __  _   ___  __ |  _ \ ___  ___ ___  _ __  
| |   | | '_ \| | | \ \/ / | |_) / _ \/ __/ _ \| '_ \ 
| |___| | | | | |_| |>  <  |  _ <  __/ (_| (_) | | | |
|_____|_|_| |_|\__,_/_/\_\ |_| \_\___|\___\___/|_| |_|
EOF

echo -e "\nby: jwsly12\n"

# Informações do Sistema
echo -e "=== System Information ==="
uname -a
echo -e "==========================\n"

# Variáveis de Ambiente
echo -e "=== Environment Variables ==="
env
echo -e "\n=== IPs found in Environment ==="
env | grep -Eo '\b([0-9]{1,3}\.){3}[0-9]{1,3}\b'
echo -e "===============================\n"

# Verificação de binários importantes
echo -e "=== Important Tools for Exploitation ==="
bins=("curl" "python" "python3" "netstat" "mysql")
for cmd in "${bins[@]}"; do
    if command -v "$cmd" >/dev/null 2>&1; then
        echo "$cmd : $(command -v $cmd)"
    else
        echo "$cmd : Not found"
    fi
done
echo -e "========================================\n"

# Funções para converter hex -> IP e port
hex2ip() {
    ip=$1
    echo "$((0x${ip:6:2})).$((0x${ip:4:2})).$((0x${ip:2:2})).$((0x${ip:0:2}))"
}

hex2port() {
    echo $((0x$1))
}

state_name() {
    case $1 in
        01) echo ESTABLISHED ;;
        02) echo SYN_SENT ;;
        03) echo SYN_RECV ;;
        04) echo FIN_WAIT1 ;;
        05) echo FIN_WAIT2 ;;
        06) echo TIME_WAIT ;;
        07) echo CLOSE ;;
        08) echo CLOSE_WAIT ;;
        09) echo LAST_ACK ;;
        0A) echo LISTEN ;;
        0B) echo CLOSING ;;
        *)  echo UNKNOWN ;;
    esac
}

parse_proc_net() {
    proto=$1
    file=$2
    echo -e "\n=== $proto Connections ==="
    tail -n +2 "$file" | while read -r line; do
        set -- $line
        local_hex=$(echo $2 | cut -d: -f1)
        local_port_hex=$(echo $2 | cut -d: -f2)
        remote_hex=$(echo $3 | cut -d: -f1)
        remote_port_hex=$(echo $3 | cut -d: -f2)
        state=$4

        local_ip=$(hex2ip "$local_hex")
        local_port=$(hex2port "$local_port_hex")
        remote_ip=$(hex2ip "$remote_hex")
        remote_port=$(hex2port "$remote_port_hex")

        if [ "$proto" = "TCP" ]; then
            state_txt=$(state_name "$state")
        else
            state_txt="-"
        fi

        echo "$proto: Local $local_ip:$local_port  Remote $remote_ip:$remote_port  State $state_txt"
    done
}

# Listando conexões TCP e UDP
[ -f /proc/net/tcp ] && parse_proc_net TCP /proc/net/tcp
[ -f /proc/net/udp ] && parse_proc_net UDP /proc/net/udp

# Usuários com shell
echo -e "\n=== Users with /bin/bash ==="
grep "/bin/bash" /etc/passwd || echo "No users with /bin/bash found"
echo -e "==============================\n"
