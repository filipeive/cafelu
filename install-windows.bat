@echo off
chcp 65001 >nul
title RestPOS Restaurant Cloud ^& POS Suite - Instalador Windows
color 0A

:: Garantir que o script executa no diretório onde está localizado
cd /d "%~dp0"

cls
echo ==============================================================================
echo        RestPOS RESTAURANT CLOUD ^& POS SUITE - MOÇAMBIQUE
echo        Instalador de Terminal de Venda / KDS / Atendimento
echo        Desenvolvido por: Fdsmultiservices
echo        Suporte Técnico: (+258) 86 213 4230 ^| fdsmultiservices@gmail.com
echo ==============================================================================
echo.
echo Escolha o modo de instalação desejado:
echo.
echo [1] Configurar Terminal POS (Conectar ao RestPOS Nuvem ou Servidor Local)
echo     - Cria atalho de App Nativo na Área de Trabalho (sem barra de navegação).
echo     - Ideal para caixas/atendimento conectados à nuvem ou rede interna.
echo.
echo [2] Instalar Servidor Local Completo (Modo Offline / No Computador)
echo     - Configura o ambiente local, chaves de criptografia e banco de dados.
echo     - Cria o atalho "RestPOS Servidor Local" na Área de Trabalho.
echo.
echo [3] Iniciar o Servidor Local Imediatamente
echo.
echo [0] Sair
echo.
set /p OPTION="Digite a opção [1, 2, 3 ou 0]: "

if "%OPTION%"=="1" goto INSTALL_TERMINAL_CLOUD
if "%OPTION%"=="2" goto INSTALL_LOCAL_SERVER
if "%OPTION%"=="3" goto START_SERVER_NOW
if "%OPTION%"=="0" goto EXIT_SCRIPT

echo Opção inválida.
pause
goto EXIT_SCRIPT

:: ==============================================================================
:: OPÇÃO 1: TERMINAL POS (NUVEM OU URL REMOTA)
:: ==============================================================================
:INSTALL_TERMINAL_CLOUD
cls
echo ==============================================================================
echo        CONFIGURAÇÃO DE ATALHO DE TERMINAL POS (APP NATIVO)
echo ==============================================================================
echo.
echo Informe o endereço URL do sistema RestPOS:
echo [Pressione ENTER para usar a Nuvem: http://146.235.224.99/pos]
set /p TARGET_URL="URL [Padrão: http://146.235.224.99/pos]: "
if "%TARGET_URL%"=="" set "TARGET_URL=http://146.235.224.99/pos"

:: Detectar navegador Edge ou Chrome
set "BROWSER_EXE="
if exist "%ProgramFiles(x86)%\Microsoft\Edge\Application\msedge.exe" (
    set "BROWSER_EXE=%ProgramFiles(x86)%\Microsoft\Edge\Application\msedge.exe"
) else if exist "%ProgramFiles%\Microsoft\Edge\Application\msedge.exe" (
    set "BROWSER_EXE=%ProgramFiles%\Microsoft\Edge\Application\msedge.exe"
) else if exist "%ProgramFiles%\Google\Chrome\Application\chrome.exe" (
    set "BROWSER_EXE=%ProgramFiles%\Google\Chrome\Application\chrome.exe"
) else if exist "%ProgramFiles(x86)%\Google\Chrome\Application\chrome.exe" (
    set "BROWSER_EXE=%ProgramFiles(x86)%\Google\Chrome\Application\chrome.exe"
)

:: Criar launcher em batch específico do terminal
set "TERMINAL_LAUNCHER=%USERPROFILE%\RestPOS_Terminal.bat"
(
    echo @echo off
    if defined BROWSER_EXE (
        echo start "" "%BROWSER_EXE%" --app=%TARGET_URL%
    ) else (
        echo start "" "%TARGET_URL%"
    )
) > "%TERMINAL_LAUNCHER%"

:: Criar atalho na Área de Trabalho
powershell -ExecutionPolicy Bypass -Command "$ws = New-Object -ComObject WScript.Shell; $s = $ws.CreateShortcut([System.Environment]::GetFolderPath('Desktop') + '\RestPOS Terminal POS.lnk'); $s.TargetPath = '%TERMINAL_LAUNCHER%'; $s.WindowStyle = 7; $s.Description = 'RestPOS Restaurant Cloud Suite - Fdsmultiservices'; $s.Save()"

echo.
echo ==============================================================================
echo [OK] O atalho "RestPOS Terminal POS" foi criado na sua Área de Trabalho.
echo      URL Configurada: %TARGET_URL%
echo ==============================================================================
echo.
set /p OPEN_NOW="Deseja abrir o terminal agora? [S/N, Padrão: S]: "
if "%OPEN_NOW%"=="" set OPEN_NOW=S
if /i "%OPEN_NOW%"=="S" (
    call "%TERMINAL_LAUNCHER%"
)
goto EXIT_SCRIPT

:: ==============================================================================
:: OPÇÃO 2: INSTALAR SERVIDOR LOCAL COMPLETO
:: ==============================================================================
:INSTALL_LOCAL_SERVER
cls
echo ==============================================================================
echo        INSTALAÇÃO DE SERVIDOR LOCAL OFFLINE
echo ==============================================================================
echo.
echo Verificando PHP...
where php >nul 2>nul
if %errorlevel% neq 0 (
    echo [ERRO] PHP não encontrado no PATH do sistema.
    echo Por favor, instale o PHP 8.2 ou 8.3 ou o XAMPP e adicione ao PATH.
    pause
    goto EXIT_SCRIPT
)

echo Instalando dependências e configurando base de dados...
call composer install --no-dev --optimize-autoloader

if not exist .env (
    copy .env.example .env
    call php artisan key:generate
)

call php artisan migrate --force
call php artisan storage:link

:: Criar launcher do servidor local
set "SERVER_LAUNCHER=%USERPROFILE%\RestPOS_StartServer.bat"
(
    echo @echo off
    echo cd /d "%~dp0"
    echo title Servidor RestPOS Local
    echo echo Servidor RestPOS em execução na porta 8000...
    echo php artisan serve --host=0.0.0.0 --port=8000
) > "%SERVER_LAUNCHER%"

powershell -ExecutionPolicy Bypass -Command "$ws = New-Object -ComObject WScript.Shell; $s = $ws.CreateShortcut([System.Environment]::GetFolderPath('Desktop') + '\RestPOS Servidor Local.lnk'); $s.TargetPath = '%SERVER_LAUNCHER%'; $s.WindowStyle = 1; $s.Description = 'Iniciar Servidor RestPOS'; $s.Save()"

echo.
echo [OK] Servidor Local configurado com sucesso!
echo Atalho "RestPOS Servidor Local" criado na Área de Trabalho.
pause
goto EXIT_SCRIPT

:: ==============================================================================
:: OPÇÃO 3: INICIAR SERVIDOR LOCAL AGORA
:: ==============================================================================
:START_SERVER_NOW
cls
echo Iniciando RestPOS na porta 8000 (Acesse: http://127.0.0.1:8000)...
php artisan serve --host=0.0.0.0 --port=8000
goto EXIT_SCRIPT

:EXIT_SCRIPT
exit /b 0
