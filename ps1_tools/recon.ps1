# ========================================
# Reconhecimento de Ambiente Windows
# ========================================

# 1 Privilégios Atuais
# 2 Usuários 
# 3 Grupos

# 1 Processor Rodando
#
#

$banner = @"

 __      __  _             _                         ___                         
 \ \    / / (_)  _ _    __| |  ___  __ __ __  ___   | _ \  ___   __   ___   _ _  
  \ \/\/ /  | | | ' \  / _`  | / _ \ \ V  V / (_-<   |   / / -_) / _| / _ \ | ' \ 
   \_/\_/   |_| |_||_| \__,_| \___/  \_/\_/  /__/   |_|_\ \___| \__| \___/ |_||_|

By: jwsly12                                                                               
"@

Write-Host $banner -ForegroundColor Cyan

# Privilégios Atuais 
whoami /all /fo list

#Grupos

#Usuários
