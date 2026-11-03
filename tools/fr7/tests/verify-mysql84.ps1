[CmdletBinding()]
param(
    [string]$Repository = 'C:\laragon\www\fletes_ram',
    [string]$PhpBinary = ''
)
$ErrorActionPreference = 'Stop'
$fr7TemporaryWorktree = $null
$fr7EnvironmentNames = @('FR7_TEST_CREATE_FIXTURES','FR7_TEST_PORT','FR7_TEST_USER','FR7_TEST_PASS')
$fr7PreviousEnvironment = @{}
foreach ($fr7Name in $fr7EnvironmentNames) { $fr7PreviousEnvironment[$fr7Name] = [Environment]::GetEnvironmentVariable($fr7Name,'Process') }
try {
    if (-not (Test-Path -LiteralPath $Repository)) { throw 'No se encontro el repositorio existente de Fletes RAM.' }
    if (-not $PhpBinary) {
        $fr7Candidates = Get-ChildItem 'C:\laragon\bin\php' -Directory -Filter 'php-8.4*' | Sort-Object Name -Descending
        foreach ($fr7Candidate in $fr7Candidates) {
            $fr7CandidateBinary = Join-Path $fr7Candidate.FullName 'php.exe'
            if (Test-Path -LiteralPath $fr7CandidateBinary) { $PhpBinary = $fr7CandidateBinary; break }
        }
    }
    if (-not $PhpBinary -or -not (Test-Path -LiteralPath $PhpBinary)) { throw 'Indica la ruta del PHP 8.4 existente con -PhpBinary.' }
    & $PhpBinary -r "exit(PHP_VERSION_ID >= 80400 && extension_loaded('pdo_mysql') ? 0 : 1);"
    if ($LASTEXITCODE -ne 0) { throw 'El PHP seleccionado necesita version >=8.4 y PDO MySQL.' }
    & git -C $Repository fetch origin refs/heads/fr-7/migrador-legacy-moderno
    if ($LASTEXITCODE -ne 0) { throw 'No se pudo leer la rama FR-7; no se cambio tu checkout.' }
    $fr7TemporaryWorktree = Join-Path ([IO.Path]::GetTempPath()) ('fr7-mysql84-' + [guid]::NewGuid().ToString('N'))
    & git -C $Repository worktree add --detach $fr7TemporaryWorktree FETCH_HEAD
    if ($LASTEXITCODE -ne 0) { $fr7TemporaryWorktree = $null; throw 'No se pudo preparar el checkout temporal.' }
    $fr7Port = Read-Host 'Puerto de MySQL 8.4 local [3306]'
    if (-not $fr7Port) { $fr7Port = '3306' }
    $fr7User = Read-Host 'Usuario administrador local de pruebas [root]'
    if (-not $fr7User) { $fr7User = 'root' }
    $fr7Secret = Read-Host 'Contrasena local (Enter si esta vacia; no se mostrara)' -AsSecureString
    $env:FR7_TEST_CREATE_FIXTURES = 'YES'
    $env:FR7_TEST_PORT = $fr7Port
    $env:FR7_TEST_USER = $fr7User
    $env:FR7_TEST_PASS = [System.Net.NetworkCredential]::new('', $fr7Secret).Password
    & $PhpBinary (Join-Path $fr7TemporaryWorktree 'tools\fr7\tests\mysql84.php')
    if ($LASTEXITCODE -ne 0) { throw 'La verificacion no paso; comparte solo la salida, sin contrasenas.' }
} finally {
    foreach ($fr7Name in $fr7EnvironmentNames) { [Environment]::SetEnvironmentVariable($fr7Name,$fr7PreviousEnvironment[$fr7Name],'Process') }
    if ($fr7Secret) { $fr7Secret.Dispose() }
    if ($fr7TemporaryWorktree) {
        & git -C $Repository worktree remove -- $fr7TemporaryWorktree
        if ($LASTEXITCODE -ne 0) { Write-Warning 'El checkout temporal quedo disponible para revision; no se forzo su eliminacion.' }
    }
}
