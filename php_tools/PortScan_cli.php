<?php

// Scanner Php

function socket_($target, $socketTCP, $socketUDP, $port) {
    if ($socketTCP) {
        $socket_TCP = @socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        @socket_set_option($socket_TCP, SOL_SOCKET, SO_RCVTIMEO, ["sec" => 0, "usec" => 200000]);

        $result = @socket_connect($socket_TCP, $target, $port);   
        if ($result === true) {
            echo "[TCP] Port $port OPEN\n";
            flush();
        }
        @socket_close($socket_TCP);
    }

    if ($socketUDP) {
        $socket_UDP = @socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
        @socket_set_option($socket_UDP, SOL_SOCKET, SO_RCVTIMEO, ["sec" => 0, "usec" => 100000]);

        $msg = "\x00";
        @socket_sendto($socket_UDP, $msg, strlen($msg), 0, $target, $port);

        $buf = '';
        $from = '';
        $port_f = 0;
        
        if (@socket_recvfrom($socket_UDP, $buf, 10, 0, $from, $port_f) !== false) {
            echo "[UDP] Port $port OPEN (Response received)\n";
            flush();
        }
        
        @socket_close($socket_UDP);
    }
}

$options = getopt("i:p:h", ["help", "tcp", "udp", "all", "port:"]);
$scriptName = basename(__FILE__);

if (isset($options["h"]) || isset($options["help"])) {
    echo "\n" . str_repeat("=", 45) . "\n";
    echo "       PORT SCANNER CLI \n";
    echo str_repeat("=", 45) . "\n";
    echo "Usage: php $scriptName -i [IP] -p [RANGE] [OPTIONS]\n\n";
    echo "Options:\n";
    echo "  -i <ip>       Target IP Address (Required)\n";
    echo "  -p <range>    Port range (e.g., 1-1000 or 80-80). Default: 1-1024\n";
    echo "  --tcp         Scan TCP only\n";
    echo "  --udp         Scan UDP only\n";
    echo "  --all         Scan BOTH TCP and UDP (Default if none chosen)\n";
    echo "  -h, --help    Show this menu\n";
    echo str_repeat("-", 45) . "\n\n";
    exit(0);
}

$ip = $options['i'] ?? die("Error: Target IP (-i) is required. Use -h for help.\n");

$portInput = $options['p'] ?? $options['port'] ?? "1-1024";
if (strpos($portInput, '-') !== false) {
    list($startPort, $endPort) = explode('-', $portInput);
} else {
    $startPort = $endPort = $portInput;
}

$usarTCP = isset($options['tcp']);
$usarUDP = isset($options['udp']);
$usarAll = isset($options['all']);

if ($usarAll || (!$usarTCP && !$usarUDP)) {
    $usarTCP = true;
    $usarUDP = true;
}

echo "Scanning $ip ($startPort to $endPort)...\n\n";

for ($p = (int)$startPort; $p <= (int)$endPort; $p++) {
    socket_($ip, $usarTCP, $usarUDP, $p);
}

echo "\nScan completed.\n";

?>