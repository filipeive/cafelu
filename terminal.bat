cd C:\laragon\bin\apache\httpd-2.4.62-240904-win64-VS17\bin

# Instalar Apache como serviço
.\httpd.exe -k install -n "Laragon Apache"

# Navegar para pasta do MySQL
cd C:\laragon\bin\mysql\mysql-8.4.3-winx64\bin

# Instalar MySQL como serviço
.\mysqld.exe --install "Laragon MySQL"

# Configurar para iniciar automaticamente
sc config "Laragon Apache" start= auto
sc config "Laragon MySQL" start= auto

# Iniciar serviços
net start "Laragon Apache"
net start "Laragon MySQL"