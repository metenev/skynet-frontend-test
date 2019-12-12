rem @echo off
setlocal enableextensions enabledelayedexpansion
call :find-files *.php
goto :eof

:find-files
    set files=""
    for /r "..\classes" %%P in ("%~1") do (
        set args=!args! "%%~fP"
    )
    for /r "..\views" %%P in ("%~1") do (
        set args=!args! "%%~fP"
    )
    xgettext -n --from-code=UTF-8 --sort-output -o ..\locale\messages.pot %args%
