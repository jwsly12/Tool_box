#include <stdio.h>
#include <sys/socket.h>
#include <arpa/inet.h>
#include <unistd.h>
#include <string.h>

int main(int argc, char *argv[]) {
    if (argc < 3) {
        printf("Uso: %s <IP> <Porta-Fim>\n", argv[0]);
        return 1;
    }

    char *target_ip = argv[1];
    int end_port = atoi(argv[2]);
    struct sockaddr_in address;

    for (int port = 1; port <= end_port; port++) {
        int sock = socket(AF_INET, SOCK_STREAM, 0);
        if (sock < 0) continue;

        address.sin_family = AF_INET;
        address.sin_addr.s_addr = inet_addr(target_ip);
        address.sin_port = htons(port);

        if (connect(sock, (struct sockaddr *)&address, sizeof(address)) == 0) {
            printf("[TCP] Porta %d ABERTA\n", port);
        }

        close(sock);
    }
    return 0;
}
